<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
$CONFIG = Array();
$CONFIG['bindAddr'] = "0.0.0.0"; // Server IP (you can use 0.0.0.0 for allow access from everywhere)
$CONFIG['bindPort'] = 99; // Server Port
$CONFIG['ipaccess'] = true; // Configure a access by ip to the server
$CONFIG['ipaccesslist'] = Array("127.0.0.1"); // List of ip if ipaccess is enabled (true)
$CONFIG['mysql'] = Array();
$CONFIG['mysql']['host'] = "91.236.254.65"; // SQL Host IP (no hostname)
$CONFIG['mysql']['port'] = 3306; // SQL Host Port (MySQL use 3306 by default)
$CONFIG['mysql']['user'] = "root"; // SQL User Name (root by default)
$CONFIG['mysql']['database'] = "blooncrypto"; // SQL Database
$CONFIG['mysql']['password'] = "mariakey"; // SQL Password
?>