<?php

class RateLimiter {
    private $config;
    private $dataFile;
    
    public function __construct($config) {
        $this->config = $config;
        $this->dataFile = __DIR__ . '/data/rate_limits.json';
        $this->ensureDataDirectory();
    }
    
    /**
     * Check if IP is within rate limits
     */
    public function checkLimit($ip) {
        if (!$this->config['enabled']) {
            return ['allowed' => true, 'remaining' => 999999];
        }
        
        $this->cleanupOldEntries();
        $data = $this->loadData();
        
        $now = time();
        $ipData = $data[$ip] ?? ['requests' => [], 'blocked_until' => 0];
        
        // Check if IP is currently blocked
        if ($ipData['blocked_until'] > $now) {
            return [
                'allowed' => false,
                'remaining' => 0,
                'reset_time' => $ipData['blocked_until'],
                'reason' => 'IP temporarily blocked'
            ];
        }
        
        // Add current request
        $ipData['requests'][] = $now;
        
        // Check various time windows
        $limits = [
            'minute' => ['window' => 60, 'max' => $this->config['max_requests_per_minute']],
            'hour' => ['window' => 3600, 'max' => $this->config['max_requests_per_hour']],
            'day' => ['window' => 86400, 'max' => $this->config['max_requests_per_day']]
        ];
        
        foreach ($limits as $period => $limit) {
            $windowStart = $now - $limit['window'];
            $requestsInWindow = array_filter($ipData['requests'], function($time) use ($windowStart) {
                return $time > $windowStart;
            });
            
            if (count($requestsInWindow) > $limit['max']) {
                // Block the IP
                $ipData['blocked_until'] = $now + $this->config['block_duration'];
                $data[$ip] = $ipData;
                $this->saveData($data);
                
                return [
                    'allowed' => false,
                    'remaining' => 0,
                    'reset_time' => $ipData['blocked_until'],
                    'reason' => "Rate limit exceeded for {$period}",
                    'period' => $period
                ];
            }
        }
        
        // Update data and allow request
        $data[$ip] = $ipData;
        $this->saveData($data);
        
        // Calculate remaining requests (use most restrictive)
        $remaining = PHP_INT_MAX;
        foreach ($limits as $limit) {
            $windowStart = $now - $limit['window'];
            $requestsInWindow = array_filter($ipData['requests'], function($time) use ($windowStart) {
                return $time > $windowStart;
            });
            $remaining = min($remaining, $limit['max'] - count($requestsInWindow));
        }
        
        return [
            'allowed' => true,
            'remaining' => max(0, $remaining),
            'reset_time' => null
        ];
    }
    
    /**
     * Get recent request count for behavioral analysis
     */
    public function getRecentRequestCount($ip, $seconds = 60) {
        $data = $this->loadData();
        $ipData = $data[$ip] ?? ['requests' => []];
        
        $windowStart = time() - $seconds;
        $recentRequests = array_filter($ipData['requests'], function($time) use ($windowStart) {
            return $time > $windowStart;
        });
        
        return count($recentRequests);
    }
    
    /**
     * Get count of rate limited IPs
     */
    public function getRateLimitedCount() {
        $data = $this->loadData();
        $now = time();
        $blocked = 0;
        
        foreach ($data as $ipData) {
            if (isset($ipData['blocked_until']) && $ipData['blocked_until'] > $now) {
                $blocked++;
            }
        }
        
        return $blocked;
    }
    
    /**
     * Manually block an IP address
     */
    public function blockIP($ip, $duration = null) {
        $duration = $duration ?? $this->config['block_duration'];
        $data = $this->loadData();
        
        $data[$ip] = [
            'requests' => $data[$ip]['requests'] ?? [],
            'blocked_until' => time() + $duration,
            'manually_blocked' => true
        ];
        
        $this->saveData($data);
    }
    
    /**
     * Unblock an IP address
     */
    public function unblockIP($ip) {
        $data = $this->loadData();
        
        if (isset($data[$ip])) {
            $data[$ip]['blocked_until'] = 0;
            unset($data[$ip]['manually_blocked']);
            $this->saveData($data);
        }
    }
    
    /**
     * Get whitelist of IPs that should never be rate limited
     */
    private function getWhitelistedIPs() {
        return [
            '127.0.0.1',    // Localhost
            '::1',          // IPv6 localhost
            // Add your server's IP, monitoring IPs, etc.
        ];
    }
    
    /**
     * Check if IP is whitelisted
     */
    private function isWhitelisted($ip) {
        return in_array($ip, $this->getWhitelistedIPs());
    }
    
    /**
     * Enhanced rate limit check with whitelist
     */
    public function checkLimitWithWhitelist($ip) {
        if ($this->isWhitelisted($ip)) {
            return ['allowed' => true, 'remaining' => 999999, 'whitelisted' => true];
        }
        
        return $this->checkLimit($ip);
    }
    
    /**
     * Load rate limit data from file
     */
    private function loadData() {
        if (!file_exists($this->dataFile)) {
            return [];
        }
        
        $content = @file_get_contents($this->dataFile);
        if ($content === false) {
            return [];
        }
        
        $data = @json_decode($content, true);
        return $data ?? [];
    }
    
    /**
     * Save rate limit data to file
     */
    private function saveData($data) {
        $content = json_encode($data, JSON_PRETTY_PRINT);
        @file_put_contents($this->dataFile, $content, LOCK_EX);
    }
    
    /**
     * Clean up old entries to prevent file from growing too large
     */
    private function cleanupOldEntries() {
        $data = $this->loadData();
        $now = time();
        $maxAge = 86400 * 7; // Keep data for 7 days
        $changed = false;
        
        foreach ($data as $ip => $ipData) {
            // Remove old requests
            if (isset($ipData['requests'])) {
                $oldCount = count($ipData['requests']);
                $ipData['requests'] = array_filter($ipData['requests'], function($time) use ($now, $maxAge) {
                    return $time > ($now - $maxAge);
                });
                
                if (count($ipData['requests']) !== $oldCount) {
                    $changed = true;
                }
            }
            
            // Remove entries for unblocked IPs with no recent requests
            if ((!isset($ipData['blocked_until']) || $ipData['blocked_until'] <= $now) && 
                empty($ipData['requests']) && 
                !isset($ipData['manually_blocked'])) {
                unset($data[$ip]);
                $changed = true;
            } else {
                $data[$ip] = $ipData;
            }
        }
        
        if ($changed) {
            $this->saveData($data);
        }
    }
    
    /**
     * Ensure data directory exists
     */
    private function ensureDataDirectory() {
        $dir = dirname($this->dataFile);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
    }
    
    /**
     * Get statistics for monitoring
     */
    public function getStatistics() {
        $data = $this->loadData();
        $now = time();
        $stats = [
            'total_tracked_ips' => count($data),
            'currently_blocked' => 0,
            'requests_last_hour' => 0,
            'requests_last_minute' => 0
        ];
        
        foreach ($data as $ipData) {
            if (isset($ipData['blocked_until']) && $ipData['blocked_until'] > $now) {
                $stats['currently_blocked']++;
            }
            
            if (isset($ipData['requests'])) {
                $hourAgo = $now - 3600;
                $minuteAgo = $now - 60;
                
                foreach ($ipData['requests'] as $requestTime) {
                    if ($requestTime > $hourAgo) {
                        $stats['requests_last_hour']++;
                    }
                    if ($requestTime > $minuteAgo) {
                        $stats['requests_last_minute']++;
                    }
                }
            }
        }
        
        return $stats;
    }
}