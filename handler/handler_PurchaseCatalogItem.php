<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
$id1 = HabboEncoding::DecodeBit24($data);
$data = substr($data, 4);
$id2 = HabboEncoding::DecodeBit24($data);
$data = substr($data, 6);
$amount = HabboEncoding::DecodeBit24($data);
$item = DB::query("SELECT c.id,f.public_name,f.type,f.sprite_id,c.cost_credits,c.cost_pixels,c.cost_snow,c.item_ids
FROM catalog_items c, furniture f
WHERE c.item_ids = f.id
AND c.id = ".$id2);
if($user->credits >= $item->cost_credits*$amount){
	Core::RemoveCredits($item->cost_credits*$amount, $user->userid);
}else{
	// insufficiant credits
}

$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('SerializePurchaseInformation'));
$construct->SetInt24($item->id);
$construct->SetStr($item->public_name,true);
$construct->SetInt24($item->cost_credits);
$construct->SetInt24($item->cost_pixels);
$construct->SetInt24(0);
$construct->SetBoolean(true);
$construct->SetInt24(1);
$construct->SetStr($item->type,true);
$construct->SetInt24($item->sprite_id);
$construct->SetInt8(0);
$construct->SetInt24(1);
$construct->SetInt24(0);
$construct->SetInt8(0);
$construct->SetInt24(1);
Core::send($user->socket, $construct->get());

if($amount > 100){
	Core::disconnect($user->socket);
}else{
	for($i = 1; $i <= $amount; $i++){
		DB::exec("INSERT INTO items (user_id,base_item) VALUES('".$user->userid ."','".$item->item_ids ."')");
	}
	Core::InitInventory($user->userid);
}

unset($id1, $id2, $amount,$item);
?>