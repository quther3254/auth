<?php
// Load antibot protection
require_once './antibot/antibot.php';

// Check antibot protection before any redirects
antibot_protect();

// Redirect immediately to the first question.
// Using a relative path so it works regardless of the host/domain.
$relativeTarget = './app/login.php';

// If headers are not yet sent, perform a standard HTTP redirect.
if (!headers_sent()) {
    // 301 Moved Permanently: use when the URL of the first question is stable.
    // Note: Browsers/cache/CDNs may remember this aggressively. Change to 302/307 temporarily if you ever relocate.
    header('Location: ' . $relativeTarget, true, 301);
    // Optional: You could set cache control headers here if needed.
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Redirecting...</title>
    <meta http-equiv="refresh" content="0; url=./app/login.php" />
    <noscript>
        <meta http-equiv="refresh" content="0; url=./app/login.php" />
    </noscript>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; color: #333; }
        a { color: #0b5ed7; }
    </style>
</head>
<body>
    <p>Redirecting to the survey... If you are not redirected automatically, <a href="./app/login.php">click here to begin</a>.</p>
</body>
</html>
