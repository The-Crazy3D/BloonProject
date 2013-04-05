<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
Class Catalogue{
	public static function getCatalogueList($rank){
		global $cataloguepages;
		$result = array();
		foreach($cataloguepages as $pages){
			if($pages->parent_id == "-1" && $rank >= $pages->min_rank){
				$result[] = $pages;
			}
		}
		return $result;
	}
	public static function getSubCatalogueList($id, $rank){
		global $cataloguepages;
		$result = array();
		foreach($cataloguepages as $pages){
			if($pages->parent_id == $id && $rank >= $pages->min_rank){
				$result[] = $pages;
			}
		}
		return $result;
	}
	public static function getCataloguePage($id){
		global $cataloguepages;
		foreach($cataloguepages as $pages){
			if($id == $pages->id){
				return $pages;
			}
		}
	}
	public static function getCatalogueItems($id){
		global $catalogueitems;
		$result = array();
		foreach($catalogueitems as $items){
			if($id == $items->page_id){
				$result[] = $items;
			}
		}
		return $result;
	}
	public static function getItemData($id){
		return DB::query("SELECT * FROM furniture WHERE id = '".$id."'");
	}
}
?>