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
Core::say("X : ".$x.", Y : ".$y);
if($user->pos_x == 0){
	$user->pos_x = 1;
}else if($user->pos_y == 0){
	$user->pos_y = 1;
}else if($x == 0){
	$x = 1;
}else if($y == 0){
	$y == 1;
}
// if($x != $user->pos_x && $y != $user->pos_y){
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

	foreach($result as $coordkey => $coord){
		$split = explode("x", $coord);
		$xc = $split[0];
		$yc = $split[1];
		if(isset($result[($coordkey+1)])){
			$split = explode("x", $result[($coordkey+1)]);
			$xf = $split[0];
			$yf = $split[1];
		}
		$construct = New Constructor;
		$construct->SetHeader(Packet::GetHeader('pathfinding'));
		if(isset($xf) && isset($yf)){
			$addin = "mv ".$xf.",".$yf.",0//";

			$rotate = Core::RotationCalculate(array($xc,$yc),array($xf,$yf));
		}else{
			$addin = "";
			$rotate = Core::RotationCalculate(array($user->pos_x,$user->pos_y),array($xc,$yc));	
		}
		$construct->SetInt24(1);
		$construct->SetInt24($user->userid);
		$construct->SetInt24($xc);
		$construct->SetInt24($yc);
		$construct->SetInt8(1);
		$construct->SetStr(chr(0x30));
		$construct->SetInt24($rotate);
		$construct->SetInt24($rotate);
		$construct->SetStr("/flatcrtl 4 useradmin/".$addin,true);
		usleep(500000);
		Core::SendToAllRoom($user->room_id, $construct->get());
		$user->pos_x = $xc;
		$user->pos_y = $yc;
		unset($xf,$yf,$addin);
	}
	unset($construct,$map,$path,$result,$coord);
// }
?>