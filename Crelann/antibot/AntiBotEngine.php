<?php

class AntiBotEngine {
    private $config;
    private $rateLimiter;
    private $countryFilter;
    private $sessionManager;
    private $challenge;
    private $logger;
    
    public function __construct($config) {
        $this->config = $config;
        $this->initializeComponents();
    }
    
    private function initializeComponents() {
        require_once __DIR__ . '/RateLimiter.php';
        require_once __DIR__ . '/CountryFilter.php';
        require_once __DIR__ . '/SessionManager.php';
        require_once __DIR__ . '/Challenge.php';
        require_once __DIR__ . '/Logger.php';
        
        $this->rateLimiter = new RateLimiter($this->config['rate_limit']);
        $this->countryFilter = new CountryFilter($this->config['country_filter']);
        $this->sessionManager = new SessionManager($this->config['security']);
        $this->challenge = new Challenge($this->config['bot_detection']);
        $this->logger = new AntiBotLogger($this->config['logging']);
    }
    
    /**
     * Main validation method - checks if request should be allowed
     */
    public function validateRequest() {
        try {
            $ip = $this->getClientIP();
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            $result = [
                'allowed' => true,
                'reason' => '',
                'challenge_required' => false,
                'challenge_html' => '',
                'confidence' => 100
            ];
            
            // Quick session check first (fastest)
            if ($this->sessionManager->isValidSession()) {
                return $result; // Already validated user
            }
            
            // Rate limiting check
            if ($this->config['rate_limit']['enabled']) {
                $rateLimitResult = $this->rateLimiter->checkLimit($ip);
                if (!$rateLimitResult['allowed']) {
                    $result['allowed'] = false;
                    $result['reason'] = 'Rate limit exceeded';
                    $this->logger->logBlocked($ip, 'rate_limit', $userAgent);
                    return $result;
                }
            }
            
            // Country filtering
            if ($this->config['country_filter']['enabled']) {
                $countryResult = $this->countryFilter->checkCountry($ip);
                if (!$countryResult['allowed']) {
                    $result['allowed'] = false;
                    $result['reason'] = 'Country blocked: ' . ($countryResult['country'] ?? 'unknown');
                    $this->logger->logBlocked($ip, 'country_blocked', $userAgent, $countryResult);
                    return $result;
                }
            }
            
            // Bot detection
            if ($this->config['bot_detection']['enabled']) {
                $botScore = $this->calculateBotScore($ip, $userAgent);
                $result['confidence'] = 100 - $botScore;
                
                // If high bot probability, require challenge
                if ($botScore > 70) {
                    if ($this->config['bot_detection']['javascript_challenge']) {
                        $challengeResult = $this->challenge->generateChallenge();
                        if ($challengeResult['challenge_required']) {
                            $result['challenge_required'] = true;
                            $result['challenge_html'] = $challengeResult['html'];
                            return $result;
                        }
                    } else {
                        $result['allowed'] = false;
                        $result['reason'] = 'Bot behavior detected (score: ' . $botScore . ')';
                        $this->logger->logBlocked($ip, 'bot_detected', $userAgent, ['score' => $botScore]);
                        return $result;
                    }
                }
            }
            
            // Create valid session for legitimate users
            $this->sessionManager->createValidSession($ip, $userAgent);
            
            return $result;
            
        } catch (Exception $e) {
            // Fail open if configured
            if ($this->config['performance']['fail_open']) {
                $this->logger->logError('AntiBot validation failed: ' . $e->getMessage());
                return ['allowed' => true, 'reason' => 'System error - failed open'];
            } else {
                return ['allowed' => false, 'reason' => 'System error'];
            }
        }
    }
    
