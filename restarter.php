<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();
while(true){
	system("echo ________________________________________________________");
	system("echo Emulator restarted : ".date("d/m/Y").' '.date("H:i:s"));
	system("echo ________________________________________________________");
	system('"C:\xampp\php\php.exe" server.php');
}
?>