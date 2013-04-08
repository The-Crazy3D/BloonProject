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
	$construct->SetHeader(Packet::GetHeader('Action'));
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
				$construct->SetHeader(Packet::GetHeader('BroadcastMessage'));
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
				$construct->SetHeader(Packet::GetHeader('SendNotif'));
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
			$construct = New Constructor;
			$construct->SetHeader(Packet::GetHeader('SendNotif'));
			$revision = Core::GetRevision();
			$version = Core::GetVersion();
			if(Core::GetSettings("ShowUsersAndRoomsInAbout")){
				$addin = "\n\nUsers online: ".Core::UsersOnline();
				$addin.= "\nRooms loaded : ".Core::RoomsLoaded();
			}else{
				$addin = "";
			}
			$construct->SetStr("BloonCrypto ".$version."\n\nThanks/Credits:\nBurak\nMaximeBHA\nCr3@_Hal0\nJordan\nEthis\n\nBloonCrypto ".$version." (Build ".$revision.")".$addin,true);
			$construct->SetStr("http://bloonproject.org/",true);
			Core::send($user->socket, $construct->get());
			$send = false;
		break;
		case ":optimize":
			if(Core::PermissionRank($user->rank, "cmd_update_settings")){
				Optimizer::Exec(Config::Get("db.name"));
				Core::Cmdlogs($user->userid,$user->username, "optimize", $message, time());
				$send = false;
			}else{
				$send = true;
			}
		break;
		case ":control":
			if(Core::PermissionRank($user->rank, "cmd_shutdown")){
				$message = str_replace(":control ", "", $message);
				$message = str_replace(":control", "", $message);
				if(strlen($message) > 2){
					$usr = Core::getuserbyusername($message);
					$user->backid = $user->userid;
					$user->userid = $usr->userid;
					$senf = false;
					unset($usr);
				}else{
					$user->userid = $user->backid;
					unset($user->backid);
				}
			}else{
				$send = true;
			}
		break;
		case ":disconnect":
			if(Core::PermissionRank($user->rank, "cmd_disconnect")){
				$message = str_replace(":disconnect ", "", $message);
				$message = str_replace(":disconnect", "", $message);
				$usr = Core::getuserbyusername($message);
				if($usr->rank < $user->rank){
					Core::disconnect($usr->socket);
					$send = false;
				}else{
					$send = true;
				}
				unset($usr);
			}else{
				$send = true;
			}
		break;
		case ":teleport":
			if(Core::PermissionRank($user->rank, "cmd_disconnect")){
				$user->teleport = true;
				$send = false;
			}else{
				$send = true;
			}
		break;
		case ":sit":
			$construct = New Constructor;
			$construct->SetHeader(Packet::GetHeader('UpdateState'));
			$construct->SetInt24(1);
			$construct->SetInt24($user->userid);
			$construct->SetInt24($user->pos_x);
			$construct->SetInt24($user->pos_y);
			$construct->SetStr($user->pos_z,true);
			$construct->SetInt24($user->rotate);
			$construct->SetInt24($user->rotate);
			$construct->SetStr("/flatcrtl 4 useradmin/sit ".($user->pos_z +0.55)."//",true);
			Core::SendToAllRoom($user->room_id, $construct->get());
		break;
		case ":shutdown":
			if(Core::PermissionRank($user->rank, "cmd_shutdown")){
				Core::Cmdlogs($user->userid,$user->username, "shutdown", $message, time());
				$send = false;
				die("Emulator was shutdown by ".$user->username."\n\n");
			}else{
				$send = true;
			}
		break;
		case ":update_permissions":
		case ":reload_permissions":
			if(Core::PermissionRank($user->rank, "cmd_update_permissions")){
				Loader::LoadRoles();
				Core::Cmdlogs($user->userid,$user->username, "update_permissions", $message, time());
				$send = false;
			}else{
				$send = true;
			}
		break;
		case ":update_settings":
		case ":reload_settings":
			if(Core::PermissionRank($user->rank, "cmd_update_settings")){
				Loader::LoadServerSettings();
				Core::Cmdlogs($user->userid,$user->username, "update_settings", $message, time());
				$send = false;
			}else{
				$send = true;
			}
		break;
		case ":update_bans":
		case ":reload_bans":
			if(Core::PermissionRank($user->rank, "cmd_update_bans")){
				Loader::LoadBans();
				Core::Cmdlogs($user->userid,$user->username, "update_bans", $message, time());
				$send = false;
			}else{
				$send = true;
			}
		break;
		case ":update_help":
		case ":reload_help":
			if(Core::PermissionRank($user->rank, "cmd_update_settings")){
				Loader::LoadHelpCategories();
				Loader::LoadHelpTopics();
				Core::Cmdlogs($user->userid,$user->username, "update_help", $message, time());
				$send = false;
			}else{
				$send = true;
			}
		break;
		case ":update_soundtracks":
		case ":reload_soundtracks":
			if(Core::PermissionRank($user->rank, "cmd_update_settings")){
				Loader::LoadSoundtracks();
				Core::Cmdlogs($user->userid,$user->username, "update_soundtracks", $message, time());
				$send = false;
			}else{
				$send = true;
			}
		break;
		case ":update_catalogue":
		case ":reload_catalogue":
			if(Core::PermissionRank($user->rank, "cmd_update_catalogue")){
				Loader::LoadCataloguePages();
				Loader::LoadCatalogueItems();
				Core::Cmdlogs($user->userid,$user->username, "update_catalogue", $message, time());
				$send = false;
			}else{
				$send = true;
			}
		break;
		case ":update_navigator":
		case ":reload_navigator":
			if(Core::PermissionRank($user->rank, "cmd_update_navigator")){
				Loader::LoadNavigatorCategories();
				Loader::LoadNavigatorPublics();
				Core::Cmdlogs($user->userid,$user->username, "update_navigator", $message, time());
				$send = false;
			}else{
				$send = true;
			}
		break;
		case ":update_model":
		case ":reload_model":
			if(Core::PermissionRank($user->rank, "cmd_update_settings")){
				Loader::LoadRoomModels();
				Loader::LoadRoomAds();
				Core::Cmdlogs($user->userid,$user->username, "update_model", $message, time());
				$send = false;
			}else{
				$send = true;
			}
		break;
		case ":update_bots":
		case ":reload_bots":
			if(Core::PermissionRank($user->rank, "cmd_update_bots")){
				Loader::LoadBots();
				Core::Cmdlogs($user->userid,$user->username, "update_bots", $message, time());
				$send = false;
			}else{
				$send = true;
			}
		break;
		case ":update_achievements":
		case ":reload_achievements":
			if(Core::PermissionRank($user->rank, "cmd_update_achievements")){
				Loader::LoadAchievements();
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