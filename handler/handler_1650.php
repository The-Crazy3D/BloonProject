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
$construct->SetHeader(Packet::GetHeader('PrepareRoomForUsers'));
Core::send($user->socket, $construct->get());
unset($construct);

$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('InitialRoomInformation'));
$construct->SetStr($roominfo->model_name,true);
$construct->SetInt24($roomid);
Core::send($user->socket, $construct->get());
unset($construct);

$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('RoomDecoration'));
$construct->SetStr("landscape",true);
$construct->SetStr($roominfo->landscape,true);
Core::send($user->socket, $construct->get());
unset($construct);

$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('RoomRightsLevel'));
$construct->SetInt24(4);
Core::send($user->socket, $construct->get());
unset($construct);

$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('HasOwnerRights'));
Core::send($user->socket, $construct->get());
unset($construct);

$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('RateRoom'));
$construct->SetInt24(0);
$construct->SetStr(chr(0));
$rateroom = $construct->get();
Core::send($user->socket, $rateroom);
Core::send($user->socket, $rateroom);

unset($roomid,$roominfo,$rateroom);
?>