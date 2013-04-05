<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
$danceid = HabboEncoding::DecodeBit24($data);
$construct = New Constructor;
$construct->SetHeader(Packet::GetHeader('Dance'));
$construct->SetInt24($user->userid);
$construct->SetInt24($danceid);
Core::SendToAllRoom($user->room_id, $construct->get());
unset($danceid);
?>