<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Get participant ID from request
$participant_id = $_POST['participant_id'] ?? $_GET['participant_id'] ?? null;
$current_page = $_POST['current_page'] ?? $_GET['current_page'] ?? 'unknown';

if (!$participant_id) {
    echo json_encode(['error' => 'Missing participant_id']);
    exit;
}

$activity_file = '../session/activity.json';

try {
    // Read current activity data
    if (!file_exists($activity_file)) {
        $activity_data = [];
    } else {
        $content = file_get_contents($activity_file);
        $activity_data = json_decode($content, true) ?: [];
    }
    
    // Update participant's last seen time
    $activity_data[$participant_id] = [
        'last_seen' => time(),
        'current_page' => $current_page,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
    ];
    
    // Save activity data
    file_put_contents($activity_file, json_encode($activity_data, JSON_PRETTY_PRINT));
    
    echo json_encode(['success' => true, 'timestamp' => time()]);
    
} catch (Exception $e) {
    echo json_encode(['error' => 'Failed to update activity: ' . $e->getMessage()]);
}
?>
