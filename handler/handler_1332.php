<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
$split = Core::GetNextString($data);
$gender = $split[0];
$data = $split[1];
$split = Core::GetNextString($data);
$look = $split[0];
$user->look = $look;
$user->gender = $gender;
DB::exec("UPDATE users SET look = '".$look."',gender = '".$gender."' WHERE id = '".$user->userid ."'");
$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('updateLook'));
$construct->SetInt24($user->userid);
$construct->SetStr($look,true);
$construct->SetStr(strtolower($gender),true);
$construct->SetStr($user->motto,true);
$construct->SetInt24(0); // winwin
Core::SendToAllRoom($user->room_id, $construct->get());
unset($construct);

$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('updateLook'));
$construct->SetStr(chr(0xFF).chr(0xFF).chr(0xFF).chr(0xFF));
$construct->SetStr($look,true);
$construct->SetStr(strtolower($gender),true);
$construct->SetStr($user->motto,true);
$construct->SetInt24(0); // winwin
Core::SendToAllRoom($user->room_id, $construct->get());
unset($look,$gender,$split);
?>