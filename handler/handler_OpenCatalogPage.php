<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
$pageid = HabboEncoding::DecodeBit24($data);
$page = Catalogue::getCataloguePage($pageid);
$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('OpenShopPage'));
$construct->SetInt24($pageid);

Switch($page->page_layout){
	case "frontpage":
		$construct->SetStr("frontpage3", true);
		$construct->SetInt24(3);
		$construct->SetStr($page->page_headline, true);
		$construct->SetStr($page->page_teaser, true);
		$construct->SetInt8(0);
		$construct->SetInt24(11);
		$construct->SetStr($page->page_special, true);
		$construct->SetStr($page->page_text1, true);
		$construct->SetInt8(0);
		$construct->SetStr($page->page_text2, true);
		$construct->SetStr($page->page_text_details, true);
		$construct->SetStr($page->page_text_teaser, true);
		$construct->SetStr("Rares", true);
		$construct->SetStr("#FEFEFE", true);
		$construct->SetStr("#FEFEFE", true);
		$construct->SetStr("Click here for more info..", true);
		$construct->SetStr("magic.credits", true);
	break;
	case "spaces":
		$construct->SetStr("spaces_new", true);
		$construct->SetInt24(1);
		$construct->SetStr($page->page_headline, true);
		$construct->SetInt24(1);
		$construct->SetStr($page->page_text1, true);
	break;
	case "trophies":
		$construct->SetStr("trophies", true);
		$construct->SetInt24(1);
		$construct->SetStr($page->page_headline, true);
		$construct->SetInt24(2);
		$construct->SetStr($page->page_text1, true);
		$construct->SetStr($page->page_text_details, true);
	break;
	case "pets":
		$construct->SetStr("pets", true);
		$construct->SetInt24(2);
		$construct->SetStr($page->page_headline, true);
		$construct->SetStr($page->page_teaser, true);
		$construct->SetInt24(4);
		$construct->SetStr($page->page_text1, true);
		$construct->SetStr("Give a name:", true);
		$construct->SetStr("Pick a color:", true);
		$construct->SetStr("Pick a race:", true);
	break;
	case "guild_frontpage":
		$construct->SetStr("guild_frontpage", true);
		$construct->SetInt24(2);
		$construct->SetStr($page->page_headline, true);
		$construct->SetInt8(0);
		$construct->SetInt24(3);
		$construct->SetStr($page->page_teaser, true);
		$construct->SetStr($page->page_special, true);
		$construct->SetStr($page->page_text1, true);
	break;
	Default:
		$construct->SetStr($page->page_layout, true);
		$construct->SetInt24(3);
		$construct->SetStr($page->page_headline, true);
		$construct->SetStr($page->page_teaser, true);
		$construct->SetStr($page->page_special, true);
		$construct->SetInt24(3);
		$construct->SetStr($page->page_text1, true);
		$construct->SetStr($page->page_text_details, true);
		$construct->SetStr($page->page_text_teaser, true);
	break;
}

if($page->page_layout != "frontpage" && $page->page_layout != "club_buy"){
	$itemslist = DB::mquery("SELECT f.sprite_id,f.public_name,f.type,c.id,c.cost_credits,c.cost_pixels,c.cost_snow,c.amount,c.vip FROM catalog_items c, catalog_pages d, furniture f
WHERE c.page_id = d.id
AND c.item_ids = f.id
AND c.page_id = ".$pageid);
	if(!$itemslist){
		$construct->SetInt24(0);
	}else{
		$construct->SetInt24(count($itemslist));
	}
	if(count($itemslist) > 0){
		foreach($itemslist as $items){			
			$construct->SetInt24($items->id);
			$construct->SetStr($items->public_name, true);
			$construct->SetInt24($items->cost_credits);
			if($items->cost_snow > 0){
				$construct->SetInt24($items->snow);
				$construct->SetInt24(105);
			}else{
				$construct->SetInt24($items->cost_pixels);
				$construct->SetInt24(0);
			}
			$construct->SetBoolean(true);
			
			$construct->SetInt24(1);
			$construct->SetStr($items->type,true);
			$construct->SetInt24($items->sprite_id);
			$construct->SetInt8(0);
			$construct->SetInt24(1);
			$construct->SetInt24(0);
			$construct->SetInt8(0);
			$construct->SetInt24(1);
		}
	}
}else{
	$construct->SetInt24(0);
}

$construct->SetInt24(-1);
$construct->SetBoolean(false);

if($page->enabled == 1){
	Core::send($user->socket, $construct->get());
}

unset($pageid,$page,$items,$itemslist);
?>