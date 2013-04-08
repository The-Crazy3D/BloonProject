<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
$roomid = HabboEncoding::DecodeBit24($data);
$roominfo = DB::query("SELECT model_name,landscape,owner FROM rooms WHERE id = '".$roomid."'");
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
if($roominfo->owner == $user->username){
	$construct->SetInt24(4);
}else{
	$construct->SetInt24(0);
}
Core::send($user->socket, $construct->get());
unset($construct);

if($roominfo->owner == $user->username){
	$construct = New Constructor;
	$construct->SetHeader(Packet::GetHeader('HasOwnerRights'));
	Core::send($user->socket, $construct->get());
	unset($construct);
}

$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('RateRoom'));
$construct->SetInt24(0);
$construct->SetBoolean(false);
$rateroom = $construct->get();
Core::send($user->socket, $rateroom);

unset($roomid,$roominfo,$rateroom);
?>