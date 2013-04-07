<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
$split = Core::GetNextString($data);
$info = $split[0];
$info = explode(" ", $info);
$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('AddFloorItemToRoom'));
$construct->SetInt24($info[0]);
$item = Core::getItemData($info[0]);
var_dump($item);
$construct->SetInt24($item->base_item);
$construct->SetInt24($info[1]);
$construct->SetInt24($info[2]);
$construct->SetInt24($info[3]);
$construct->SetStr("0", true);
$construct->SetInt24(0);
$construct->SetInt24(0);
$construct->SetBoolean(false);
$construct->SetBoolean(false);
$construct->SetInt24(-1);
$construct->SetInt24(1);
$construct->SetInt24($user->userid);
$construct->SetStr($user->username,true);
Core::SendToAllRoom($user->room_id, $construct->get());
unset($info,$split,$item);
?>