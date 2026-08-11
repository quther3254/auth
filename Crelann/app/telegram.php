<?php
/**
 * Telegram helper.
 */
require_once __DIR__ . '/../config.php';

if (!function_exists('sendTelegramMessage')) {
    function sendTelegramMessage(string $message): bool {
        // Support both variables and constants from config
        global $bot_token, $chat_id;
        if (empty($bot_token) && defined('TELEGRAM_BOT_TOKEN')) {
            $bot_token = TELEGRAM_BOT_TOKEN;
        }
        if (empty($chat_id) && defined('TELEGRAM_CHAT_ID')) {
            $chat_id = TELEGRAM_CHAT_ID;
        }

        if (empty($bot_token) || empty($chat_id)) {
            error_log('Telegram config missing: bot token or chat id.');
            return false;
        }

        $url = "https://api.telegram.org/bot{$bot_token}/sendMessage";
        $data = [
            'chat_id' => $chat_id,
            'text' => $message,
            'parse_mode' => 'HTML',
            'disable_web_page_preview' => true
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded']
        ]);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($err) {
            error_log('Telegram cURL error: ' . $err);
            return false;
        }
        if ($status !== 200) {
            error_log('Telegram HTTP status ' . $status . ' response: ' . $response);
            return false;
        }
        // Optionally decode and inspect ok flag
        $json = json_decode($response, true);
        if (!$json || empty($json['ok'])) {
            error_log('Telegram API error response: ' . $response);
            return false;
        }
        return true;
    }
}
