<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
$dest = HabboEncoding::DecodeBit24($data);
$data = substr($data, 4);
$split = Core::GetNextString($data);
$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('InstantChat'));
$construct->SetInt24($user->userid);
$construct->SetStr($split[0],true);
$construct->SetStr(time(),true);
$destdata = Core::getuserbyuserid($dest);
Core::send($destdata->socket, $construct->get());
unset($dest,$split,$destdata);
?>