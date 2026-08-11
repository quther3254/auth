<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Ensure admin_username is set, fallback to 'admin' if not
if (!isset($_SESSION['admin_username'])) {
    $_SESSION['admin_username'] = 'admin';
}

// Session timeout (2 hours)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 7200)) {
    session_unset();
    session_destroy();
    header('Location: login.php?timeout=1');
    exit();
}
$_SESSION['last_activity'] = time();

// Function to load participants
function loadParticipants() {
    $participants_file = '../session/participants.json';
    if (!file_exists($participants_file)) {
        return [];
    }
    $data = json_decode(file_get_contents($participants_file), true);
    return $data ?: [];
}

// Function to load scores
function loadScores() {
    $scores_file = '../session/scores.json';
    if (!file_exists($scores_file)) {
        return [];
    }
    $data = json_decode(file_get_contents($scores_file), true);
    return $data ?: [];
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}

// Handle error messages
$error_message = '';
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'no_participant':
            $error_message = 'No participant specified for management.';
            break;
        case 'participant_not_found':
            $error_message = 'The specified participant was not found.';
            break;
        default:
            $error_message = 'An error occurred.';
    }
}

$participants = loadParticipants();
$scores = loadScores();

// Statistics
$total_participants = count($participants);
$active_participants = 0;
$completed_participants = 0;
$blocked_participants = 0;

