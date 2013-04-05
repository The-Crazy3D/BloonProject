<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
$split = Core::GetNextString($data);
$action = $split[0];
$data = $split[1];
switch($action){
	case "Navigation":
		$data = str_replace(chr(0).chr(6)."Search", "", $data);
		if(preg_match("/go.me/i", $data) OR preg_match("/go.rooms/i", $data)){
			$data = substr($data, 2);
		}
		$split = Core::GetNextString($data);
		$go = $split[0];
		$data = $split[1];
		switch($go){
			case "go.me":
			case "go.search":
			case "go.rooms":
				$split = Core::GetNextString($data);
				$roomid = $split[0];
				$data = $split[1];
				if(is_numeric($user->room_id)){
					DB::exec("UPDATE rooms SET users_now = users_now-1 WHERE id = '".$user->room_id."'");
					unset($user->room_id);
				}
				$user->room_id = $roomid;
				if(Config::Get("emu.messages.roommgr")){
					Core::say("Loaded room ".$roomid,1);
				}
				$roominfo = DB::query("SELECT * FROM rooms WHERE id = '".$roomid."'");
				$model = Core::GetModel($roominfo->model_name);
				$heightmap = str_replace(chr(10), "", $model->heightmap .chr(0x0D));
				$construct = New Constructor;
				$construct->SetHeader(Packet::GetHeader('HeightMap'));
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
								$heightmap_door.= $model->door_z;
							}
						}
						$heightmap_door = $heightmap_door.chr(0x0D);
					}
				}
				$construct = New Constructor;
				$construct->SetHeader(Packet::GetHeader('RelativeMap'));
				$construct->SetStr($heightmap_door,true);
				Core::send($user->socket, $construct->get());
				unset($construct);
				
				$construct = New Constructor;
				$construct->SetHeader(Packet::GetHeader('SerializeFloorItems'));
				$construct->SetInt24(0);
				$construct->SetInt24(0);
				Core::send($user->socket, $construct->get());
				unset($construct);
				
				$construct = New Constructor;
				$construct->SetHeader(Packet::GetHeader('SerializeWallItems'));
				$construct->SetInt24(0);
				$construct->SetInt24(0);
				Core::send($user->socket, $construct->get());
				unset($construct);
				$user->pos_x = $model->door_x;
				$user->pos_y = $model->door_y;
				$user->pos_z = $model->door_z;
				$user->heightmap = $model->heightmap;
				$user->rotate = $model->door_dir;
				$userlist = Core::GetUserByRoom($roomid);
				$construct = New Constructor;
				$construct->SetHeader(Packet::GetHeader('SetRoomUser'));
				$construct->SetInt24(count($userlist));
				foreach($userlist as $roomuser){
					$construct->SetInt24($roomuser->userid);
					$construct->SetStr($roomuser->username,true);
					$construct->SetStr($roomuser->motto,true);
					$construct->SetStr($roomuser->look,true);
					$construct->SetInt24($roomuser->userid);
					$construct->SetInt24($roomuser->pos_x);
					$construct->SetInt24($roomuser->pos_y);
					$construct->SetStr($roomuser->pos_z,true);
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
				$construct->SetHeader(Packet::GetHeader('SetRoomUser'));
				$construct->SetInt24(1);
				$construct->SetInt24($user->userid);
				$construct->SetStr($user->username,true);
				$construct->SetStr($user->motto,true);
				$construct->SetStr($user->look,true);
				$construct->SetInt24($user->userid);
				$construct->SetInt24($model->door_x);
				$construct->SetInt24($model->door_y);
				$construct->SetStr($model->door_z,true);
				$construct->SetInt24(2);
				$construct->SetInt24(1);
				$construct->SetStr(strtolower($user->gender),true);
				$construct->SetStr(chr(0xFF).chr(0xFF).chr(0xFF).chr(0xFF).chr(0xFF).chr(0xFF).chr(0xFF).chr(0xFF));
				$construct->SetInt24(0);
				$construct->SetInt24(0);
				
				Core::SendToRoom($user->room_id, $construct->get(),$user->userid);
				unset($construct);
				
				$construct = New Constructor;
				$construct->SetHeader(Packet::GetHeader('ConfigureWallandFloor'));
				$construct->SetInt24(0);
				$construct->SetInt24(0);
				$construct->SetInt8(0);
				Core::send($user->socket, $construct->get());
				Core::send($user->socket, $construct->get());
				unset($construct);
				
				$construct = New Constructor;
				$construct->SetHeader(Packet::GetHeader('UpdateState'));
				$construct->SetInt24(1);
				$construct->SetInt24($user->userid);
				$construct->SetInt24($model->door_x);
				$construct->SetInt24($model->door_y);
				$construct->SetStr($model->door_z,true);
				$construct->SetInt24(2);
				$construct->SetInt24(2);
				$construct->SetStr("/flatcrtl 4 useradmin/",true);
				Core::SendToAllRoom($user->room_id, $construct->get());
				unset($construct);
				DB::exec("UPDATE rooms SET users_now = users_now+1 WHERE id = '".$user->room_id."'");
			break;
		}
	break;
}
unset($split,$roomid,$roominfo,$heightmap);
?>