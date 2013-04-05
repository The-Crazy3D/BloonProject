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
		$Outgoing['SendBannerMessageComposer'] = 3500;
		$Outgoing['SecretKeyComposer'] = 659;
		$Outgoing['UniqueID'] = 1130;
		$Outgoing['AuthenticationOK'] = 1065;
		$Outgoing['FavouriteRooms'] = 2429;
		$Outgoing['Fuserights'] = 978;
		$Outgoing['bools1'] = 1060;
		$Outgoing['HomeRoom'] = 1994;
		$Outgoing['ActivityPoints'] = 2542;
		$Outgoing['CreditsBalance'] = 2995;
		$Outgoing['BroadcastMessage'] = 2491;
		$Outgoing['HabboInfomation'] = 2228;
		$Outgoing['AchievementPoints'] = 1614;
		$Outgoing['InitFriends'] = 398;
		$Outgoing['InitRequests'] = 1671;
		$Outgoing['ProfileInformation'] = 2776;
		$Outgoing['NavigatorPacket'] = 2160;
		$Outgoing['PublicCategories'] = 3700;
		$Outgoing['PrepareRoomForUsers'] = 3798;
		$Outgoing['InitialRoomInformation'] = 999;
		$Outgoing['RoomDecoration'] = 3663;
		$Outgoing['RoomRightsLevel'] = 1496;
		$Outgoing['HasOwnerRights'] = 213;
		$Outgoing['RateRoom'] = 3401;
		$Outgoing['HeightMap'] = 9;
		$Outgoing['RelativeMap'] = 2483;
		$Outgoing['SerializeFloorItems'] = 3580;
		$Outgoing['SerializeWallItems'] = 3096;
		$Outgoing['SetRoomUser'] = 2204;
		$Outgoing['ConfigureWallandFloor'] = 939;
		$Outgoing['UpdateState'] = 493;
		$Outgoing['Shout'] = 3165;
		$Outgoing['Talk'] = 2119;
		$Outgoing['Whisp'] = 701;
		$Outgoing['CanCreateRoom'] = 3859;
		$Outgoing['OnCreateRoomInfo'] = 290;
		$Outgoing['Dance'] = 3301;
		$Outgoing['UserLeftRoom'] = 3770;
		$Outgoing['Action'] = 523;
		$Outgoing['TypingStatus'] = 851;
		$Outgoing['SendNotif'] = 3192;
		$Outgoing['UpdateUserInformation'] = 421;
		$Outgoing['ShopData2'] = 2889;
		$Outgoing['Inventory'] = 177;
		if(isset($Outgoing[$name])){
			return $Outgoing[$name];
		}else{
			Console::WriteLine($name." header not found in class.packet.php !");
			return false;
		}
	}
}
?>