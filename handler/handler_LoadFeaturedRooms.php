<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('SerializePublicRooms'));
$construct->SetInt24(count($navigatorpublics));
foreach($navigatorpublics as $public){
	$construct->SetInt24($public->id);
	$construct->SetStr($public->caption,true);
	$construct->SetStr($public->description,true);
	$construct->SetInt8(0);
	$construct->SetInt24(0);
	$construct->SetStr($public->image,true);
	$construct->SetInt24(0);
	$construct->SetInt24(0);
	$construct->SetInt24(4);
	$construct->SetInt24(0);
	$construct->SetBoolean(true);
	$construct->SetInt24(0);
}
// Core::send($user->socket, $construct->get());
unset($public);
?>