<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
Core::LoadNavigatorPublics();
// print_r($navigatorpublics);
$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('loadPublicRoom'));
$construct->SetInt24(count($navigatorpublics));
foreach($navigatorpublics as $public){
	$construct->SetInt24($public->id);
	$construct->SetStr($public->caption,true);
	$construct->SetStr($public->descrption,true);
	// $construct->SetInt24(0);
	$construct->SetInt8(0);
	$construct->SetInt24(0);
	$construct->SetStr($public->image,true);
	$construct->SetInt24(0);
	$construct->SetInt24(0);
	$construct->SetInt24(4);
	$construct->SetInt24(0);
	$construct->SetStr(chr(1));
	$construct->SetInt24(0);
}
// Core::send($user->socket, $construct->get());
unset($public);
?>