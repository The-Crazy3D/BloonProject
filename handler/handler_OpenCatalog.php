<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('OpenShop'));
$construct->SetBoolean(true);
$construct->SetInt24(0);
$construct->SetInt24(0);
$construct->SetInt24(-1);
$construct->SetStr("root",true);
$construct->SetBoolean(false);
$construct->SetBoolean(false);
$parentPage = Catalogue::getCatalogueList($user->rank);
$construct->SetInt24(count($parentPage));

foreach($parentPage as $page){
	$construct->SetBoolean(true);
	$construct->SetInt24($page->icon_color);
	$construct->SetInt24($page->icon_image);
	$construct->SetInt24($page->id);
	$construct->SetStr(strtolower(str_replace(" ", "_", $page->page_layout)),true);
	$construct->SetStr($page->caption,true);
	$childPage = Catalogue::getSubCatalogueList($page->id,$user->rank);
	$construct->SetInt24(count($childPage));
	if(count($childPage) > 0){
		foreach($childPage as $child){
			$construct->SetBoolean(true);
			$construct->SetInt24($child->icon_color);
			$construct->SetInt24($child->icon_image);
			$construct->SetInt24($child->id);
			$construct->SetStr(strtolower(str_replace(" ", "_", $child->page_layout)),true);
			$construct->SetStr($child->caption,true);
			$construct->SetInt24(0);
		}
	}
	unset($childPage,$child);
}
$construct->SetBoolean(true);
Core::send($user->socket, $construct->get());
unset($page,$parentPage);
?>