<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
Class Packet{
	public static function GetHeader($name){
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
		$Outgoing['loadPublicRoom'] = 3700;
		$Outgoing['loadRoomInfo1'] = 3798;
		$Outgoing['loadRoomInfo2'] = 999;
		$Outgoing['loadRoomInfo3'] = 3663;
		$Outgoing['loadRoomInfo4'] = 1496;
		$Outgoing['loadRoomInfo5'] = 213;
		$Outgoing['loadRoomInfo6'] = 3401;
		$Outgoing['loadRoomModel1'] = 9;
		$Outgoing['loadRoomModel2'] = 2483;
		$Outgoing['loadRoom1'] = 3580;
		$Outgoing['loadRoom2'] = 3096;
		$Outgoing['loadRoomUser'] = 2204;
		$Outgoing['loadRoom3'] = 939;
		$Outgoing['loadRoom4'] = 15361;
		$Outgoing['pathfinding'] = 493;
		if(isset($Outgoing[$name])){
			return $Outgoing[$name];
		}else{
			return false;
		}
	}
}
?>