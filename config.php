<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * By Burak (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonCrypto
 */
$CONFIG = Array();
$CONFIG['bindAddr'] = "0.0.0.0"; // IP du serveur (0.0.0.0 pour être accesible de partout)
$CONFIG['bindPort'] = 99; // Port du serveur
$CONFIG['ipaccess'] = true; // Configurer un accès par IP au serveur
$CONFIG['ipaccesslist'] = Array("127.0.0.1"); // Liste des IP si ipaccess est activé (true)
$CONFIG['mysql'] = Array();
$CONFIG['mysql']['host'] = "127.0.0.1";
$CONFIG['mysql']['port'] = 3306;
$CONFIG['mysql']['user'] = "root";
$CONFIG['mysql']['database'] = "database";
$CONFIG['mysql']['password'] = "password";
?>