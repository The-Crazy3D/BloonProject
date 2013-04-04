<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('chatBullet'));
$construct->SetInt24($user->userid);
$construct->SetInt24(0);
Core::SendToAllRoom($user->room_id, $construct->get());
?>