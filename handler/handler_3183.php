<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('initUser'));
$construct->SetInt24($user->userid);
$construct->SetStr($user->username,true);
$construct->SetStr($user->look,true);
$construct->SetStr($user->gender,true);
$construct->SetStr($user->motto,true);
$construct->SetInt24(0);
$construct->SetInt24(0);
$construct->SetInt24(768);
$construct->SetInt24(769);
$construct->SetStr("28/03/2013 11:50:05", true);
$construct->SetInt8(0);
Core::send($user->socket, $construct->get());
unset($construct);

$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('init8sso'));
$construct->SetInt24(15);
Core::send($user->socket, $construct->get());
unset($construct);
?>