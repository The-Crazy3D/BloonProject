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
	if($user->ip != $userdata->ip_last){
		Core::disconnect($user->socket);
	}else{
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
		$construct->SetHeader($Outgoing['init1sso']);
		$construct->SetInt8(0);
		Core::send($user->socket, $construct->get());
		unset($construct);
		
		$construct = New Constructor;
		$construct->SetHeader($Outgoing['init2sso']);
		Core::send($user->socket, $construct->get());
		unset($construct);
		
		$construct = New Constructor;
		$construct->SetHeader($Outgoing['init3sso']);
		$construct->SetInt24($user->userid);
		$construct->SetInt24(0);
		Core::send($user->socket, $construct->get());
		unset($construct);
		
		$construct = New Constructor;
		$construct->SetHeader($Outgoing['init4sso']);
		$construct->SetInt24(2);
		$construct->SetInt24(2);
		Core::send($user->socket, $construct->get());
		unset($construct);
		
		$construct = New Constructor;
		$construct->SetHeader($Outgoing['init5sso']);
		$construct->SetInt8(256);
		Core::send($user->socket, $construct->get());
		unset($construct);
		
		$construct = New Constructor;
		$construct->SetHeader($Outgoing['init6sso']);
		$construct->SetInt24(636);
		$construct->SetInt24(0);
		Core::send($user->socket, $construct->get());
		unset($construct);
		
		$construct = New Constructor;
		$construct->SetHeader($Outgoing['init7sso']);
		$construct->SetInt24(2);
		$construct->SetInt24(0);
		$construct->SetInt24($user->activity_points);
		$construct->SetInt24(105);
		$construct->SetInt24(0);
		Core::send($user->socket, $construct->get());
		unset($construct);
		
		$construct = New Constructor;
		$construct->SetHeader($Outgoing['initCredits']);
		$construct->SetStr($user->credits .".0",true);
		Core::send($user->socket, $construct->get());
		unset($construct);
		
		$construct = New Constructor;
		$construct->SetHeader($Outgoing['initMsg']);
		$construct->SetStr("Bienvenue sur Bloon ! Le serveur est encore en développement.\nBon jeu à tous !\n\n - Burak.",true);
		Core::send($user->socket, $construct->get());
		unset($construct);
		
		$friend = DB::mquery("SELECT u.id,u.username,u.look,u.online,u.motto FROM messenger_friendships m, users u WHERE m.user_one_id = ".$user->userid ." AND u.id = m.user_two_id ORDER BY -online;");
		
		$construct = New Constructor;
		$construct->SetHeader($Outgoing['loadFriend']);
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
		
		$request = DB::mquery("SELECT u.id,u.username,u.look,u.online,u.motto FROM messenger_requests m, users u WHERE m.to_id = ".$user->userid ." AND u.id = m.from_id;");
		$construct = New Constructor;
		$construct->SetHeader($Outgoing['loadFriendRequest']);
		$construct->SetInt24(count($request));
		$construct->SetInt24(count($request));
		foreach($request as $rdata){
			$construct->SetInt24($rdata->id);
			$construct->SetStr($rdata->username,true);
			$construct->SetStr($rdata->look,true);
		}
		Core::send($user->socket, $construct->get());
		unset($construct,$request,$rdata);
	}
}
?>