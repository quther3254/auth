<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
@ini_set('html_errors','0');
@ini_set('display_errors','0');
@ini_set('display_startup_errors','0');
@ini_set('log_errors','0');

include './includes/detect.php';

$detect = new BrowserDetection();


$usragent       = $_SERVER['HTTP_USER_AGENT'];
$browserName    = $detect->getName();
$browserVer     = $detect->getVersion();
$device       = ($detect->isMobile()) ? 'mobile' : 'desktop';
$platformName   = $detect->getPlatform();
$is64bits       = $detect->is64bitPlatform();

// echo $browserName . ', ' . $is64bits . ', ' . $browserVer . ', ' . $platformName .', ' . $isMobile;

 if( $browserName == 'unknown' || $platformName == 'unknown' || $platformName == 'Linux'){
	 
	     $stripos = $stripos + 1;
			
 }
 
 if($desktop == 'off' && $device == 'desktop' || $mobile == 'off' && $device == 'mobile'){

$stripos = $stripos + 1;

}

?>