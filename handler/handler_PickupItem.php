<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
$data = substr($data, 4);
$id = HabboEncoding::DecodeBit24($data);
DB::exec("UPDATE items SET room_id = '0' WHERE id = '".$id."'");
$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('PickUpFloorItem'));
$construct->SetStr($id, true);
$construct->SetInt24(0);
$construct->SetInt24(99597);
Core::SendToAllRoom($user->room_id, $construct->get());
Core::InitInventory($user->userid);
Core::ReloadRoomFurni($user->room_id);
?>