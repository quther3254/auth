<?php

class SessionManager {
    private $config;
    private $sessionFile;
    private $badIPsFile;
    
    public function __construct($config) {
        $this->config = $config;
        $this->sessionFile = __DIR__ . '/data/valid_sessions.json';
        $this->badIPsFile = __DIR__ . '/data/bad_ips.json';
        $this->ensureDataDirectory();
        $this->startSecureSession();
    }
    
    /**
     * Start a secure session with antibot protection
     */
    private function startSecureSession() {
        if (session_status() === PHP_SESSION_NONE) {
            // Secure session configuration
            ini_set('session.cookie_httponly', 1);
            ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
            ini_set('session.use_strict_mode', 1);
            ini_set('session.cookie_samesite', 'Strict');
            
            session_start();
        }
    }
    
    /**
     * Check if current session is valid
     */
    public function isValidSession() {
        // Check session variables
        if (!isset($_SESSION['antibot_validated']) || 
            !isset($_SESSION['antibot_ip']) || 
            !isset($_SESSION['antibot_timestamp'])) {
            return false;
        }
        
        // Check if IP matches
        $currentIP = $this->getClientIP();
        if ($_SESSION['antibot_ip'] !== $currentIP) {
            $this->invalidateSession();
            return false;
        }
        
        // Check session timeout (24 hours)
        $sessionAge = time() - $_SESSION['antibot_timestamp'];
        if ($sessionAge > 86400) {
            $this->invalidateSession();
            return false;
        }
        
        // Update timestamp for active sessions
        $_SESSION['antibot_timestamp'] = time();
        return true;
    }
    
    /**
     * Create a valid session for legitimate users
     */
    public function createValidSession($ip, $userAgent) {
        $_SESSION['antibot_validated'] = true;
        $_SESSION['antibot_ip'] = $ip;
        $_SESSION['antibot_timestamp'] = time();
        $_SESSION['antibot_user_agent'] = $userAgent;
        $_SESSION['antibot_score'] = 0; // Good user
        
        // Generate session fingerprint
        $_SESSION['antibot_fingerprint'] = $this->generateFingerprint($ip, $userAgent);
        
        // Store in persistent storage for cross-session validation
        $this->storeValidSession($ip, $userAgent);
    }
    
    /**
     * Invalidate current session
     */
    public function invalidateSession() {
        // Clear antibot session variables
        unset($_SESSION['antibot_validated']);
        unset($_SESSION['antibot_ip']);
        unset($_SESSION['antibot_timestamp']);
        unset($_SESSION['antibot_user_agent']);
        unset($_SESSION['antibot_score']);
        unset($_SESSION['antibot_fingerprint']);
    }
    
    /**
     * Mark an IP as potentially malicious
     */
    public function markBadIP($ip, $reason = '') {
        $badIPs = $this->loadBadIPs();
        $badIPs[$ip] = [
            'timestamp' => time(),
            'reason' => $reason,
            'count' => ($badIPs[$ip]['count'] ?? 0) + 1
        ];
        $this->saveBadIPs($badIPs);
    }
    
    /**
     * Check if IP has been flagged as bad
     */
    public function isKnownBadIP($ip) {
        $badIPs = $this->loadBadIPs();
        
        if (!isset($badIPs[$ip])) {
            return false;
        }
        
        // Expire bad IP records after 7 days
        $age = time() - $badIPs[$ip]['timestamp'];
        if ($age > 604800) { // 7 days
            unset($badIPs[$ip]);
            $this->saveBadIPs($badIPs);
            return false;
        }
        
        return $badIPs[$ip]['count'] > 3; // Consider bad after 3 infractions
    }
    
    /**
     * Store valid session information
     */
    private function storeValidSession($ip, $userAgent) {
        $sessions = $this->loadValidSessions();
        $fingerprint = $this->generateFingerprint($ip, $userAgent);
        
        $sessions[$fingerprint] = [
            'ip' => $ip,
            'user_agent' => substr($userAgent, 0, 255), // Limit length
            'created' => time(),
            'last_seen' => time(),
            'request_count' => ($sessions[$fingerprint]['request_count'] ?? 0) + 1
        ];
        
        // Clean up old sessions
        $this->cleanupOldSessions($sessions);
        $this->saveValidSessions($sessions);
    }
    
