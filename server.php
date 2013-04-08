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

if(!extension_loaded("pthreads")){
	Console::WriteLine("Please install phtreads ! Emulator can't run without it.");
	exit;
}

spl_autoload_register(function ($class) {
    include 'class/class.' . $class . '.php';
});

Console::SetTitle("Loading BloonCrypto...");

Config::Init();

if(Config::Get("emu.messages.buildcheck")){
	Updater::Check();
}else{
	Console::WriteLine("WARNING! Update check has been disabled in Bloon.conf, you can reactivate it on the Bloon.conf");
}

$start_load = microtime();

Console::WriteLine("Welcome to this ALPHA ".Core::GetVersion()." Build ".Core::GetRevision()." of BloonCrypto...");

Console::WriteLine();

Console::Write("Connecting to database...");

try{
	if(Config::Get("db.port") != 3306){
		$portext = chr(58).Config::Get("db.port");
	}else{
		$portext = "";
	}
	@$sql = new PDO('mysql:host='.Config::Get("db.hostname").$portext.';dbname='.Config::Get("db.name"), Config::Get("db.username"), Config::Get("db.password"));
}catch(Exception $error){
	Console::WriteLine("failed!");
	Console::WriteLine("Error : ".$error->getMessage());
	exit;
}
Console::WriteLine("completed!");

if(Config::Get("db.OptimizeOnStartup")){
	Optimizer::Exec(Config::Get("db.name"));
}

Core::OnStartTasks();

$master  = Core::Socket(Config::Get("game.tcp.bindip"),Config::Get("game.tcp.port"));
$sockets = array($master);
$users   = array();

$end_load = microtime();

$statstart = Core::DiffTime($start_load, $end_load);
Console::WriteLine("Server -> READY! (".$statstart[0]." s, ".$statstart[1]." ms)");
unset($statstart,$start_load,$end_load);
$statsexec = time();
while(true){
  if((time()-$statsexec) > 5){
	  Core::StatsTasks();
	  $statsexec = time();
  }
  $changed = $sockets;
  $write=NULL;
  $except=NULL;
  socket_select($changed,$write,$except,NULL);
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
			$packet = Core::GetHeader($packet);
			$header = $packet[0];
			$data = $packet[2];
			if(Config::Get("emu.messages.debug")){
				Core::say("[".$header."] ".$data,1);
			}
			if(Config::Get("emu.messages.debug")){
				$filepath = ("handler/handler_".Packet::GetIncoming($header).".php");
				if(file_exists($filepath)){
					@include($filepath);
				}
			}else{
				$handlerdata = @$handlers[Packet::GetIncoming($header)];
				if(isset($handlerdata)){
					@eval($handlerdata);
				}
			}
			unset($packet,$header,$construct,$data,$crossdomain,$ticket,$userdata,$filepath);
		}
		unset($packets,$buffer);
      }
    }
  }
}

function loadClass($class){
	include 'class/class.' . $class . '.php';
}
?>