<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
$actionid = HabboEncoding::DecodeBit24($data);
$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('Action'));
$construct->SetInt24($user->userid);
$construct->SetInt24($actionid);
Core::SendToAllRoom($user->room_id, $construct->get());
?>