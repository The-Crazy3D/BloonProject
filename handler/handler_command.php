<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
$smessage = str_split($message);
if($smessage[0] == ":"){
	$split = explode(" ", $message);
	print_r($split);
	Switch($split[0]){
		case ":ha":
		case ":hotelalert":
			// if($user->rank > 5){
				$message = str_replace(":ha ", "", $message);
				$message = str_replace(":ha", "", $message);
				$message = str_replace(":hotelalert ", "", $message);
				$message = str_replace(":hotelalert", "", $message);
				$construct = New Constructor;
				$construct->SetHeader(Packet::GetHeader('initMsg'));
				$construct->SetStr($message."\n\n- ".$user->username,true);
				Core::SendToAll($construct->get());
			// }
			$send = false;
		break;
		Default:
			$send = true;
		break;
	}
}else{
	$send = true;
}
unset($smessage,$split);
?>