<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
$key = Core::GetNextString($data);
$key = $key[0];
$key = str_replace("owner:", "", $key);
$roomlist = DB::mquery("SELECT u.username,u.id as userid,r.id,r.caption,r.state,r.users_max,r.users_now FROM rooms r,users u WHERE u.username = r.owner AND r.owner = '".$key."' OR r.caption LIKE '".$key."%' ORDER BY owner");
if(!$roomlist){
	$construct = New Constructor;
	$construct->SetHeader(Packet::GetHeader('NavigatorPacket'));
	$construct->SetInt24(5);
	$construct->SetInt24(0);
	$construct->SetInt24(0);
	$construct->SetInt24(0);
	$construct->SetInt8(0);
	$construct->SetStr(chr(0));
	Core::send($user->socket, $construct->get());
}else{
	$construct = New Constructor;
	$construct->SetHeader(Packet::GetHeader('NavigatorPacket'));
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
	Core::send($user->socket, $construct->get());
}
unset($roomlist,$rlist);
?>