<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
if(Core::Get("emu.messages.connections")){
	$release = Core::GetNextString($data);
	Core::say("Loaded ".$release[0],1);
}
?>