foreach ($participants as $participant) {
    if (strpos($participant['status'], 'question') !== false || $participant['status'] === 'loading') {
        $active_participants++;
    } elseif (strpos($participant['status'], 'completed') !== false) {
        $completed_participants++;
    } elseif ($participant['status'] === 'blocked') {
        $blocked_participants++;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Survey Panel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            line-height: 1.6;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .logo h1 {
            font-size: 1.5rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logout-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        .participants-section {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .section-header {
            background: #f8f9fa;
            padding: 1.5rem;
            border-bottom: 1px solid #e9ecef;
        }

        .section-title {
            font-size: 1.25rem;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .section-subtitle {
            color: #666;
            font-size: 0.9rem;
        }

        .participants-table {
            width: 100%;
            border-collapse: collapse;
        }

        .participants-table th,
        .participants-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }

        .participants-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
        }

        .participants-table tr:hover {
            background: #f8f9fa;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-question1, .status-question2, .status-question3 {
            background: #e3f2fd;
            color: #1976d2;
        }

        .status-loading {
            background: #fff3e0;
            color: #f57c00;
        }

        .status-completed {
            background: #e8f5e8;
            color: #388e3c;
        }

        .status-blocked {
            background: #ffebee;
            color: #d32f2f;
        }

        .manage-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.8rem;
            transition: background 0.3s ease;
        }

        .manage-btn:hover {
            background: #0056b3;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #666;
        }

        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }

            .header-content {
                flex-direction: column;
                gap: 1rem;
            }

            .participants-table {
                font-size: 0.9rem;
            }

            .participants-table th,
            .participants-table td {
                padding: 0.75rem 0.5rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <h1>🛡️ Survey Admin Panel</h1>
            </div>
            <div class="user-info">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</span>
                <a href="?logout=1" class="logout-btn">Logout</a>
            </div>
        </div>
    </header>

    <div class="container">
        <?php if ($error_message): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid #f5c6cb;">
            ⚠️ <?php echo htmlspecialchars($error_message); ?>
        </div>
        <?php endif; ?>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-number"><?php echo $total_participants; ?></div>
                <div class="stat-label">Total Participants</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🔄</div>
                <div class="stat-number"><?php echo $active_participants; ?></div>
                <div class="stat-label">Active Participants</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">✅</div>
                <div class="stat-number"><?php echo $completed_participants; ?></div>
                <div class="stat-label">Completed</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🚫</div>
                <div class="stat-number"><?php echo $blocked_participants; ?></div>
                <div class="stat-label">Blocked</div>
            </div>
        </div>

        <!-- Live Participants Table -->
        <div class="participants-section">
            <div class="section-header">
                <h2 class="section-title">Live Participants 
                    <span id="live-indicator" style="color: #4caf50; font-size: 0.8rem;">●</span>
                    <span id="participant-count" style="font-size: 0.9rem; color: #666;">(0 active)</span>
                </h2>
                <p class="section-subtitle">Real-time monitoring of active participants (updates every second)</p>
            </div>

            <div id="participants-container">
                <div class="empty-state">
                    <div class="empty-state-icon">📭</div>
                    <h3>No Active Participants</h3>
                    <p>Active participants will appear here when they're online.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        let lastUpdateTime = 0;
        
        function updateLiveParticipants() {
            fetch('get_live_participants.php?' + Date.now())
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateParticipantsTable(data.participants, data.stats);
                        lastUpdateTime = data.timestamp;
                        
                        // Update live indicator
                        document.getElementById('live-indicator').style.color = '#4caf50';
                        document.getElementById('participant-count').textContent = 
                            `(${data.stats.total_active} active)`;
                    } else {
                        console.error('Error fetching live data:', data.error);
                        document.getElementById('live-indicator').style.color = '#f44336';
                    }
                })
                .catch(error => {
                    console.error('Network error:', error);
                    document.getElementById('live-indicator').style.color = '#f44336';
                });
        }
        
        function updateParticipantsTable(participants, stats) {
            const container = document.getElementById('participants-container');
            
            if (Object.keys(participants).length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">📭</div>
                        <h3>No Active Participants</h3>
                        <p>Participants who are online will appear here.</p>
                        <small style="color: #888;">Last checked: ${new Date().toLocaleTimeString()}</small>
                    </div>
                `;
                return;
            }
            
            let tableHTML = `
                <table class="participants-table">
                    <thead>
                        <tr>
                            <th>Participant ID</th>
                            <th>Current Page</th>
                            <th>Status</th>
                            <th>IP Address</th>
                            <th>Last Seen</th>
                            <th>Score</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            for (const [participantId, participant] of Object.entries(participants)) {
                const timeSince = participant.time_since_last_seen;
                const lastSeenText = timeSince < 5 ? 'Just now' : `${timeSince}s ago`;
                const statusColor = getStatusColor(participant.current_page);
                const score = participant.scores ? participant.scores.score : 'No score';
                
                tableHTML += `
                    <tr>
                        <td title="${participantId}">${participantId.substring(0, 20)}...</td>
                        <td>
                            <span class="status-badge" style="background: ${statusColor.bg}; color: ${statusColor.text};">
                                ${participant.current_page}
                            </span>
                        </td>
                        <td>
                            <span class="status-badge status-${participant.status.replace(/[^a-z0-9]/gi, '-').toLowerCase()}">
                                ${participant.status}
                            </span>
                        </td>
                        <td>${participant.ip}</td>
                        <td>
                            <span style="color: ${timeSince <= 5 ? '#4caf50' : '#666'};">
                                ${lastSeenText}
                            </span>
                        </td>
                        <td>${score}</td>
                        <td>
                            <a href="manage.php?participant=${encodeURIComponent(participantId)}" class="manage-btn">
                                Manage
                            </a>
                        </td>
                    </tr>
                `;
            }
            
            tableHTML += `
                    </tbody>
                </table>
                <div style="text-align: center; margin-top: 1rem; color: #666; font-size: 0.9rem;">
                    Last updated: ${new Date().toLocaleTimeString()} | 
                    Showing participants active within ${stats.timeout_seconds} seconds
                </div>
            `;
            
            container.innerHTML = tableHTML;
        }
        
        function getStatusColor(page) {
            const colors = {
                'question1': { bg: '#e3f2fd', text: '#1976d2' },
                'question2': { bg: '#f3e5f5', text: '#7b1fa2' },
                'question3': { bg: '#e8f5e8', text: '#2e7d32' },
                'loading': { bg: '#fff3e0', text: '#f57c00' },
                'unknown': { bg: '#f5f5f5', text: '#666' }
            };
            return colors[page] || colors['unknown'];
        }
        
        // Update immediately and then every second
        updateLiveParticipants();
        setInterval(updateLiveParticipants, 1000);
        
        // Update indicator animation
        setInterval(() => {
            const indicator = document.getElementById('live-indicator');
            indicator.style.opacity = '0.3';
            setTimeout(() => indicator.style.opacity = '1', 100);
        }, 2000);
    </script>
    
    <style>
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #666;
        }
        
        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .empty-state h3 {
            margin-bottom: 0.5rem;
            color: #333;
        }
        
        #live-indicator {
            display: inline-block;
            transition: opacity 0.3s ease;
        }
    </style>
</body>
</html>
