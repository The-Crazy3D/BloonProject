<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
// $data = str_replace("Navigation".chr(0).chr(0), "Navigation", $data);
$split = Core::GetNextString($data);
$action = $split[0];
$data = $split[1];
switch($action){
	case "Navigation":
		$data = str_replace(chr(0).chr(6)."Search", "", $data);
		Core::say("Final data : ".$data);
		if(preg_match("/go.me/i", $data) OR preg_match("/go.rooms/i", $data)){
			$data = substr($data, 2);
		}
		$split = Core::GetNextString($data);
		$go = $split[0];
		// Core::say("lol ! ".$go);
		$data = $split[1];
		switch($go){
			case "go.me":
			case "go.search":
			case "go.rooms":
				$split = Core::GetNextString($data);
				$roomid = $split[0];
				Core::say("ROOM ID : ".$roomid);
				$data = $split[1];
				if(is_numeric($user->room_id)){
					DB::exec("UPDATE rooms SET users_now = users_now-1 WHERE id = '".$user->room_id."'");
					unset($user->room_id);
				}
				$user->room_id = $roomid;
				$roominfo = DB::query("SELECT * FROM rooms WHERE id = '".$roomid."'");
				$model = Core::GetModel($roominfo->model_name);
				$heightmap = str_replace(chr(10), "", $model->heightmap .chr(0x0D));
				$construct = New Constructor;
				$construct->SetHeader(Packet::GetHeader('loadRoomModel1'));
				$construct->SetStr($heightmap,true);
				Core::send($user->socket, $construct->get());
				unset($construct);
				
				$heightmap_step1 = explode(chr(0x0D), $heightmap);
				$heightmap_door = "";
				foreach($heightmap_step1 as $heightmapkey => $heightmapdata){
					if($heightmapkey != $model->door_y){
						$heightmap_door .= $heightmapdata.chr(0x0D);
					}else{
						$heightmap_split = str_split($heightmapdata);
						foreach($heightmap_split as $keysplit => $splitdata){
							if($keysplit != $model->door_x){
								$heightmap_door.= $splitdata;
							}else{
								$heightmap_door.= "0";
							}
						}
						$heightmap_door = $heightmap_door.chr(0x0D);
					}
				}
				$construct = New Constructor;
				$construct->SetHeader(Packet::GetHeader('loadRoomModel2'));
				$construct->SetStr($heightmap_door,true);
				Core::send($user->socket, $construct->get());
				unset($construct);
				
				$construct = New Constructor;
				$construct->SetHeader(Packet::GetHeader('loadRoom1'));
				$construct->SetInt24(0);
				$construct->SetInt24(0);
				Core::send($user->socket, $construct->get());
				unset($construct);
				
				$construct = New Constructor;
				$construct->SetHeader(Packet::GetHeader('loadRoom2'));
				$construct->SetInt24(0);
				$construct->SetInt24(0);
				Core::send($user->socket, $construct->get());
				unset($construct);
				$user->pos_x = $model->door_x;
				$user->pos_y = $model->door_y;
				$user->rotate = $model->door_dir;
				$userlist = Core::GetUserByRoom($roomid);
				// print_r($userlist);
				$construct = New Constructor;
				$construct->SetHeader(Packet::GetHeader('loadRoomUser'));
				$construct->SetInt24(count($userlist));
				foreach($userlist as $roomuser){
					$construct->SetInt24($roomuser->userid);
					$construct->SetStr($roomuser->username,true);
					$construct->SetStr($roomuser->motto,true);
					$construct->SetStr($roomuser->look,true);
					$construct->SetInt24($roomuser->userid);
					$construct->SetInt24($roomuser->pos_x);
					$construct->SetInt24($roomuser->pos_y);
					$construct->SetInt8(1);
					$construct->SetStr(chr(0x30));
					$construct->SetInt24(2);
					$construct->SetInt24(1);
					$construct->SetStr(strtolower($roomuser->gender),true);
					$construct->SetStr(chr(0xFF).chr(0xFF).chr(0xFF).chr(0xFF).chr(0xFF).chr(0xFF).chr(0xFF).chr(0xFF));
					$construct->SetInt24(0);
					$construct->SetInt24(15);
				}
				
				Core::send($user->socket, $construct->get());
				unset($construct);
				
				$construct = New Constructor;
				$construct->SetHeader(Packet::GetHeader('loadRoomUser'));
				$construct->SetInt24(1);
				$construct->SetInt24($user->userid);
				$construct->SetStr($user->username,true);
				$construct->SetStr($user->motto,true);
				$construct->SetStr($user->look,true);
				$construct->SetInt24($user->userid);
				$construct->SetInt24($model->door_x);
				$construct->SetInt24($model->door_y);
				$construct->SetInt8(1);
				$construct->SetStr(chr(0x30));
				$construct->SetInt24(2);
				$construct->SetInt24(1);
				$construct->SetStr(strtolower($user->gender),true);
				$construct->SetStr(chr(0xFF).chr(0xFF).chr(0xFF).chr(0xFF).chr(0xFF).chr(0xFF).chr(0xFF).chr(0xFF));
				$construct->SetInt24(0);
				$construct->SetInt24(0);
				
				Core::SendToRoom($user->room_id, $construct->get(),$user->userid);
				unset($construct);
				
				$construct = New Constructor;
				$construct->SetHeader(Packet::GetHeader('loadRoom3'));
				$construct->SetInt24(0);
				$construct->SetInt24(0);
				$construct->SetInt8(0);
				Core::send($user->socket, $construct->get());
				Core::send($user->socket, $construct->get());
				unset($construct);
				
				/*$construct = New Constructor;
				$construct->SetHeader(Packet::GetHeader('loadRoom4'));
				$construct->SetInt24($user->userid);*/
				$construct = New Constructor;
				$construct->SetHeader(Packet::GetHeader('pathfinding'));
				$construct->SetInt24(1);
				$construct->SetInt24(0);
				$construct->SetInt24($model->door_x);
				$construct->SetInt24($model->door_y);
				$construct->SetInt8(1);
				$construct->SetStr(chr(0x30));
				$construct->SetInt24(2);
				$construct->SetInt24(2);
				$construct->SetStr("/flatcrtl 4 useradmin/",true);
				Core::send($user->socket, $construct->get());
				unset($construct);
				DB::exec("UPDATE rooms SET users_now = users_now+1 WHERE id = '".$user->room_id."'");
			break;
			Default:
				// Console::WriteLine("Undefined go : ".$go);
			break;
		}
	break;
	Default:
		// Console::WriteLine("Undefined action : ".$action);
	break;
}
unset($split,$roomid,$roominfo,$heightmap);
?>