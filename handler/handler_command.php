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
				Core::Cmdlogs($user->userid,$user->username, "ha", $message, time());
				$send = false;
			}else{
				$send = true;
			}
		break;
		case ":hal":
			if(Core::PermissionRank($user->rank, "cmd_hal")){
				$message = str_replace(":hal ", "", $message);
				$message = str_replace(":hal", "", $message);
				$message = str_replace($split[1]." ", "", $message);
				$construct = New Constructor;
				$construct->SetHeader(Packet::GetHeader('alertLink'));
				$construct->SetStr($message."\n\n- ".$user->username,true);
				$construct->SetStr($split[1],true);
				Core::SendToAll($construct->get());
				Core::Cmdlogs($user->userid,$user->username, "hal", $message." ".$split[1], time());
				$send = false;
			}else{
				$send = true;
			}
		break;
		case ":about":
		case ":info":
			
		break;
		case ":update_permissions":
		case ":reload_permissions":
			if(Core::PermissionRank($user->rank, "cmd_update_permissions")){
				Core::LoadRoles();
				Core::Cmdlogs($user->userid,$user->username, "update_permissions", $message, time());
				$send = false;
			}else{
				$send = true;
			}
		break;
		case ":update_settings":
		case ":reload_settings":
			if(Core::PermissionRank($user->rank, "cmd_update_settings")){
				Core::LoadServerSettings();
				Core::Cmdlogs($user->userid,$user->username, "update_settings", $message, time());
				$send = false;
			}else{
				$send = true;
			}
		break;
		case ":update_bans":
		case ":reload_bans":
			if(Core::PermissionRank($user->rank, "cmd_update_bans")){
				Core::LoadBans();
				Core::Cmdlogs($user->userid,$user->username, "update_bans", $message, time());
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
				Core::Cmdlogs($user->userid,$user->username, "update_help", $message, time());
				$send = false;
			}else{
				$send = true;
			}
		break;
		case ":update_soundtracks":
		case ":reload_soundtracks":
			if(Core::PermissionRank($user->rank, "cmd_update_settings")){
				Core::LoadSoundtracks();
				Core::Cmdlogs($user->userid,$user->username, "update_soundtracks", $message, time());
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
				Core::Cmdlogs($user->userid,$user->username, "update_catalogue", $message, time());
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
				Core::Cmdlogs($user->userid,$user->username, "update_navigator", $message, time());
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
				Core::Cmdlogs($user->userid,$user->username, "update_model", $message, time());
				$send = false;
			}else{
				$send = true;
			}
		break;
		case ":update_bots":
		case ":reload_bots":
			if(Core::PermissionRank($user->rank, "cmd_update_bots")){
				Core::LoadBots();
				Core::Cmdlogs($user->userid,$user->username, "update_bots", $message, time());
				$send = false;
			}else{
				$send = true;
			}
		break;
		case ":update_achievements":
		case ":reload_achievements":
			if(Core::PermissionRank($user->rank, "cmd_update_achievements")){
				Core::LoadAchievements();
				Core::Cmdlogs($user->userid,$user->username, "update_achievements", $message, time());
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