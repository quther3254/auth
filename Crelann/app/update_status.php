<?php
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Get form data
$participant_id = $_POST['participant_id'] ?? null;
$new_status = $_POST['status'] ?? null;

if (!$participant_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input - missing participant_id']);
    exit;
}

$scores_file = '../session/scores.json';

try {
    // Read existing scores
    if (!file_exists($scores_file)) {
        // Create empty scores file
        file_put_contents($scores_file, json_encode([]));
        $scores = [];
    } else {
        $content = file_get_contents($scores_file);
        if (empty($content)) {
            $scores = [];
        } else {
            $scores = json_decode($content, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $scores = [];
            }
        }
    }
    
    // Update the participant's status
    if (isset($scores[$participant_id])) {
        $scores[$participant_id]['status'] = $new_status;
        $scores[$participant_id]['updated_at'] = date('Y-m-d H:i:s');
    } else {
        // Create new entry
        $scores[$participant_id] = [
            'id' => $participant_id,
            'status' => $new_status,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
    }
    
    // Save back to file
    file_put_contents($scores_file, json_encode($scores, JSON_PRETTY_PRINT));
    
    echo json_encode(['success' => true, 'message' => 'Status updated', 'participant_id' => $participant_id, 'status' => $new_status]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
?>
