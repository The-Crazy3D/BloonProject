<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
$x = HabboEncoding::DecodeBit24($data);
$data = substr($data, 4);
$y = HabboEncoding::DecodeBit24($data);
$tilefurni = Core::GetTileFurni($x, $y, $user->room_id);
if(isset($tilefurni)){
	foreach($tilefurni as $tilefurnitem){
		if($tilefurnitem->can_sit == "1"){
			$height = $tilefurnitem->stack_height;
			$finaladdin = "sit ".$height."//";
			$finalrot = $tilefurnitem->rot;
		}
	}
}
foreach($sockethand[$user->userid] as $keyp => $threadp){
	if($threadp->isRunning()){
		$user->pos_x = $threadp->xa;
		$user->pos_y = $threadp->ya;
		if($user->pos_x > $x && $user->pos_y > $y){
			$user->pos_x--;
			$user->pos_y--;
		}else if($user->pos_x < $x && $user->pos_y < $y){
			$user->pos_x++;
			$user->pos_y++;
		}else if($user->pos_x > $x && $user->pos_y < $y){
			$user->pos_x--;
			$user->pos_y++;
		}else if($user->pos_x < $x && $user->pos_y > $y){
			$user->pos_x++;
			$user->pos_y--;
		}else if($user->pos_x > $x){
			$user->pos_x--;
		}else if($user->pos_x < $x){
			$user->pos_x++;
		}else if($user->pos_y < $y){
			$user->pos_y++;
		}else if($user->pos_y > $y){
			$user->pos_y--;
		}
		usleep(250000);
		$threadp->stop();
		unset($sockethand[$user->userid][$keyp]);
	}
}
if($user->pos_x == 0){
	$user->pos_x = 1;
}else if($user->pos_y == 0){
	$user->pos_y = 1;
}else if($x == 0){
	$x = 1;
}else if($y == 0){
	$y == 1;
}else if($user->pos_x == 1 && $user->pos_y == 1){
	$user->pos_x++;
	$user->pos_y++;
}
if(!isset($user->teleport)){
if($x != $user->pos_x || $y != $user->pos_y){
	$map=Core::GetMap();
	$path=new PathFinder();
	$origx = $user->pos_x;
	$origy = $user->pos_y;
	if($user->pos_x > $x && $user->pos_y > $y){
		$origx++;
		$origy++;
	}else if($user->pos_x < $x && $user->pos_y < $y){
		$origx--;
		$origy--;
	}else if($user->pos_x > $x && $user->pos_y < $y){
		$origx++;
		$origy--;
	}else if($user->pos_x < $x && $user->pos_y > $y){
		$origx--;
		$origy++;
	}else if($user->pos_x > $x){
		$origx++;
	}else if($user->pos_x < $x){
		$origx--;
	}else if($user->pos_y < $y){
		$origy--;
	}else if($user->pos_y > $y){
		$origy++;
	}
	if($origx == 0){
		$origx = 1;
	}else if($origy == 0){
		$origy = 1;
	}
	$path->setOrigin($origx,$origy);
	$path->setDestination($x,$y);
	$path->setMap($map);
	$result=$path->returnPath();
	$packetarray = array();
	$xthread = array();
	$ythread = array();
	$xfthread = array();
	$yfthread = array();
	foreach($result as $coordkey => $coord){
		$split = explode("x", $coord);
		$xc = $split[0];
		$yc = $split[1];
		if(isset($result[($coordkey+1)])){
			$split = explode("x", $result[($coordkey+1)]);
			$xf = $split[0];
			$yf = $split[1];
			$zf = Core::GetTileData($xf, $yf, $user->heightmap);
		}
		$user->pos_z = Core::GetTileData($xc, $yc, $user->heightmap);
		$construct = New Constructor;
		$construct->SetHeader(Packet::GetHeader('UpdateState'));
		if(isset($xf) && isset($yf)){
			$addin = "mv ".$xf.",".$yf.",".$zf."//";
			$xfthread[] = $xf;
			$yfthread[] = $yf;
			$rotate = Core::RotationCalculate(array($xc,$yc),array($xf,$yf));
		}else{
			$addin = "";
			$rotate = Core::RotationCalculate(array($user->pos_x,$user->pos_y),array($xc,$yc));	
			if(isset($finaladdin)){
				$addin = $finaladdin;
				$rotate = $finalrot;
			}
			$xfthread[] = $xc;
			$yfthread[] = $yc;
		}
		$construct->SetInt24(1);
		$construct->SetInt24($user->userid);
		$construct->SetInt24($xc);
		$construct->SetInt24($yc);
		$construct->SetStr($user->pos_z,true);
		$construct->SetInt24($rotate);
		$construct->SetInt24($rotate);
		$construct->SetStr("/flatcrtl 4 useradmin/".$addin,true);
		$packetarray[] = $construct->get();
		$xthread[] = $xc;
		$ythread[] = $yc;
		$user->pos_x = $xc;
		$user->pos_y = $yc;
		$user->rotate = $rotate;
		unset($xf,$yf,$addin);
	}
		$userlist = Core::GetUserByRoom($user->room_id);
		$socketarray = array();
		foreach($userlist as $userroom){
			$socketarray[] = $userroom->socket;
		}
			$tid = count($sockethand);
			$sockethand[$user->userid][$tid] = New SocketSender;
			$sockethand[$user->userid][$tid]->SetData($packetarray,$socketarray,$xfthread,$yfthread);
			$sockethand[$user->userid][$tid]->start();
	unset($construct,$map,$path,$result,$coord,$userlist,$userroom);
}
}else if($user->teleport){
		$construct = New Constructor;
		$construct->SetHeader(Packet::GetHeader('UpdateState'));
		$construct->SetInt24(1);
		$construct->SetInt24($user->userid);
		$construct->SetInt24($x);
		$construct->SetInt24($y);
		$user->pos_z = Core::GetTileData($x, $y, $user->heightmap);
		$construct->SetStr($user->pos_z,true);
		$construct->SetInt24($user->rotate);
		$construct->SetInt24($user->rotate);
		$construct->SetStr("/flatcrtl 4 useradmin/".$addin,true);
		$user->pos_x = $x;
		$user->pos_y = $y;
		Core::SendToAllRoom($user->room_id, $construct->get());
}
unset($finaladdin);
?>