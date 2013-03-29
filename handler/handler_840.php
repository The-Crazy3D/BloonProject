<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonCrypto
 */
$construct = New Constructor;
$construct->SetHeader($Outgoing['init2']);
$construct->SetStr("M24231219992253632572058933470468103090824667747608911151318774416044820318109",true);
$core->send($user->socket, $construct->get());
?>