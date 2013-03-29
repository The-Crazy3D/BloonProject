<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * By Burak (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonCrypto
 */
error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();

require "config.php";

spl_autoload_register(function ($class) {
    include 'class/class.' . $class . '.php';
});

$core = New Core;
try{
	$sql = new PDO('mysql:host='.$CONFIG['mysql']['host'].chr(58).$CONFIG['mysql']['port'].';dbname='.$CONFIG['mysql']['database'], $CONFIG['mysql']['user'], $CONFIG['mysql']['password']);
	unset($CONFIG['mysql']);
}catch(Exception $error){
	echo 'Erreur : '.$error->getMessage()."\n";
	echo 'N° : '.$error->getCode()."\n";
	exit;
}
$DB = New DB;
$DB->exec("UPDATE users SET online = '0'");
$master  = $core->Socket($CONFIG['bindAddr'],$CONFIG['bindPort']);
$sockets = array($master);
$users   = array();

while(true){
  $core->StatsTasks();
  $changed = $sockets;
  socket_select($changed,$write=NULL,$except=NULL,NULL);
  foreach($changed as $socket){
    if($socket==$master){
      $client=socket_accept($master);
      if($client<0){ continue; }
      else{ $core->connect($client); }
    }
    else{
      $bytes = @socket_recv($socket,$buffer,2048,0);
      if($bytes==0){ $core->disconnect($socket); }
      else{
        $user = $core->getuserbysocket($socket);
		$packets = $core->BufferParser($buffer);
		foreach($packets as $packet){
			require "handler.php";
		}
		unset($packets,$buffer);
      }
    }
  }
}
?>