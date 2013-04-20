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
$item = DB::query("SELECT f.* FROM items i, furniture f
WHERE i.base_item = f.id
AND i.id = ".$info[0]);
DB::exec("UPDATE items SET room_id = '".$user->room_id ."',x = '".$info[1]."',y='".$info[2]."' WHERE id ='".$info[0]."'");
$construct->SetInt24($item->sprite_id);
$construct->SetInt24($info[1]);
$construct->SetInt24($info[2]);
$construct->SetInt24(0);
$furniheight = Core::GetTileHeight($info[1], $info[2], $user->room_id);
if($furniheight == 0){
	$construct->SetStr("0", true);
}else{
	$construct->SetStr($furniheight, true);
}
$construct->SetInt24(0);
$construct->SetInt24(0);
$construct->SetBoolean(false);
$construct->SetBoolean(false);
$construct->SetInt24(-1);
$construct->SetInt24(1);
$construct->SetInt24($user->userid);
$construct->SetStr($user->username,true);
Core::SendToAllRoom($user->room_id, $construct->get());
Core::InitInventory($user->userid);
Core::ReloadRoomFurni($user->room_id);
unset($info,$split,$item);
?>