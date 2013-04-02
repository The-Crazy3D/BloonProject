<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
$actionid = HabboEncoding::DecodeBit24($data);
$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('pathfinding'));
$construct->SetInt24(1);
$construct->SetInt24($user->userid);
$construct->SetInt24($user->pos_x);
$construct->SetInt24($user->pos_y);
$construct->SetInt8(1);
$construct->SetStr(chr(0x30));
$construct->SetInt24($user->rotate);
$construct->SetInt24($user->rotate);
$construct->SetStr("/flatcrtl 4 useradmin/sign ".$actionid."//",true);
Core::SendToAllRoom($user->room_id, $construct->get());
?>