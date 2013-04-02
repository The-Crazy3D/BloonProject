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

$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('talkShout'));
$construct->SetInt24($user->userid);
$construct->SetStr($message,true);
$construct->SetInt24(0);
$construct->SetInt24($color);
$construct->SetInt24(0);
$construct->SetStr(chr(0xFF).chr(0xFF).chr(0xFF).chr(0xFF));
Core::SendToAllRoom($user->room_id, $construct->get());

unset($split,$message,$color);
?>