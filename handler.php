<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonCrypto
 */
// 1823
$Outgoing = Array();
$Outgoing['init1'] = 3500;
$Outgoing['init2'] = 659;
$Outgoing['init1sso'] = 1130;
$Outgoing['init2sso'] = 1065;
$Outgoing['init3sso'] = 2429;
$Outgoing['init4sso'] = 978;
$Outgoing['init5sso'] = 1060;
$Outgoing['init6sso'] = 1994;
$Outgoing['init7sso'] = 2542;
$Outgoing['initCredits'] = 2995;
$Outgoing['initMsg'] = 2491;
$Outgoing['initUser'] = 2228;
$Outgoing['init8sso'] = 1614;
$Outgoing['loadFriend'] = 398;
$Outgoing['loadFriendRequest'] = 1671;
$Outgoing['loadProfil'] = 2776;
$Outgoing['loadUserRoomList'] = 2160;

$packet = $core->GetHeader($packet);
$header = $packet[0];
$data = $packet[2];
// $core->say("[".$header."] ".$data,1);
switch($header) {
	case 26979:
		$crossdomain = '<?xml version="1.0"?>
<!DOCTYPE cross-domain-policy SYSTEM "/xml/dtds/cross-domain-policy.dtd">
<cross-domain-policy>
<allow-access-from domain="*" to-ports="1-31111" />
</cross-domain-policy>'.chr(0);
		$core->send($user->socket, $crossdomain);
	break;
	case 2996:
		$construct = New Constructor;
		$construct->SetHeader($Outgoing['init1']);
		$construct->SetStr("12f449917de4f94a8c48dbadd92b6276",true);
		$construct->SetStr(chr(0));
		$core->send($user->socket, $construct->get());
	break;
	case 840:
		$construct = New Constructor;
		$construct->SetHeader($Outgoing['init2']);
		$construct->SetStr("M24231219992253632572058933470468103090824667747608911151318774416044820318109",true);
		$core->send($user->socket, $construct->get());
	break;
	case 442:
		$ticket = $core->GetNextString($data);
		$data = $ticket[1];
		$ticket = $ticket[0];
		$userdata = $DB->query("SELECT * FROM users WHERE auth_ticket = '".addslashes($ticket)."' LIMIT 1");
		if(!$userdata){
			$core->disconnect($user->socket);
		}else{
			if($user->ip != $userdata->ip_last){
				$core->disconnect($user->socket);
			}else{
				$DB->exec("UPDATE users SET online = '1' AND last_online = '".time()."' WHERE id = '".($userdata->id)."'");
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
				$core->Say($user->username ." logged in !",1);
				$construct = New Constructor;
				$construct->SetHeader($Outgoing['init1sso']);
				$construct->SetInt8(0);
				$core->send($user->socket, $construct->get());
				unset($construct);
				
				$construct = New Constructor;
				$construct->SetHeader($Outgoing['init2sso']);
				$core->send($user->socket, $construct->get());
				unset($construct);
				
				$construct = New Constructor;
				$construct->SetHeader($Outgoing['init3sso']);
				$construct->SetInt24($user->userid);
				$construct->SetInt24(0);
				$core->send($user->socket, $construct->get());
				unset($construct);
				
				$construct = New Constructor;
				$construct->SetHeader($Outgoing['init4sso']);
				$construct->SetInt24(2);
				$construct->SetInt24(2);
				$core->send($user->socket, $construct->get());
				unset($construct);
				
				$construct = New Constructor;
				$construct->SetHeader($Outgoing['init5sso']);
				$construct->SetInt8(256);
				$core->send($user->socket, $construct->get());
				unset($construct);
				
				$construct = New Constructor;
				$construct->SetHeader($Outgoing['init6sso']);
				$construct->SetInt24(636);
				$construct->SetInt24(0);
				$core->send($user->socket, $construct->get());
				unset($construct);
				
				$construct = New Constructor;
				$construct->SetHeader($Outgoing['init7sso']);
				$construct->SetInt24(2);
				$construct->SetInt24(0);
				$construct->SetInt24($user->activity_points);
				$construct->SetInt24(105);
				$construct->SetInt24(0);
				$core->send($user->socket, $construct->get());
				unset($construct);
				
				$construct = New Constructor;
				$construct->SetHeader($Outgoing['initCredits']);
				$construct->SetStr($user->credits .".0",true);
				$core->send($user->socket, $construct->get());
				unset($construct);
				
				$construct = New Constructor;
				$construct->SetHeader($Outgoing['initMsg']);
				$construct->SetStr("Bienvenue sur Bloon ! Le serveur est encore en développement.\nBon jeu à tous !\n\n - Burak.",true);
				$core->send($user->socket, $construct->get());
				unset($construct);
				
				$friend = $DB->mquery("SELECT u.id,u.username,u.look,u.online,u.motto FROM messenger_friendships m, users u WHERE m.user_one_id = ".$user->userid ." AND u.id = m.user_two_id ORDER BY -online;");
				
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
				$core->send($user->socket, $construct->get());
				unset($construct,$friend,$fdata);
				
				$request = $DB->mquery("SELECT u.id,u.username,u.look,u.online,u.motto FROM messenger_requests m, users u WHERE m.to_id = ".$user->userid ." AND u.id = m.from_id;");
				$construct = New Constructor;
				$construct->SetHeader($Outgoing['loadFriendRequest']);
				$construct->SetInt24(count($request));
				$construct->SetInt24(count($request));
				foreach($request as $rdata){
					$construct->SetInt24($rdata->id);
					$construct->SetStr($rdata->username,true);
					$construct->SetStr($rdata->look,true);
				}
				$core->send($user->socket, $construct->get());
				unset($construct,$request,$rdata);
			}
		}
	break;
	case 3183:
		$construct = New Constructor;
		$construct->SetHeader($Outgoing['initUser']);
		$construct->SetInt24($user->userid);
		$construct->SetStr($user->username,true);
		$construct->SetStr($user->look,true);
		$construct->SetStr($user->gender,true);
		$construct->SetStr($user->motto,true);
		$construct->SetInt24(0);
		$construct->SetInt24(0);
		$construct->SetInt24(768);
		$construct->SetInt24(769);
		$construct->SetStr("28/03/2013 11:50:05", true);
		$construct->SetInt8(0);
		$core->send($user->socket, $construct->get());
		unset($construct);
		
		$construct = New Constructor;
		$construct->SetHeader($Outgoing['init8sso']);
		$construct->SetInt24(15);
		$core->send($user->socket, $construct->get());
		unset($construct);
	break;
	case 1611:
		$construct = New Constructor;
		$construct->SetHeader($Outgoing['loadProfil']);
		$id = HabboEncoding::DecodeBit24($data);
		$profile = $DB->query("SELECT CONCAT((SELECT COUNT(*) as nb FROM messenger_friendships WHERE user_two_id = ".$id.")) as friend_count,u.last_online,u.username,u.look,u.motto,a.AchievementScore as score FROM users u, user_stats a WHERE u.id = ".$id." AND a.id = ".$id);
		$construct->SetInt24($id);
		$construct->SetStr($profile->username,true);
		$construct->SetStr($profile->look,true);
		$construct->SetStr($profile->motto,true);
		$construct->SetStr("12/12/12",true);
		$construct->SetInt24($profile->score);
		$construct->SetInt24($profile->friend_count);
		$construct->SetInt24(256);
		$construct->SetInt24(0);
		$construct->SetInt24(time()-$profile->last_online);
		$core->send($user->socket, $construct->get());
		unset($id,$profile);
	break;
	case 1930:
		$core->send($user->socket, $core->HexaString("00 00 02 06 0E 74 00 00 00 01 00 00 00 04 00 00 00 00 00 00 00 00 00 1A 3A 3A 20 41 63 63 75 65 69 6C 20 64 65 20 4E 6F 76 61 53 70 61 63 65 20 3A 3A 00 22 6F 66 66 69 63 69 61 6C 72 6F 6F 6D 73 5F 68 71 2F 77 65 6C 63 6F 6D 65 5F 6C 6F 62 62 79 2E 70 6E 67 00 00 00 00 00 00 00 00 00 00 00 02 00 00 02 7C 00 1A 3A 3A 20 41 63 63 75 65 69 6C 20 64 65 20 4E 6F 76 61 53 70 61 63 65 20 3A 3A 01 00 00 00 10 00 04 53 6F 61 70 00 00 00 01 00 00 00 00 00 00 00 19 00 33 55 6E 20 76 72 61 69 20 63 68 C3 A2 74 65 61 75 20 64 65 20 56 65 72 73 61 69 6C 6C 65 73 20 21 20 51 75 65 6C 20 62 65 61 75 20 70 61 6C 61 69 73 20 21 00 00 00 00 00 00 00 00 00 00 00 8A 00 00 00 00 00 00 00 0F 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 01 01 00 00 00 00 00 00 00 00 00 00 00 01 00 00 00 04 00 00 00 00 00 00 00 00 00 1A 3A 3A 20 41 63 63 75 65 69 6C 20 64 65 20 4E 6F 76 61 53 70 61 63 65 20 3A 3A 00 22 6F 66 66 69 63 69 61 6C 72 6F 6F 6D 73 5F 68 71 2F 77 65 6C 63 6F 6D 65 5F 6C 6F 62 62 79 2E 70 6E 67 00 00 00 00 00 00 00 00 00 00 00 02 00 00 02 7C 00 1A 3A 3A 20 41 63 63 75 65 69 6C 20 64 65 20 4E 6F 76 61 53 70 61 63 65 20 3A 3A 01 00 00 00 10 00 04 53 6F 61 70 00 00 00 01 00 00 00 00 00 00 00 19 00 33 55 6E 20 76 72 61 69 20 63 68 C3 A2 74 65 61 75 20 64 65 20 56 65 72 73 61 69 6C 6C 65 73 20 21 20 51 75 65 6C 20 62 65 61 75 20 70 61 6C 61 69 73 20 21 00 00 00 00 00 00 00 00 00 00 00 8A 00 00 00 00 00 00 00 0F 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 01 01 00 00 00 00 00 00 00 00 00 00 00 00"));
	break;
	case 3350:
		$roomlist = $DB->mquery("SELECT * FROM rooms WHERE owner = '".$user->username ."'");
		if(!$roomlist){
			// Aucun appart
		}else{
			$construct = New Constructor;
			$construct->SetHeader($Outgoing['loadUserRoomList']);
			$construct->SetInt24(5);
			$construct->SetInt8(0);
			$construct->SetInt24(count($roomlist));
			$i = 0;
			foreach($roomlist as $rlist){
				$construct->SetInt24($rlist->id);
				$construct->SetStr($rlist->caption,true);
				$construct->SetStr(chr(1));
				$construct->SetInt24($user->userid);
				$construct->SetStr($user->username,true);
				switch($rlist->state){
					case "open":
					Default:
						$construct->SetInt24(0);
					break;
					case "locked":
						$construct->SetInt24(1);
					break;
					case "password":
						$construct->SetInt24(2);
					break;
				}
				$construct->SetInt24($rlist->users_now);
				
				$construct->SetInt24($rlist->users_max);
				$construct->SetInt24(0);
				$construct->SetInt24(0);
				$construct->SetInt24(0);
				
				$construct->SetInt24(0);
				$construct->SetInt24(0);
				$construct->SetInt24(0);
				$construct->SetInt24(0);
				
				$construct->SetInt24(0);
				$construct->SetInt24(0);
				$construct->SetInt24(0);
				$construct->SetInt24(0);
				
				$construct->SetInt24(0);
				$construct->SetInt8(257);
				$construct->SetInt24(0);
				$construct->SetInt24(0);
				$i++;
			}
			$construct->SetInt24(0);
			$construct->SetInt24(0);
			$core->send($user->socket, $construct->get());
		}
		unset($roomlist,$rlist);
	break;
	case 3891:
		$key = $core->GetNextString($data);
		$key = $key[0];
		$key = str_replace("owner:", "", $key);
		$roomlist = $DB->mquery("SELECT u.username,u.id as userid,r.id,r.caption,r.state,r.users_max,r.users_now FROM rooms r,users u WHERE u.username = r.owner AND r.owner LIKE '".$key."%' OR r.caption LIKE '".$key."%' ORDER BY owner");
		if(!$roomlist){
			// Aucun appart
		}else{
			$construct = New Constructor;
			$construct->SetHeader($Outgoing['loadUserRoomList']);
			$construct->SetInt24(5);
			$construct->SetInt8(0);
			$construct->SetInt24(count($roomlist));
			$i = 0;
			foreach($roomlist as $rlist){
				$construct->SetInt24($rlist->id);
				$construct->SetStr($rlist->caption,true);
				$construct->SetStr(chr(1));
				$construct->SetInt24($rlist->userid);
				$construct->SetStr($rlist->username,true);
				switch($rlist->state){
					case "open":
					Default:
						$construct->SetInt24(0);
					break;
					case "locked":
						$construct->SetInt24(1);
					break;
					case "password":
						$construct->SetInt24(2);
					break;
				}
				$construct->SetInt24($rlist->users_now);
				
				$construct->SetInt24($rlist->users_max);
				$construct->SetInt24(0);
				$construct->SetInt24(0);
				$construct->SetInt24(0);
				
				$construct->SetInt24(0);
				$construct->SetInt24(0);
				$construct->SetInt24(0);
				$construct->SetInt24(0);
				
				$construct->SetInt24(0);
				$construct->SetInt24(0);
				$construct->SetInt24(0);
				$construct->SetInt24(0);
				
				$construct->SetInt24(0);
				$construct->SetInt8(257);
				$construct->SetInt24(0);
				$construct->SetInt24(0);
				$i++;
			}
			$construct->SetInt24(0);
			$construct->SetInt24(0);
			$core->send($user->socket, $construct->get());
		}
		unset($roomlist,$rlist);
	break;
	Default:
		// $core->say("[HANDLER NOT FOUND] [".$header."] ".$data,1);
	break;
}
unset($packet,$header,$construct,$data,$crossdomain,$ticket,$userdata);
?>
