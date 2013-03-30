<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
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

$packet = Core::GetHeader($packet);
$header = $packet[0];
$data = $packet[2];
// Core::say("[".$header."] ".$data,1);
$filepath = ("handler/handler_".$header.".php");
if(file_exists($filepath)){
	@include($filepath);
}
unset($packet,$header,$construct,$data,$crossdomain,$ticket,$userdata,$filepath);
?>
