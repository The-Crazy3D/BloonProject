<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
$x = HabboEncoding::DecodeBit24($data);
$data = substr($data, 4);
$y = HabboEncoding::DecodeBit24($data);
Core::say("X : ".$x.", Y : ".$y);
$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('pathfinding'));
$construct->SetInt24(1);
$construct->SetInt24(0);
$construct->SetInt24($x);
$construct->SetInt24($y);
$construct->SetInt8(1);
$construct->SetStr(chr(0x30));
$construct->SetInt24(2);
$construct->SetInt24(2);
$construct->SetStr("/flatcrtl 4 useradmin/",true);
Core::send($user->socket, $construct->get());
unset($construct);
?>