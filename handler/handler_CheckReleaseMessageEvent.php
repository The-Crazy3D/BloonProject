<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
if(Config::Get("emu.messages.connections")){
	$release = Core::GetNextString($data);
	$user->release = $release[0];
	Core::say("Loaded ".$release[0],1);
}
?>