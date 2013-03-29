<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * By Burak (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonCrypto
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