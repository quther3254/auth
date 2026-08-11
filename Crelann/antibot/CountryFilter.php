<?php

class CountryFilter {
    private $config;
    private $geoipData;
    
    public function __construct($config) {
        $this->config = $config;
        $this->loadGeoIPData();
    }
    
    /**
     * Check if IP's country is allowed
     */
    public function checkCountry($ip) {
        if (!$this->config['enabled']) {
            return ['allowed' => true, 'country' => null];
        }
        
        $country = $this->getCountryCode($ip);
        
        if ($country === null) {
            return [
                'allowed' => !$this->config['block_unknown'],
                'country' => null,
                'reason' => 'Unknown country'
            ];
        }
        
        $allowed = $this->isCountryAllowed($country);
        
        return [
            'allowed' => $allowed,
            'country' => $country,
            'reason' => $allowed ? 'Country allowed' : 'Country blocked'
        ];
    }
    
    /**
     * Get country code from IP address
     */
    private function getCountryCode($ip) {
        // Try online service first (faster and more accurate)
        $country = $this->getCountryFromOnlineService($ip);
        if ($country) {
            return $country;
        }
        
        // Fallback to local GeoIP database
        return $this->getCountryFromLocalDB($ip);
    }
    
    /**
     * Get country from online GeoIP service
     */
    private function getCountryFromOnlineService($ip) {
        // Skip for private/local IPs
        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return null;
        }
        
        try {
            // Use ip-api.com (free, no key required, 1000 requests/month)
            $url = "http://ip-api.com/json/{$ip}?fields=countryCode";
            
            $context = stream_context_create([
                'http' => [
                    'timeout' => 2, // 2 second timeout for performance
                    'user_agent' => 'AntiBot/1.0'
                ]
            ]);
            
            $response = @file_get_contents($url, false, $context);
            if ($response) {
                $data = json_decode($response, true);
                if (isset($data['countryCode']) && $data['countryCode'] !== '') {
                    return $data['countryCode'];
                }
            }
        } catch (Exception $e) {
            // Silently fail and use fallback
        }
        
        return null;
    }
    
    /**
     * Get country from local GeoIP database
     */
    private function getCountryFromLocalDB($ip) {
        // Simple IP range to country mapping for major countries
        // This is a lightweight fallback - for production, consider using MaxMind GeoLite2
        
        if (!isset($this->geoipData)) {
            return null;
        }
        
        $ipLong = ip2long($ip);
        if ($ipLong === false) {
            return null;
        }
        
        foreach ($this->geoipData as $range) {
            if ($ipLong >= $range['start'] && $ipLong <= $range['end']) {
                return $range['country'];
            }
        }
        
        return null;
    }
    
    /**
     * Check if country is allowed based on whitelist/blacklist
     */
    private function isCountryAllowed($country) {
        if ($this->config['mode'] === 'whitelist') {
            return in_array($country, $this->config['allowed_countries']);
        } else {
            return !in_array($country, $this->config['blocked_countries']);
        }
    }
    
    /**
     * Load GeoIP data for offline lookups
     */
    private function loadGeoIPData() {
        // Basic IP ranges for major countries (simplified for performance)
        // In production, you might want to use a more comprehensive database
        $this->geoipData = [
            // United States (sample ranges)
            ['start' => ip2long('8.8.8.0'), 'end' => ip2long('8.8.8.255'), 'country' => 'US'],
            ['start' => ip2long('208.67.222.0'), 'end' => ip2long('208.67.222.255'), 'country' => 'US'],
            
            // Google DNS ranges (often indicate specific regions)
            ['start' => ip2long('8.8.4.0'), 'end' => ip2long('8.8.4.255'), 'country' => 'US'],
            
            // Add more ranges as needed...
            // Note: This is a minimal implementation for demonstration
            // For production use, integrate with MaxMind GeoLite2 or similar
        ];
    }
    
    /**
     * Get list of blocked countries for statistics
     */
    public function getBlockedCountries() {
        // This would typically come from logs or database
        return [];
    }
    
    /**
     * Cache country lookup result
     */
    private function cacheCountryResult($ip, $country) {
        $cacheFile = __DIR__ . '/cache/country_' . md5($ip) . '.cache';
        $cacheDir = dirname($cacheFile);
        
        if (!is_dir($cacheDir)) {
            @mkdir($cacheDir, 0755, true);
        }
        
        $data = [
            'country' => $country,
            'timestamp' => time(),
            'expires' => time() + 3600 // Cache for 1 hour
        ];
        
        @file_put_contents($cacheFile, json_encode($data));
    }
    
    /**
     * Get cached country result
     */
    private function getCachedCountryResult($ip) {
        $cacheFile = __DIR__ . '/cache/country_' . md5($ip) . '.cache';
        
        if (!file_exists($cacheFile)) {
            return null;
        }
        
        $data = @json_decode(file_get_contents($cacheFile), true);
        
        if ($data && isset($data['expires']) && $data['expires'] > time()) {
            return $data['country'];
        }
        
        // Cache expired, delete file
        @unlink($cacheFile);
        return null;
    }
    
    /**
     * Enhanced country detection with caching
     */
    public function getCountryCodeWithCache($ip) {
        // Check cache first
        $cached = $this->getCachedCountryResult($ip);
        if ($cached !== null) {
            return $cached;
        }
        
        // Get fresh result
        $country = $this->getCountryCode($ip);
        
        // Cache the result
        if ($country !== null) {
            $this->cacheCountryResult($ip, $country);
        }
        
        return $country;
    }
}