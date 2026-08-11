<?php
// AntiBot Administration Panel
require_once __DIR__ . '/../antibot/antibot.php';

// Simple authentication (you should enhance this)
$admin_password = 'admin123'; // Change this!
$authenticated = false;

if (isset($_POST['password']) && $_POST['password'] === $admin_password) {
    $_SESSION['antibot_admin'] = true;
}

if (isset($_SESSION['antibot_admin']) && $_SESSION['antibot_admin']) {
    $authenticated = true;
}

// Handle admin actions
if ($authenticated && isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'block_ip':
            if (!empty($_POST['ip'])) {
                antibot_block_ip($_POST['ip'], (int)($_POST['duration'] ?? 3600));
                $message = "IP {$_POST['ip']} has been blocked.";
            }
            break;
            
        case 'unblock_ip':
            if (!empty($_POST['ip'])) {
                antibot_unblock_ip($_POST['ip']);
                $message = "IP {$_POST['ip']} has been unblocked.";
            }
            break;
            
        case 'emergency_disable':
            antibot_emergency_disable();
            $message = "AntiBot system has been emergency disabled!";
            break;
            
        case 'emergency_enable':
            antibot_re_enable();
            $message = "AntiBot system has been re-enabled.";
            break;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AntiBot Administration</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: #007cba;
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .content {
            padding: 20px;
        }
        .login-form {
            max-width: 400px;
            margin: 50px auto;
            padding: 40px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            border-left: 4px solid #007cba;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #007cba;
        }
        .stat-label {
            color: #666;
            margin-top: 5px;
        }
        .logs {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            font-family: monospace;
            font-size: 12px;
            max-height: 400px;
            overflow-y: auto;
        }
        .log-entry {
            margin: 5px 0;
            padding: 5px;
            border-radius: 3px;
        }
        .log-blocked { background: #ffe6e6; }
        .log-suspicious { background: #fff3cd; }
        .log-allowed { background: #e6f3e6; }
        .log-error { background: #f8d7da; }
        .controls {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        .control-panel {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
        }
        .form-group {
            margin: 15px 0;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select, button {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        button {
            background: #007cba;
            color: white;
            border: none;
            cursor: pointer;
            margin: 5px;
        }
        button:hover { background: #005a87; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #218838; }
        .message {
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .emergency-notice {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php if (!$authenticated): ?>
        <div class="login-form">
            <h2>🛡️ AntiBot Admin Login</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Admin Password:</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit">Login</button>
            </form>
        </div>
    <?php else: ?>
        <div class="container">
            <div class="header">
                <h1>🛡️ AntiBot Administration Panel</h1>
                <div>
                    <small>Last updated: <?= date('Y-m-d H:i:s') ?></small>
                    <a href="?logout=1" style="color: white; margin-left: 20px;">Logout</a>
                </div>
            </div>
            
            <div class="content">
                <?php if (isset($message)): ?>
                    <div class="message"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>
                
                <?php if (antibot_is_emergency_disabled()): ?>
                    <div class="emergency-notice">
                        ⚠️ ANTIBOT SYSTEM IS EMERGENCY DISABLED ⚠️
                    </div>
                <?php endif; ?>
                
                <?php
                // Get statistics
                $stats = antibot_get_stats();
                $logs = antibot_get_logs(50);
                ?>
                
                <h2>📊 System Statistics</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number"><?= $stats['blocked_today'] ?? 0 ?></div>
                        <div class="stat-label">Blocked Today</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= $stats['rate_limited'] ?? 0 ?></div>
                        <div class="stat-label">Rate Limited IPs</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= number_format($stats['bot_score_average'] ?? 0, 1) ?>%</div>
                        <div class="stat-label">Avg. Confidence</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= count($stats['countries_blocked'] ?? []) ?></div>
                        <div class="stat-label">Countries Blocked</div>
                    </div>
                </div>
                
                <div class="controls">
                    <div class="control-panel">
                        <h3>🚫 Block IP Address</h3>
                        <form method="POST">
                            <input type="hidden" name="action" value="block_ip">
                            <div class="form-group">
                                <label>IP Address:</label>
                                <input type="text" name="ip" placeholder="192.168.1.100" required>
                            </div>
                            <div class="form-group">
                                <label>Duration (seconds):</label>
                                <select name="duration">
                                    <option value="3600">1 Hour</option>
                                    <option value="21600">6 Hours</option>
                                    <option value="86400">24 Hours</option>
                                    <option value="604800">7 Days</option>
                                </select>
                            </div>
                            <button type="submit" class="btn-danger">Block IP</button>
                        </form>
                    </div>
                    
                    <div class="control-panel">
                        <h3>✅ Unblock IP Address</h3>
                        <form method="POST">
                            <input type="hidden" name="action" value="unblock_ip">
                            <div class="form-group">
                                <label>IP Address:</label>
                                <input type="text" name="ip" placeholder="192.168.1.100" required>
                            </div>
                            <button type="submit" class="btn-success">Unblock IP</button>
                        </form>
                    </div>
                    
                    <div class="control-panel">
                        <h3>🚨 Emergency Controls</h3>
                        <?php if (antibot_is_emergency_disabled()): ?>
                            <form method="POST">
                                <input type="hidden" name="action" value="emergency_enable">
                                <button type="submit" class="btn-success">Re-enable AntiBot</button>
                            </form>
                        <?php else: ?>
                            <form method="POST" onsubmit="return confirm('This will disable ALL antibot protection! Are you sure?')">
                                <input type="hidden" name="action" value="emergency_disable">
                                <button type="submit" class="btn-danger">Emergency Disable</button>
                            </form>
                        <?php endif; ?>
                        <p><small>Use only if antibot is blocking legitimate users</small></p>
                    </div>
                </div>
                
                <h2>📋 Recent Activity Logs</h2>
                <div class="logs">
                    <?php foreach ($logs as $log): ?>
                        <div class="log-entry log-<?= strtolower($log['type']) ?>">
                            <strong><?= $log['timestamp'] ?></strong> 
                            [<?= $log['type'] ?>] 
                            <?= htmlspecialchars($log['reason'] ?? $log['activity'] ?? $log['message'] ?? '') ?>
                            <?php if (isset($log['ip'])): ?>
                                - IP: <?= htmlspecialchars($log['ip']) ?>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    
                    <?php if (empty($logs)): ?>
                        <div class="log-entry">No recent activity to display.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['logout'])): ?>
        <?php unset($_SESSION['antibot_admin']); ?>
        <script>window.location.href = window.location.pathname;</script>
    <?php endif; ?>
</body>
</html>