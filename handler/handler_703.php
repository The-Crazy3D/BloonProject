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
}
// if($x != $user->pos_x && $y != $user->pos_y){
	$map=Core::GetMap();
	$path=new PathFinder();
	$path->setOrigin($user->pos_x,$user->pos_y);
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
			$mvx = $xf;
			$mvy = $yf;
			if($xc > $xf && $yc > $yf){
				$mvx--;
				$mvy--;
			}else if($xc < $xf && $yc < $yf){
				$mvx++;
				$mvy++;
			}else if($xc > $xf && $yc < $yf){
				$mvx--;
				$mvy++;
			}else if($xc < $xf && $yc > $yf){
				$mvx++;
				$mvy--;
			}else if($xc > $xf){
				$mvx--;
			}else if($xc < $xf){
				$mvx++;
			}else if($yc < $yf){
				$mvy++;
			}else if($yc < $yf){
				$mvy--;
			}
			// $addin = "mv ".$mvx.",".$mvy.",0//";
			$rotate = Core::RotationCalculate(array($xc,$yc),array($xf,$yf));
		}else{
			$addin = "";
			$rotate = Core::RotationCalculate(array($user->pos_x,$user->pos_y),array($xc,$yc));	
		}
		$construct->SetInt24(1);
		$construct->SetInt24(0);
		$construct->SetInt24($xc);
		$construct->SetInt24($yc);
		$construct->SetInt8(1);
		$construct->SetStr(chr(0x30));
		$construct->SetInt24($rotate);
		$construct->SetInt24($rotate);
		$construct->SetStr("/flatcrtl 4 useradmin/".$addin,true);
		usleep(500000);
		Core::SendToRoom($user->room_id, $construct->get());
		$user->pos_x = $xc;
		$user->pos_y = $yc;
		unset($xf,$yf,$addin);
	}
	unset($construct,$map,$path,$result,$coord);
// }
?>