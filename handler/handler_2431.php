<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
$split = Core::GetNextString($data);
$name = str_replace("'", "\'", $split[0]);
$data = $split[1];
$split = Core::GetNextString($data);
$model = $split[0];

DB::exec("INSERT INTO rooms (caption,owner,model_name) VALUES('".$name."','".$user->username ."','".$model."')");
$id = DB::query("SELECT id as lastid FROM rooms ORDER BY -id LIMIT 1");

$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('createRoom'));
$construct->SetInt24($id->lastid);
$construct->SetStr($name,true);
Core::send($user->socket, $construct->get());
// Core::say("Make room name : ".$name." model : ".$model);
unset($split,$name,$model);
?>