<?php

/**
 * AntiBot Protection System - Main Integration File
 * 
 * This file provides simple functions to integrate antibot protection
 * into your existing application with minimal code changes.
 * 
 * Usage:
 * require_once './antibot/antibot.php';
 * $result = antibot_check();
 * if (!$result['allowed']) {
 *     antibot_block_page($result);
 * }
 */

// Load configuration
require_once __DIR__ . '/../config.php';

// Load AntiBot components
require_once __DIR__ . '/AntiBotEngine.php';

// Global antibot instance
$GLOBALS['antibot_engine'] = null;

/**
 * Initialize AntiBot engine
 */
function antibot_init() {
    global $antibot_config, $antibot_engine;
    
    if ($antibot_engine === null) {
        $antibot_engine = new AntiBotEngine($antibot_config);
    }
    
    return $antibot_engine;
}

/**
 * Main antibot check function - call this at the start of protected pages
 */
function antibot_check() {
    global $antibot_config;
    
    // Skip if antibot is disabled
    if (!$antibot_config['enabled']) {
        return ['allowed' => true, 'reason' => 'AntiBot disabled'];
    }
    
    $engine = antibot_init();
    
    // Handle challenge response if present
    if (isset($_POST['challenge_id']) && isset($_POST['challenge_response'])) {
        return antibot_handle_challenge();
    }
    
    return $engine->validateRequest();
}

/**
 * Handle challenge response
 */
function antibot_handle_challenge() {
    $engine = antibot_init();
    
    $challengeResponse = $_POST['challenge_response'] ?? '';
    $result = $engine->verifyChallenge($challengeResponse);
    
    if ($result['valid']) {
        // Challenge passed, allow access
        return ['allowed' => true, 'reason' => 'Challenge completed successfully'];
    } else {
        // Challenge failed, block access
        return [
            'allowed' => false, 
            'reason' => 'Challenge failed: ' . implode(', ', $result['reasons'] ?? []),
            'challenge_required' => false
        ];
    }
}

/**
 * Display block page with reason
 */
function antibot_block_page($result, $exit = true) {
    http_response_code(403);
    
    // If challenge is required, show challenge page
    if (isset($result['challenge_required']) && $result['challenge_required']) {
        echo $result['challenge_html'];
        if ($exit) exit;
        return;
    }
    
    // Otherwise show block page
    $reason = htmlspecialchars($result['reason'] ?? 'Access denied');
    $confidence = $result['confidence'] ?? 0;
    
    echo antibot_generate_block_page($reason, $confidence);
    
    if ($exit) exit;
}

/**
 * Generate HTML for block page
 */
