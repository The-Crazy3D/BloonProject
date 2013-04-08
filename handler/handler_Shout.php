<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
$split = Core::GetNextString($data);
$message = $split[0];
$color = HabboEncoding::DecodeBit24($split[1]);
if($color < 0 || $color > 22){
	$color = 0;
	$message = "J'ai voulu exploité une faille sur l'émulateur mais Burak est très fort !";
	Core::disconnect($user->socket);
	Console::WriteLine($user->username ." want crash room, kill it ! (".$user->ip .")");
}
@include "handler/handler_command.php";
if($send){
	$construct = New Constructor;
	$construct->SetHeader(Packet::GetHeader('Shout'));
	$construct->SetInt24($user->userid);
	$construct->SetStr($message,true);
	$construct->SetInt24(0);
	$construct->SetInt24($color);
	$construct->SetInt24(0);
	$construct->SetInt24(-1);
	Core::SendToAllRoom($user->room_id, $construct->get());
}

unset($split,$message,$color,$send);
?>