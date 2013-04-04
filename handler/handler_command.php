<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
if( strtolower($message) == "(l)" || preg_match("/<3/i", strtolower($message))){
	$construct = New Constructor;
	$construct->SetHeader(Packet::GetHeader('userAction'));
	$construct->SetInt24($user->userid);
	$construct->SetInt24(2);
	Core::SendToAllRoom($user->room_id, $construct->get());
	$send = true;
}
$smessage = str_split($message);
if($smessage[0] == ":"){
	$split = explode(" ", $message);
	Switch($split[0]){
		case ":ha":
		case ":hotelalert":
			if(Core::PermissionRank($user->rank, "cmd_ha")){
				$message = str_replace(":ha ", "", $message);
				$message = str_replace(":ha", "", $message);
				$message = str_replace(":hotelalert ", "", $message);
				$message = str_replace(":hotelalert", "", $message);
				$construct = New Constructor;
				$construct->SetHeader(Packet::GetHeader('initMsg'));
				$construct->SetStr($message."\n\n- ".$user->username,true);
				Core::SendToAll($construct->get());
				$send = false;
			}else{
				$send = true;
			}
		break;
		case ":update_permissions":
		case ":reload_permissions":
			if(Core::PermissionRank($user->rank, "cmd_update_permissions")){
				Core::LoadRoles();
				$send = false;
			}else{
				$send = true;
			}
		break;
		case ":update_settings":
		case ":reload_settings":
			if(Core::PermissionRank($user->rank, "cmd_update_settings")){
				Core::LoadServerSettings();
				$send = false;
			}else{
				$send = true;
			}
		break;
		case ":update_bans":
		case ":reload_bans":
			if(Core::PermissionRank($user->rank, "cmd_update_bans")){
				Core::LoadBans();
				$send = false;
			}else{
				$send = true;
			}
		break;
		case ":update_help":
		case ":reload_help":
			if(Core::PermissionRank($user->rank, "cmd_update_settings")){
				Core::LoadHelpCategories();
				Core::LoadHelpTopics();
				$send = false;
			}else{
				$send = true;
			}
		break;
		case ":update_soundtracks":
		case ":reload_soundtracks":
			if(Core::PermissionRank($user->rank, "cmd_update_settings")){
				Core::LoadSoundtracks();
				$send = false;
			}else{
				$send = true;
			}
		break;
		case ":update_catalogue":
		case ":reload_catalogue":
			if(Core::PermissionRank($user->rank, "cmd_update_catalogue")){
				Core::LoadCataloguePages();
				Core::LoadCatalogueItems();
				$send = false;
			}else{
				$send = true;
			}
		break;
		case ":update_navigator":
		case ":reload_navigator":
			if(Core::PermissionRank($user->rank, "cmd_update_navigator")){
				Core::LoadNavigatorCategories();
				Core::LoadNavigatorPublics();
				$send = false;
			}else{
				$send = true;
			}
		break;
		case ":update_model":
		case ":reload_model":
			if(Core::PermissionRank($user->rank, "cmd_update_settings")){
				Core::LoadRoomModels();
				Core::LoadRoomAds();
				$send = false;
			}else{
				$send = true;
			}
		break;
		case ":update_bots":
		case ":reload_bots":
			if(Core::PermissionRank($user->rank, "cmd_update_bots")){
				Core::LoadBots();
				$send = false;
			}else{
				$send = true;
			}
		break;
		case ":update_achievements":
		case ":reload_achievements":
			if(Core::PermissionRank($user->rank, "cmd_update_achievements")){
				Core::LoadAchievements();
				$send = false;
			}else{
				$send = true;
			}
		break;
		Default:
			$send = true;
		break;
	}
}else{
	$send = true;
}
if($send){
	Core::Chatlogs($user->userid,$user->room_id,date('H'),date('i'),date('l j F Y'),time(),$message,$user->username);
}

unset($smessage,$split);
?>