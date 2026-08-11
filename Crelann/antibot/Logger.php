<?php

class AntiBotLogger {
    private $config;
    private $logFile;
    
    public function __construct($config) {
        $this->config = $config;
        $this->logFile = $config['log_file'] ?? __DIR__ . '/logs/antibot.log';
        $this->ensureLogDirectory();
    }
    
    /**
     * Log blocked request
     */
    public function logBlocked($ip, $reason, $userAgent = '', $extra = []) {
        if (!$this->config['enabled'] || !$this->config['log_blocked']) {
            return;
        }
        
        $entry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'type' => 'BLOCKED',
            'ip' => $ip,
            'reason' => $reason,
            'user_agent' => substr($userAgent, 0, 255),
            'extra' => $extra
        ];
        
        $this->writeLogEntry($entry);
    }
    
    /**
     * Log suspicious activity
     */
    public function logSuspicious($ip, $activity, $userAgent = '', $score = 0) {
        if (!$this->config['enabled'] || !$this->config['log_suspicious']) {
            return;
        }
        
        $entry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'type' => 'SUSPICIOUS',
            'ip' => $ip,
            'activity' => $activity,
            'user_agent' => substr($userAgent, 0, 255),
            'score' => $score
        ];
        
        $this->writeLogEntry($entry);
    }
    
    /**
     * Log errors
     */
    public function logError($message, $context = []) {
        if (!$this->config['enabled']) {
            return;
        }
        
        $entry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'type' => 'ERROR',
            'message' => $message,
            'context' => $context
        ];
        
        $this->writeLogEntry($entry);
    }
    
    /**
     * Log allowed requests (for monitoring)
     */
    public function logAllowed($ip, $userAgent = '', $score = 100) {
        // Only log if specifically enabled for debugging
        if (!$this->config['enabled'] || !($this->config['log_allowed'] ?? false)) {
            return;
        }
        
        $entry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'type' => 'ALLOWED',
            'ip' => $ip,
            'user_agent' => substr($userAgent, 0, 255),
            'score' => $score
        ];
        
        $this->writeLogEntry($entry);
    }
    
    /**
     * Write log entry to file
     */
    private function writeLogEntry($entry) {
        $logLine = json_encode($entry) . "\n";
        
        // Check file size and rotate if necessary
        if (file_exists($this->logFile)) {
            $size = filesize($this->logFile);
            if ($size > $this->config['max_log_size']) {
                $this->rotateLog();
            }
        }
        
        @file_put_contents($this->logFile, $logLine, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Rotate log file when it gets too large
     */
    private function rotateLog() {
        if (!file_exists($this->logFile)) {
            return;
        }
        
        $backupFile = $this->logFile . '.' . date('Y-m-d-H-i-s') . '.bak';
        @rename($this->logFile, $backupFile);
        
        // Keep only last 5 backup files
        $this->cleanupOldBackups();
    }
    
    /**
     * Clean up old backup log files
     */
    private function cleanupOldBackups() {
        $logDir = dirname($this->logFile);
        $pattern = $logDir . '/' . basename($this->logFile) . '.*.bak';
        $backups = glob($pattern);
        
        if (count($backups) > 5) {
            // Sort by modification time (oldest first)
            usort($backups, function($a, $b) {
                return filemtime($a) - filemtime($b);
            });
            
            // Remove oldest backups
            $toRemove = array_slice($backups, 0, count($backups) - 5);
            foreach ($toRemove as $file) {
                @unlink($file);
            }
        }
    }
    
    /**
     * Get count of blocked requests in time period
     */
    public function getBlockedCount($seconds = 3600) {
        if (!file_exists($this->logFile)) {
            return 0;
        }
        
        $count = 0;
        $cutoff = time() - $seconds;
        
        $handle = fopen($this->logFile, 'r');
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $entry = json_decode($line, true);
                if ($entry && $entry['type'] === 'BLOCKED') {
                    $timestamp = strtotime($entry['timestamp']);
                    if ($timestamp > $cutoff) {
                        $count++;
                    }
                }
            }
            fclose($handle);
        }
        
        return $count;
    }
    
    /**
     * Get recent log entries
     */
    public function getRecentEntries($limit = 100, $type = null) {
        if (!file_exists($this->logFile)) {
            return [];
        }
        
        $entries = [];
        $handle = fopen($this->logFile, 'r');
        
        if ($handle) {
            // Read from end of file for most recent entries
            $lines = [];
            while (($line = fgets($handle)) !== false) {
                $lines[] = $line;
            }
            fclose($handle);
            
            // Process last $limit lines
            $recentLines = array_slice($lines, -$limit);
            
            foreach ($recentLines as $line) {
                $entry = json_decode($line, true);
                if ($entry && ($type === null || $entry['type'] === $type)) {
                    $entries[] = $entry;
                }
            }
        }
        
        return array_reverse($entries); // Most recent first
    }
    
    /**
     * Get statistics from logs
     */
    public function getStatistics($hours = 24) {
        if (!file_exists($this->logFile)) {
            return [
                'total_requests' => 0,
                'blocked_requests' => 0,
                'suspicious_requests' => 0,
                'block_rate' => 0,
                'top_blocked_reasons' => [],
                'top_blocked_ips' => []
            ];
        }
        
        $stats = [
            'total_requests' => 0,
            'blocked_requests' => 0,
            'suspicious_requests' => 0,
            'allowed_requests' => 0,
            'errors' => 0,
            'blocked_reasons' => [],
            'blocked_ips' => [],
            'countries' => []
        ];
        
        $cutoff = time() - ($hours * 3600);
        $handle = fopen($this->logFile, 'r');
        
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $entry = json_decode($line, true);
                if (!$entry) continue;
                
                $timestamp = strtotime($entry['timestamp']);
                if ($timestamp < $cutoff) continue;
                
                $stats['total_requests']++;
                
                switch ($entry['type']) {
                    case 'BLOCKED':
                        $stats['blocked_requests']++;
                        
                        // Count reasons
                        $reason = $entry['reason'] ?? 'unknown';
                        $stats['blocked_reasons'][$reason] = ($stats['blocked_reasons'][$reason] ?? 0) + 1;
                        
                        // Count IPs
                        $ip = $entry['ip'] ?? 'unknown';
                        $stats['blocked_ips'][$ip] = ($stats['blocked_ips'][$ip] ?? 0) + 1;
                        
                        // Count countries if available
                        if (isset($entry['extra']['country'])) {
                            $country = $entry['extra']['country'];
                            $stats['countries'][$country] = ($stats['countries'][$country] ?? 0) + 1;
                        }
                        break;
                        
                    case 'SUSPICIOUS':
                        $stats['suspicious_requests']++;
                        break;
                        
                    case 'ALLOWED':
                        $stats['allowed_requests']++;
                        break;
                        
                    case 'ERROR':
                        $stats['errors']++;
                        break;
                }
            }
            fclose($handle);
        }
        
        // Calculate derived statistics
        $stats['block_rate'] = $stats['total_requests'] > 0 
            ? round(($stats['blocked_requests'] / $stats['total_requests']) * 100, 2) 
            : 0;
        
        // Sort and limit top entries
        arsort($stats['blocked_reasons']);
        arsort($stats['blocked_ips']);
        arsort($stats['countries']);
        
        $stats['top_blocked_reasons'] = array_slice($stats['blocked_reasons'], 0, 10, true);
        $stats['top_blocked_ips'] = array_slice($stats['blocked_ips'], 0, 10, true);
        $stats['top_countries'] = array_slice($stats['countries'], 0, 10, true);
        
        return $stats;
    }
    
    /**
     * Ensure log directory exists
     */
    private function ensureLogDirectory() {
        $dir = dirname($this->logFile);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
    }
    
    /**
     * Clear old log entries
     */
    public function clearOldEntries($days = 30) {
        if (!file_exists($this->logFile)) {
            return;
        }
        
        $cutoff = time() - ($days * 86400);
        $tempFile = $this->logFile . '.tmp';
        $kept = 0;
        
        $readHandle = fopen($this->logFile, 'r');
        $writeHandle = fopen($tempFile, 'w');
        
        if ($readHandle && $writeHandle) {
            while (($line = fgets($readHandle)) !== false) {
                $entry = json_decode($line, true);
                if ($entry) {
                    $timestamp = strtotime($entry['timestamp']);
                    if ($timestamp > $cutoff) {
                        fwrite($writeHandle, $line);
                        $kept++;
                    }
                }
            }
            
            fclose($readHandle);
            fclose($writeHandle);
            
            // Replace original file with cleaned version
            if ($kept > 0) {
                @rename($tempFile, $this->logFile);
            } else {
                @unlink($tempFile);
                @unlink($this->logFile);
            }
        }
        
        return $kept;
    }
}