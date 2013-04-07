<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
$split = Core::GetNextString($data);
$motto = $split[0];
DB::exec("UPDATE users SET motto = '".$motto."' WHERE id = '".$user->userid ."'");
$user->motto = $motto;
$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('UpdateUserInformation'));
$construct->SetInt24($user->userid);
$construct->SetStr($user->look,true);
$construct->SetStr(strtolower($user->gender),true);
$construct->SetStr($motto,true);
$construct->SetInt24(0); // winwin
Core::SendToAllRoom($user->room_id, $construct->get());
unset($construct);

unset($look,$gender,$split);
?>