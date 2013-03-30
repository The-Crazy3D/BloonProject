<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject
 */
error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();

spl_autoload_register(function ($class) {
    include 'class/class.' . $class . '.php';
});

Console::SetTitle("Loading BloonCrypto...");

require "config.php";


Console::WriteLine("Welcome to this ALPHA 1.0 of BloonCrypto...");
Console::WriteLine();
Console::Write("Connecting to database...");
try{
	if($CONFIG['mysql']['port'] != 3306){
		$portext = chr(58).$CONFIG['mysql']['port'];
	}else{
		$portext = "";
	}
	@$sql = new PDO('mysql:host='.$CONFIG['mysql']['host'].$portext.';dbname='.$CONFIG['mysql']['database'], $CONFIG['mysql']['user'], $CONFIG['mysql']['password']);
	unset($CONFIG['mysql']);
}catch(Exception $error){
	Console::WriteLine("failed!");
	Console::WriteLine("Error : ".$error->getMessage());
	exit;
}
Console::WriteLine("completed!");

Core::OnStartTasks();
$master  = Core::Socket($CONFIG['bindAddr'],$CONFIG['bindPort']);
$sockets = array($master);
$users   = array();

while(true){
  Core::StatsTasks();
  $changed = $sockets;
  socket_select($changed,$write=NULL,$except=NULL,NULL);
  foreach($changed as $socket){
    if($socket==$master){
      $client=socket_accept($master);
      if($client<0){ continue; }
      else{ Core::connect($client); }
    }else{
      $bytes = @socket_recv($socket,$buffer,2048,0);
      if($bytes==0){ Core::disconnect($socket); }
      else{
        $user = Core::getuserbysocket($socket);
		$packets = Core::BufferParser($buffer);
		foreach($packets as $packet){
			require "handler.php";
		}
		unset($packets,$buffer);
      }
    }
  }
}
?>