    /**
     * Generate unique fingerprint for session
     */
    private function generateFingerprint($ip, $userAgent) {
        return hash('sha256', $ip . '|' . $userAgent . '|' . date('Y-m-d'));
    }
    
    /**
     * Get honeypot field HTML
     */
    public function getHoneypotField() {
        if (!$this->config['honeypot_enabled']) {
            return '';
        }
        
        $fieldName = 'contact_' . bin2hex(random_bytes(4));
        $_SESSION['honeypot_field'] = $fieldName;
        
        return '<input type="text" name="' . $fieldName . '" style="position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden;" tabindex="-1" autocomplete="off" />';
    }
    
    /**
     * Check honeypot field
     */
    public function checkHoneypot() {
        if (!$this->config['honeypot_enabled']) {
            return true;
        }
        
        if (!isset($_SESSION['honeypot_field'])) {
            return false; // No honeypot field set
        }
        
        $fieldName = $_SESSION['honeypot_field'];
        
        // If honeypot field is filled, it's likely a bot
        return empty($_POST[$fieldName] ?? '');
    }
    
    /**
     * Get behavioral score for current session
     */
    public function getBehaviorScore() {
        $score = 0;
        
        // Check session age
        if (isset($_SESSION['antibot_timestamp'])) {
            $sessionAge = time() - $_SESSION['antibot_timestamp'];
            if ($sessionAge < 5) {
                $score += 20; // Very fast navigation
            }
        }
        
        // Check request frequency
        if (isset($_SESSION['page_requests'])) {
            $requestCount = count($_SESSION['page_requests']);
            $timeSpan = time() - ($_SESSION['page_requests'][0] ?? time());
            
            if ($timeSpan > 0 && ($requestCount / $timeSpan) > 2) { // More than 2 requests per second
                $score += 30;
            }
        } else {
            $_SESSION['page_requests'] = [];
        }
        
        // Track current request
        $_SESSION['page_requests'][] = time();
        
        // Keep only last 10 requests
        if (count($_SESSION['page_requests']) > 10) {
            $_SESSION['page_requests'] = array_slice($_SESSION['page_requests'], -10);
        }
        
        return $score;
    }
    
    /**
     * Get average bot score for statistics
     */
    public function getAverageBotScore() {
        $sessions = $this->loadValidSessions();
        $scores = [];
        
        foreach ($sessions as $session) {
            if (isset($session['bot_score'])) {
                $scores[] = $session['bot_score'];
            }
        }
        
        return empty($scores) ? 0 : array_sum($scores) / count($scores);
    }
    
    /**
     * Load valid sessions from storage
     */
    private function loadValidSessions() {
        if (!file_exists($this->sessionFile)) {
            return [];
        }
        
        $content = @file_get_contents($this->sessionFile);
        return $content ? json_decode($content, true) : [];
    }
    
    /**
     * Save valid sessions to storage
     */
    private function saveValidSessions($sessions) {
        @file_put_contents($this->sessionFile, json_encode($sessions, JSON_PRETTY_PRINT), LOCK_EX);
    }
    
    /**
     * Load bad IPs from storage
     */
    private function loadBadIPs() {
        if (!file_exists($this->badIPsFile)) {
            return [];
        }
        
        $content = @file_get_contents($this->badIPsFile);
        return $content ? json_decode($content, true) : [];
    }
    
    /**
     * Save bad IPs to storage
     */
    private function saveBadIPs($badIPs) {
        @file_put_contents($this->badIPsFile, json_encode($badIPs, JSON_PRETTY_PRINT), LOCK_EX);
    }
    
    /**
     * Clean up old sessions
     */
    private function cleanupOldSessions(&$sessions) {
        $now = time();
        $maxAge = 86400 * 7; // 7 days
        
        foreach ($sessions as $fingerprint => $session) {
            if (($now - $session['created']) > $maxAge) {
                unset($sessions[$fingerprint]);
            }
        }
    }
    
    /**
     * Get client IP address
     */
    private function getClientIP() {
        $headers = [
            'HTTP_CF_CONNECTING_IP',
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];
        
        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ips = explode(',', $_SERVER[$header]);
                $ip = trim($ips[0]);
                
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }
    
    /**
     * Ensure data directory exists
     */
    private function ensureDataDirectory() {
        $dirs = [
            dirname($this->sessionFile),
            dirname($this->badIPsFile)
        ];
        
        foreach ($dirs as $dir) {
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
        }
    }
}