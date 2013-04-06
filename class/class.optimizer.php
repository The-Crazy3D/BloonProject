<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
Class Optimizer{
	public static function Exec($dbname){
		Console::Write("Optimize all tables...");
		$table = DB::mquery("SHOW TABLES");
		$i = 0;
		$str = "OPTIMIZE TABLE ";
		foreach($table as $name){
			$i++;
			eval('$db = $name->Tables_in_'.$dbname.';');
			if($i != 1){
				$str .= ",";
			}
			$str .= $db;
		}
		DB::exec($str);
		Console::WriteLine("completed!");
	}
}
?>