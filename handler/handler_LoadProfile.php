<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('ProfileInformation'));
$id = HabboEncoding::DecodeBit24($data);
$profile = DB::query("SELECT CONCAT((SELECT COUNT(*) as nb FROM messenger_friendships WHERE user_two_id = ".$id.")) as friend_count,u.online,u.last_online,u.username,u.look,u.motto,a.AchievementScore as score FROM users u, user_stats a WHERE u.id = ".$id." AND a.id = ".$id);
$construct->SetInt24($id);
$construct->SetStr($profile->username,true);
$construct->SetStr($profile->look,true);
$construct->SetStr($profile->motto,true);
$construct->SetStr("12/12/12",true);
$construct->SetInt24($profile->score);
$construct->SetInt24($profile->friend_count);
$friend = DB::mquery("SELECT * FROM messenger_friendships WHERE user_one_id = '".$user->userid ."' AND user_two_id = '".$id."'");
if(!$friend){
	$construct->SetBoolean(false);
}else{
	$construct->SetBoolean(true);
}
$construct->SetInt8($profile->online);
$construct->SetInt24(0);
$construct->SetBoolean(false);
$construct->SetInt24(time()-$profile->last_online);
Core::send($user->socket, $construct->get());
unset($id,$profile,$friend);
?>