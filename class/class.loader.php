<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
Class Loader{
	public static function LoadServerSettings(){
		global $serversettings;
		if(isset($serversettings)){
			unset($serversettings);
		}
		Console::Write("Loading Server Settings...");
		$serversettings = DB::query("SELECT * FROM server_settings");
		Console::WriteLine("completed!");
	}
	public static function LoadBans(){
		global $bans;
		if(isset($bans)){
			unset($bans);
		}
		Console::Write("Loading Bans...");
		$bans = DB::mquery("SELECT * FROM bans");
		Console::WriteLine("completed!");
	}
	public static function LoadRoles(){
		global $permissions;
		if(isset($permissions)){
			unset($permissions);
		}
		Console::Write("Loading Roles...");
		$permissions = Array();
		$permissions['ranks'] = DB::mquery("SELECT * FROM permissions_ranks");
		$permissions['users'] = DB::mquery("SELECT * FROM permissions_users");
		$permissions['vip'] = DB::mquery("SELECT * FROM permissions_vip");
		Console::WriteLine("completed!");
	}
	public static function LoadHelpCategories(){
		global $helpcategories;
		if(isset($helpcategories)){
			unset($helpcategories);
		}
		Console::Write("Loading Help Categories...");
		$helpcategories = DB::mquery("SELECT * FROM help_subjects");
		Console::WriteLine("completed!");
	}
	public static function LoadHelpTopics(){
		global $helptopics;
		if(isset($helptopics)){
			unset($helptopics);
		}
		Console::Write("Loading Help Topics...");
		$helptopics = DB::mquery("SELECT * FROM help_topics");
		Console::WriteLine("completed!");
	}
	public static function LoadSoundtracks(){
		global $soundtracks;
		if(isset($soundtracks)){
			unset($soundtracks);
		}
		Console::Write("Loading Soundtracks...");
		$soundtracks = DB::mquery("SELECT * FROM soundtracks");
		Console::WriteLine("completed!");
	}
	public static function LoadCataloguePages(){
		global $cataloguepages;
		if(isset($cataloguepages)){
			unset($cataloguepages);
		}
		Console::Write("Loading Catalogue Pages...");
		$cataloguepages = DB::mquery("SELECT * FROM catalog_pages ORDER BY order_num ASC");
		Console::WriteLine("completed!");
	}
	public static function LoadCatalogueItems(){
		global $catalogueitems;
		if(isset($catalogueitems)){
			unset($catalogueitems);
		}
		Console::Write("Loading Catalogue Items...");
		$catalogueitems = DB::mquery("SELECT * FROM catalog_items");
		Console::WriteLine("completed!");
	}
	public static function LoadNavigatorCategories(){
		global $navigatorcategories;
		if(isset($navigatorcategories)){
			unset($navigatorcategories);
		}
		Console::Write("Loading Navigator Categories...");
		$navigatorcategories = DB::mquery("SELECT * FROM navigator_flatcats");
		Console::WriteLine("completed!");
	}
	public static function LoadNavigatorPublics(){
		global $navigatorpublics;
		if(isset($navigatorpublics)){
			unset($navigatorpublics);
		}
		Console::Write("Loading Navigator Publics...");
		$navigatorpublics = DB::mquery("SELECT * FROM navigator_publics ORDER BY -ordernum");
		Console::WriteLine("completed!");
	}
	public static function LoadRoomModels(){
		global $roommodels;
		if(isset($roommodels)){
			unset($roommodels);
		}
		Console::Write("Loading Room Models...");
		$roommodels = DB::mquery("SELECT * FROM room_models");
		Console::WriteLine("completed!");
	}
	public static function LoadRoomAds(){
		global $roomads;
		if(isset($roomads)){
			unset($roomads);
		}
		Console::Write("Loading Room Adverts...");
		$roomads = DB::mquery("SELECT * FROM room_ads");
		Console::WriteLine("completed!");
	}
	public static function LoadBots(){
		global $bots,$botsspeech,$botsresponses;
		if(isset($bots) && isset($botsspeech) && isset($botsresponses)){
			unset($bots);
			unset($botsspeech);
			unset($botsresponses);
		}
		Console::Write("Loading Bots...");
		$bots = DB::mquery("SELECT * FROM bots");
		$botsspeech = DB::mquery("SELECT * FROM bots_speech");
		$botsresponses = DB::mquery("SELECT * FROM botsresponses");
		Console::WriteLine("completed!");
	}
	public static function LoadAchievements(){
		global $achievements;
		if(isset($achievements)){
			unset($achievements);
		}
		Console::Write("Loading Achievements...");
		$achievements = DB::mquery("SELECT * FROM achievements");
		Console::WriteLine("completed!");
	}
	public static function LoadChatFilter(){
		global $chatfilter;
		if(isset($chatfilter)){
			unset($chatfilter);
		}
		Console::Write("Loading Chat Filter...");
		$chatfilter = DB::mquery("SELECT * FROM wordfilter");
		Console::WriteLine("completed!");
	}
	public static function LoadQuests(){
		global $quests;
		if(isset($quests)){
			unset($quests);
		}
		Console::Write("Loading Quests...");
		$quests = DB::mquery("SELECT * FROM quests");
		Console::WriteLine("completed!");
	}
	public static function LoadGroups(){
		global $groups,$grouprequests,$groupmemberships;
		if(isset($groups) && isset($grouprequests) && isset($groupmemberships)){
			unset($groups);
			unset($grouprequests);
			unset($groupmemberships);
		}
		Console::Write("Loading Groups...");
		$groups = DB::mquery("SELECT * FROM groups");
		$grouprequests = DB::mquery("SELECT * FROM group_requests");
		$groupmemberships = DB::mquery("SELECT * FROM group_memberships");
		Console::WriteLine("completed!");
	}
	public static function LoadHandlers(){
		global $handlers;
		$i = 0;
		Console::Write("Loading Handlers...");
		foreach (glob("handler/*.php") as $filename){
			$i++;
			$action = str_replace("handler/handler_", "", $filename);
			$action = str_replace(".php", "", $action);
			$file = file_get_contents($filename);
			$file = str_replace("<?php","", $file);
			$file = str_replace("?>","", $file);
			$handlers[$action] = $file;
		}
		Console::WriteLine("completed! (".$i." handlers loaded)");
	}
}
?>