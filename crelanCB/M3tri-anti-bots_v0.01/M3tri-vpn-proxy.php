<?php

$random_id = sha1(rand(0,1000000));
function getUserIPs()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}

// $ip = getUserIPs();


        $url = "https://blackbox.ipinfo.app/lookup/".$ip;
        $ch = curl_init();  
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($ch);
        curl_close($ch);
        $result = $resp;
        if($result == "Y") {
            $file = fopen("proxy-block.txt","a");
            $message = $ip."\n";
            fwrite($file, $message);
            fclose($file);
            $click = fopen("bots.txt","a");
            fwrite($click,"$ip (Detect by Proxy/VPN)"."\n");
            fclose($click);
			
            $stripos = $stripos + 1;
        }

?>