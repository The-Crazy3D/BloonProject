<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonCrypto
 */
$construct = New Constructor;
$construct->SetHeader($Outgoing['init1']);
$construct->SetStr("12f449917de4f94a8c48dbadd92b6276",true);
$construct->SetStr(chr(0));
$core->send($user->socket, $construct->get());
?>