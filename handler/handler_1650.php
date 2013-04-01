<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
$roomid = HabboEncoding::DecodeBit24($data);
$roominfo = DB::query("SELECT model_name,landscape FROM rooms WHERE id = '".$roomid."'");
$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('loadRoomInfo1'));
Core::send($user->socket, $construct->get());
unset($construct);

$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('loadRoomInfo2'));
$construct->SetStr($roominfo->model_name,true);
$construct->SetInt24($roomid);
Core::send($user->socket, $construct->get());
unset($construct);

$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('loadRoomInfo3'));
$construct->SetStr("landscape",true);
$construct->SetStr($roominfo->landscape,true);
Core::send($user->socket, $construct->get());
unset($construct);

$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('loadRoomInfo4'));
$construct->SetInt24(4);
Core::send($user->socket, $construct->get());
unset($construct);

$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('loadRoomInfo5'));
Core::send($user->socket, $construct->get());
unset($construct);

$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('loadRoomInfo6'));
$construct->SetInt24(0);
$construct->SetStr(chr(0));
Core::send($user->socket, $construct->get());
unset($construct);

$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('loadRoomInfo6'));
$construct->SetInt24(0);
$construct->SetStr(chr(0));
Core::send($user->socket, $construct->get());

unset($roomid,$roominfo);
?>