function antibot_generate_block_page($reason, $confidence) {
    return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            margin: 0;
            padding: 40px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: white;
            border-radius: 12px;
            padding: 40px;
            max-width: 500px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 1.8rem;
        }
        .reason {
            color: #666;
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 4px solid #dc3545;
        }
        .info {
            color: #666;
            font-size: 0.9rem;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .back-btn {
            background: #007cba;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 20px;
            text-decoration: none;
            display: inline-block;
        }
        .back-btn:hover {
            background: #005a87;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">🛡️</div>
        <h1>Access Denied</h1>
        <p>Your request has been blocked by our security system.</p>
        
        <div class="reason">
            <strong>Reason:</strong> ' . $reason . '
        </div>
        
        <p>If you believe this is an error, please:</p>
        <ul style="text-align: left; color: #666;">
            <li>Check that you are in an allowed country</li>
            <li>Ensure you are not using automated tools</li>
            <li>Wait a few minutes if you were browsing too quickly</li>
            <li>Contact the site administrator if the problem persists</li>
        </ul>
        
        <a href="javascript:history.back()" class="back-btn">← Go Back</a>
        
        <div class="info">
            Security Score: ' . $confidence . '%<br>
            Time: ' . date('Y-m-d H:i:s') . '<br>
            Reference: ' . substr(md5(time() . $_SERVER['REMOTE_ADDR']), 0, 8) . '
        </div>
    </div>
</body>
</html>';
}

/**
 * Simple integration for existing pages - just add one line
 */
function antibot_protect() {
    $result = antibot_check();
    if (!$result['allowed']) {
        antibot_block_page($result);
    }
}

/**
 * Get antibot statistics for admin dashboard
 */
function antibot_get_stats() {
    $engine = antibot_init();
    return $engine->getStatistics();
}

/**
 * Add antibot protection headers
 */
function antibot_headers() {
    // Security headers
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    
    // Rate limiting headers (if blocked)
    $engine = antibot_init();
    $ip = antibot_get_client_ip();
    
    // Get rate limit info without triggering a limit
    global $antibot_config;
    if ($antibot_config['rate_limit']['enabled']) {
        require_once __DIR__ . '/RateLimiter.php';
        $rateLimiter = new RateLimiter($antibot_config['rate_limit']);
        $recentCount = $rateLimiter->getRecentRequestCount($ip, 60);
        
        header('X-RateLimit-Limit: ' . $antibot_config['rate_limit']['max_requests_per_minute']);
        header('X-RateLimit-Remaining: ' . max(0, $antibot_config['rate_limit']['max_requests_per_minute'] - $recentCount));
        header('X-RateLimit-Reset: ' . (time() + 60));
    }
}

/**
 * Get client IP address
 */
function antibot_get_client_ip() {
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
 * Check if user is from allowed country
 */
function antibot_check_country($ip = null) {
    global $antibot_config;
    
    if (!$antibot_config['country_filter']['enabled']) {
        return ['allowed' => true, 'country' => null];
    }
    
    if ($ip === null) {
        $ip = antibot_get_client_ip();
    }
    
    require_once __DIR__ . '/CountryFilter.php';
    $countryFilter = new CountryFilter($antibot_config['country_filter']);
    return $countryFilter->checkCountry($ip);
}

/**
 * Manually block an IP address
 */
function antibot_block_ip($ip, $duration = 3600) {
    require_once __DIR__ . '/RateLimiter.php';
    global $antibot_config;
    
    $rateLimiter = new RateLimiter($antibot_config['rate_limit']);
    $rateLimiter->blockIP($ip, $duration);
    
    // Also mark in session manager
    require_once __DIR__ . '/SessionManager.php';
    $sessionManager = new SessionManager($antibot_config['security']);
    $sessionManager->markBadIP($ip, 'manually_blocked');
}

/**
 * Unblock an IP address
 */
function antibot_unblock_ip($ip) {
    require_once __DIR__ . '/RateLimiter.php';
    global $antibot_config;
    
    $rateLimiter = new RateLimiter($antibot_config['rate_limit']);
    $rateLimiter->unblockIP($ip);
}

/**
 * Get recent log entries for monitoring
 */
function antibot_get_logs($limit = 50, $type = null) {
    require_once __DIR__ . '/Logger.php';
    global $antibot_config;
    
    $logger = new AntiBotLogger($antibot_config['logging']);
    return $logger->getRecentEntries($limit, $type);
}

/**
 * Emergency disable function - creates a file that disables antibot
 */
function antibot_emergency_disable() {
    $disableFile = __DIR__ . '/data/EMERGENCY_DISABLE';
    file_put_contents($disableFile, date('Y-m-d H:i:s') . " - Emergency disabled\n");
}

/**
 * Check if emergency disabled
 */
function antibot_is_emergency_disabled() {
    return file_exists(__DIR__ . '/data/EMERGENCY_DISABLE');
}

/**
 * Re-enable after emergency disable
 */
function antibot_re_enable() {
    $disableFile = __DIR__ . '/data/EMERGENCY_DISABLE';
    if (file_exists($disableFile)) {
        unlink($disableFile);
    }
}

/**
 * Honeypot field generator
 */
function antibot_honeypot_field() {
    $engine = antibot_init();
    require_once __DIR__ . '/SessionManager.php';
    global $antibot_config;
    
    $sessionManager = new SessionManager($antibot_config['security']);
    return $sessionManager->getHoneypotField();
}

/**
 * Validate honeypot on form submission
 */
function antibot_validate_honeypot() {
    require_once __DIR__ . '/SessionManager.php';
    global $antibot_config;
    
    $sessionManager = new SessionManager($antibot_config['security']);
    return $sessionManager->checkHoneypot();
}

// Auto-initialize on include
if (!antibot_is_emergency_disabled()) {
    // Add security headers
    antibot_headers();
    
    // Initialize engine
    antibot_init();
}