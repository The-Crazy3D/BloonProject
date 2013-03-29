<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject
 */
Class Console{
	public static function Write($str=""){
		print($str);
	}
	public static function WriteLine($str=""){
		print($str."\n");
	}
	public static function Beep(){
		print(chr(7));
	}
	public static function SetTitle($title){
		system("title ".$title);
	}
}
?>