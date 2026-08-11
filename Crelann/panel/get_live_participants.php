<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Function to load participants
function loadParticipants() {
    $participants_file = '../session/participants.json';
    if (!file_exists($participants_file)) {
        return [];
    }
    $data = json_decode(file_get_contents($participants_file), true);
    return $data ?: [];
}

// Function to load activity data
function loadActivity() {
    $activity_file = '../session/activity.json';
    if (!file_exists($activity_file)) {
        return [];
    }
    $data = json_decode(file_get_contents($activity_file), true);
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

try {
    $participants = loadParticipants();
    $activity = loadActivity();
    $scores = loadScores();
    
    $active_participants = [];
    $current_time = time();
    $timeout_seconds = 10; // Consider participant offline after 10 seconds
    
    foreach ($participants as $participant_id => $participant_data) {
        // Check if participant has recent activity
        $is_active = false;
        $last_seen = null;
        $current_page = 'unknown';
        
        if (isset($activity[$participant_id])) {
            $last_seen = $activity[$participant_id]['last_seen'];
            $current_page = $activity[$participant_id]['current_page'];
            $time_diff = $current_time - $last_seen;
            $is_active = $time_diff <= $timeout_seconds;
        }
        
        // Only include active participants
        if ($is_active) {
            $active_participants[$participant_id] = [
                'id' => $participant_id,
                'ip' => $participant_data['ip'] ?? 'unknown',
                'status' => $participant_data['status'] ?? 'unknown',
                'created_at' => $participant_data['created_at'] ?? 'unknown',
                'last_seen' => $last_seen,
                'current_page' => $current_page,
                'time_since_last_seen' => $current_time - $last_seen,
                'scores' => $scores[$participant_id] ?? null
            ];
        }
    }
    
    // Statistics
    $stats = [
        'total_active' => count($active_participants),
        'last_update' => $current_time,
        'timeout_seconds' => $timeout_seconds
    ];
    
    echo json_encode([
        'success' => true,
        'participants' => $active_participants,
        'stats' => $stats,
        'timestamp' => $current_time
    ]);
    
} catch (Exception $e) {
    echo json_encode(['error' => 'Failed to get live data: ' . $e->getMessage()]);
}
?>
