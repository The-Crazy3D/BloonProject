<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
$ticket = Core::GetNextString($data);
$data = $ticket[1];
$ticket = $ticket[0];
$userdata = DB::query("SELECT * FROM users WHERE auth_ticket = '".addslashes($ticket)."' LIMIT 1");
if(!$userdata){
	Core::disconnect($user->socket);
}else{
	// if($user->ip != $userdata->ip_last){
		// Core::disconnect($user->socket);
	// }else{
		DB::exec("UPDATE users SET online = '1' AND last_online = '".time()."' WHERE id = '".($userdata->id)."'");
		$user->userid = $userdata->id;
		$user->username = $userdata->username;
		$user->mail = $userdata->mail;
		$user->rank = $userdata->rank;
		$user->credits = $userdata->credits;
		$user->vip_points = $userdata->vip_points;
		$user->activity_points = $userdata->activity_points;
		$user->look = $userdata->look;
		$user->gender = $userdata->gender;
		$user->motto = $userdata->motto;
		$user->account_created = $userdata->account_created;
		$user->last_online = $userdata->last_online;
		$user->home_room = $userdata->home_room;
		$user->respect = $userdata->respect;
		$user->daily_respect_points = $userdata->daily_respect_points;
		$user->daily_pet_respect_points = $userdata->daily_pet_respect_points;
		$user->block_newfriends = $userdata->block_newfriends;
		$user->hide_online = $userdata->hide_online;
		$user->hide_inroom = $userdata->hide_inroom;
		$user->vip = $userdata->vip;
		$user->volume = $userdata->volume;
		$user->accept_trading = $userdata->accept_trading;
		Core::say($user->username ." logged in !",1);
		$construct = New Constructor;
		$construct->SetHeader(Packet::GetHeader('init1sso'));
		$construct->SetInt8(0);
		Core::send($user->socket, $construct->get());
		unset($construct);
		
		Core::send($user->socket, Core::HexaString('00 00 00 F7 02 07 00 00 00 09 00 14 56 4F 54 45 5F 49 4E 5F 43 4F 4D 50 45 54 49 54 49 4F 4E 53 01 00 00 00 05 54 52 41 44 45 01 00 00 00 07 43 49 54 49 5A 45 4E 01 00 00 00 09 53 41 46 45 5F 43 48 41 54 01 00 00 00 09 46 55 4C 4C 5F 43 48 41 54 01 00 00 00 0F 43 41 4C 4C 5F 4F 4E 5F 48 45 4C 50 45 52 53 01 00 00 00 09 53 41 46 45 5F 43 48 41 54 01 00 00 00 0E 55 53 45 5F 47 55 49 44 45 5F 54 4F 4F 4C 00 00 26 72 65 71 75 69 72 65 6D 65 6E 74 2E 75 6E 66 75 6C 66 69 6C 6C 65 64 2E 68 65 6C 70 65 72 5F 6C 65 76 65 6C 5F 34 00 12 4A 55 44 47 45 5F 43 48 41 54 5F 52 45 56 49 45 57 53 00 00 26 72 65 71 75 69 72 65 6D 65 6E 74 2E 75 6E 66 75 6C 66 69 6C 6C 65 64 2E 68 65 6C 70 65 72 5F 6C 65 76 65 6C 5F 36 00 09 53 41 46 45 5F 43 48 41 54 01 00 00'));
		
		$construct = New Constructor;
		$construct->SetHeader(Packet::GetHeader('init2sso'));
		Core::send($user->socket, $construct->get());
		unset($construct);
		
		$construct = New Constructor;
		$construct->SetHeader(Packet::GetHeader('init3sso'));
		$construct->SetInt24($user->userid);
		$construct->SetInt24(0);
		Core::send($user->socket, $construct->get());
		unset($construct);
		
		$construct = New Constructor;
		$construct->SetHeader(Packet::GetHeader('init4sso'));
		$construct->SetInt24(2);
		$construct->SetInt24(2);
		Core::send($user->socket, $construct->get());
		unset($construct);
		
		$construct = New Constructor;
		$construct->SetHeader(Packet::GetHeader('init5sso'));
		$construct->SetInt8(256);
		Core::send($user->socket, $construct->get());
		unset($construct);
		
		$construct = New Constructor;
		$construct->SetHeader(Packet::GetHeader('init6sso'));
		$construct->SetInt24(636);
		$construct->SetInt24(0);
		Core::send($user->socket, $construct->get());
		unset($construct);
		
		$construct = New Constructor;
		$construct->SetHeader(Packet::GetHeader('init7sso'));
		$construct->SetInt24(2);
		$construct->SetInt24(0);
		$construct->SetInt24($user->activity_points);
		$construct->SetInt24(105);
		$construct->SetInt24(0);
		Core::send($user->socket, $construct->get());
		unset($construct);
		
		$construct = New Constructor;
		$construct->SetHeader(Packet::GetHeader('initCredits'));
		$construct->SetStr($user->credits .".0",true);
		Core::send($user->socket, $construct->get());
		unset($construct);
		
		$construct = New Constructor;
		$construct->SetHeader(Packet::GetHeader('initMsg'));
		$motd = DB::query("SELECT motd FROM server_settings");
		$construct->SetStr($motd->motd,true);
		Core::send($user->socket, $construct->get());
		unset($construct,$motd);
		
		$friend = DB::mquery("SELECT u.id,u.username,u.look,u.online,u.motto FROM messenger_friendships m, users u WHERE m.user_one_id = ".$user->userid ." AND u.id = m.user_two_id ORDER BY -online;");
		if($friend){
		$construct = New Constructor;
		$construct->SetHeader(Packet::GetHeader('loadFriend'));
		$construct->SetInt24(1100);
		$construct->SetInt24(300);
		$construct->SetInt24(800);
		$construct->SetInt24(1100);
		$construct->SetInt24(0);
		$construct->SetInt24(count($friend));
		foreach($friend as $fdata){
			$construct->SetInt24($fdata->id);
			$construct->SetStr($fdata->username,true);
			$construct->SetInt24(0);
			if($fdata->online == 1){
				$construct->SetInt8(257);
			}else{
				$construct->SetInt8(0);
			}
			$construct->SetStr($fdata->look,true);
			$construct->SetInt24(0);
			$construct->SetStr($fdata->motto,true);
			$construct->SetInt24(0);
			$construct->SetInt8(257);
			$construct->SetStr(chr(0));
		}
		Core::send($user->socket, $construct->get());
		unset($construct,$friend,$fdata);
		}
		
		$request = DB::mquery("SELECT u.id,u.username,u.look,u.online,u.motto FROM messenger_requests m, users u WHERE m.to_id = ".$user->userid ." AND u.id = m.from_id;");
		if($request){
			$construct = New Constructor;
			$construct->SetHeader(Packet::GetHeader('loadFriendRequest'));
			$construct->SetInt24(count($request));
			$construct->SetInt24(count($request));
			foreach($request as $rdata){
				$construct->SetInt24($rdata->id);
				$construct->SetStr($rdata->username,true);
				$construct->SetStr($rdata->look,true);
			}
			Core::send($user->socket, $construct->get());
		}
		unset($construct,$request,$rdata);
	// }
}
?>