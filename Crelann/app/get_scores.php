<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Get the scores.json file
$scores_file = '../session/scores.json';

try {
    if (!file_exists($scores_file)) {
        // Create empty scores file if it doesn't exist
        file_put_contents($scores_file, json_encode([]));
        echo json_encode([]);
        exit;
    }
    
    $content = file_get_contents($scores_file);
    
    if (empty($content) || trim($content) === '') {
        // File is empty, return empty object
        echo json_encode([]);
        exit;
    }
    
    // Validate JSON
    $data = json_decode($content, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        // Invalid JSON, return empty object and log error
        error_log('Invalid JSON in scores.json: ' . json_last_error_msg());
        echo json_encode([]);
        exit;
    }
    
    // Return the valid data
    echo json_encode($data);
    
} catch (Exception $e) {
    // Return empty object on any error
    error_log('Error reading scores.json: ' . $e->getMessage());
    echo json_encode([]);
}
?>
