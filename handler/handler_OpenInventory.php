<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('Inventory'));
$construct->SetStr("S",true);
$construct->SetInt24(1);
$construct->SetInt24(1);
$flooritems = Core::GetFloorItems($user->userid);
if(!$flooritems){
	$construct->SetInt24(0);
}else{
	$construct->SetInt24(count($flooritems));
	foreach($flooritems as $flooritem){
		$construct->SetInt24($flooritem->id);
		$construct->SetStr(strtoupper($flooritem->type),true);
		$construct->SetInt24($flooritem->id);
		$construct->SetInt24($flooritem->sprite_id);
		$construct->SetInt24(1);
		$construct->SetInt8(0);
		$construct->SetInt24(0);
		
		$construct->SetStr(chr($flooritem->allow_recycle));
		$construct->SetStr(chr($flooritem->allow_trade));
		$construct->SetStr(chr($flooritem->allow_inventory_stack));
		$construct->SetStr(chr($flooritem->allow_marketplace_sell));
		
		$construct->SetStr(chr(0xFF).chr(0xFF).chr(0xFF).chr(0xFF));
		$construct->SetInt8(0);
		$construct->SetInt24(0);
	}
}
Core::send($user->socket, $construct->get());
unset($construct);

$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('Inventory'));
$construct->SetStr("I",true);
$construct->SetInt24(1);
$construct->SetInt24(1);
$wallitems = Core::GetWallItems($user->userid);
if(!$wallitems){
	$construct->SetInt24(0);
}else{
	$construct->SetInt24(count($wallitems));
	foreach($wallitems as $wallitem){
	}
}
Core::send($user->socket, $construct->get());

unset($flooritems,$wallitems,$packet1,$packet2);
?>