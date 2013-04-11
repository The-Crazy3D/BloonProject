<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
$itemid = HabboEncoding::DecodeBit24($data);
$itemquery = DB::query("SELECT f.sprite_id FROM furniture f, items i
WHERE i.base_item = f.id
AND i.id = ".$itemid);
$data = substr($data, 4);
$x = HabboEncoding::DecodeBit24($data);
$data = substr($data, 4);
$y = HabboEncoding::DecodeBit24($data);
$data = substr($data, 4);
$rot = HabboEncoding::DecodeBit24($data);
DB::exec("UPDATE items SET x = '".$x."',y = '".$y."',rot = '".$rot."' where id = '".$itemid."'");
$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('UpdateItemOnRoom'));
$construct->SetInt24($itemid);
$construct->SetInt24($itemquery->sprite_id);
$construct->SetInt24($x);
$construct->SetInt24($y);
$construct->SetInt24($rot);
$construct->SetStr("0",true);
$construct->SetInt8(0);
$construct->SetInt24(0);
$construct->SetInt24(0);
$construct->SetInt24(-1);
$construct->SetInt24(1);
$construct->SetInt24(99597);
Core::SendToAllRoom($user->room_id, $construct->get());
Core::ReloadRoomFurni($user->room_id);
unset($itemid, $itemquery, $x, $y, $rot);
?>