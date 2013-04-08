<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
$construct = New Constructor;
$itemid = HabboEncoding::DecodeBit24($data);
$itemquery = DB::query("SELECT f.interaction_modes_count,i.extra_data FROM furniture f, items i
WHERE i.base_item = f.id
AND i.id = ".$itemid);
$construct->SetHeader(Packet::GetHeader('UpdateFloorItemExtraData'));
$construct->SetStr($itemid, true);
$construct->SetInt24(0);
if(!is_numeric($itemquery->extra_data)){
	$interaction = 1;
	DB::exec("UPDATE items SET extra_data='1' WHERE (id='".$itemid."')");
}else{
	if($itemquery->extra_data == $itemquery->interaction_modes_count){
		$interaction = 1;
		DB::exec("UPDATE items SET extra_data='1' WHERE (id='".$itemid."')");
	}else{
		$interaction = $itemquery->extra_data+1;
		DB::exec("UPDATE items SET extra_data='".$interaction."' WHERE (id='".$itemid."')");
	}
}
$construct->SetStr($interaction, true);
Core::SendToAllRoom($user->room_id, $construct->get());
unset($itemid,$itemquery);
?>