    /**
     * Calculate bot probability score (0-100, higher = more likely bot)
     */
    private function calculateBotScore($ip, $userAgent) {
        $score = 0;
        
        // User Agent analysis
        if ($this->config['bot_detection']['check_user_agent']) {
            $score += $this->analyzeUserAgent($userAgent);
        }
        
        // Header analysis
        if ($this->config['bot_detection']['check_headers']) {
            $score += $this->analyzeHeaders();
        }
        
        // Behavioral analysis
        if ($this->config['bot_detection']['check_behavior']) {
            $score += $this->analyzeBehavior($ip);
        }
        
        return min(100, $score);
    }
    
    private function analyzeUserAgent($userAgent) {
        $score = 0;
        
        // Empty or suspicious user agents
        if (empty($userAgent)) {
            $score += 30;
        }
        
        // Known bot patterns
        $botPatterns = [
            '/bot/i', '/crawler/i', '/spider/i', '/scraper/i',
            '/curl/i', '/wget/i', '/python/i', '/java/i',
            '/headless/i', '/phantom/i', '/selenium/i'
        ];
        
        foreach ($botPatterns as $pattern) {
            if (preg_match($pattern, $userAgent)) {
                $score += 40;
                break;
            }
        }
        
        // Very short user agents
        if (strlen($userAgent) < 20) {
            $score += 20;
        }
        
        // Missing common browser indicators
        if (!preg_match('/Mozilla/i', $userAgent) && !preg_match('/WebKit/i', $userAgent)) {
            $score += 15;
        }
        
        return $score;
    }
    
    private function analyzeHeaders() {
        $score = 0;
        
        // Missing common headers
        $commonHeaders = ['HTTP_ACCEPT', 'HTTP_ACCEPT_LANGUAGE', 'HTTP_ACCEPT_ENCODING'];
        $missingHeaders = 0;
        
        foreach ($commonHeaders as $header) {
            if (!isset($_SERVER[$header])) {
                $missingHeaders++;
            }
        }
        
        $score += $missingHeaders * 10;
        
        // Suspicious header combinations
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && isset($_SERVER['HTTP_X_REAL_IP'])) {
            $score += 10; // Possible proxy chain
        }
        
        // Check for automation tools
        $automationHeaders = ['HTTP_X_REQUESTED_WITH', 'HTTP_X_AUTOMATION', 'HTTP_WEBDRIVER'];
        foreach ($automationHeaders as $header) {
            if (isset($_SERVER[$header])) {
                $score += 25;
            }
        }
        
        return $score;
    }
    
    private function analyzeBehavior($ip) {
        $score = 0;
        
        // Check request frequency from this IP
        $recentRequests = $this->rateLimiter->getRecentRequestCount($ip, 60); // Last minute
        
        if ($recentRequests > 10) {
            $score += 20;
        } elseif ($recentRequests > 5) {
            $score += 10;
        }
        
        // Check if IP has been flagged before
        if ($this->sessionManager->isKnownBadIP($ip)) {
            $score += 30;
        }
        
        return $score;
    }
    
    /**
     * Verify challenge response
     */
    public function verifyChallenge($challengeResponse) {
        return $this->challenge->verifyResponse($challengeResponse);
    }
    
    /**
     * Get real client IP address
     */
    private function getClientIP() {
        $headers = [
            'HTTP_CF_CONNECTING_IP',     // Cloudflare
            'HTTP_CLIENT_IP',            // Proxy
            'HTTP_X_FORWARDED_FOR',      // Load balancer/proxy
            'HTTP_X_FORWARDED',          // Proxy
            'HTTP_X_CLUSTER_CLIENT_IP',  // Cluster
            'HTTP_FORWARDED_FOR',        // Proxy
            'HTTP_FORWARDED',            // Proxy
            'REMOTE_ADDR'                // Standard
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
     * Get statistics for monitoring
     */
    public function getStatistics() {
        return [
            'blocked_today' => $this->logger->getBlockedCount(86400),
            'rate_limited' => $this->rateLimiter->getRateLimitedCount(),
            'countries_blocked' => $this->countryFilter->getBlockedCountries(),
            'bot_score_average' => $this->sessionManager->getAverageBotScore(),
        ];
    }
}