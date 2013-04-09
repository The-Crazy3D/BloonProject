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
		$table = DB::mquery("SELECT CONCAT_WS('.', TABLE_SCHEMA, TABLE_NAME) AS list FROM information_schema.TABLES WHERE DATA_FREE > 0 ORDER BY TABLE_SCHEMA ASC, TABLE_NAME ASC");
		$i = 0;
		$str = "OPTIMIZE TABLE ";
		foreach($table as $name){
			$split = explode(".", $name->list);
			if($split[0] == $dbname){
				$i++;
				if($i != 1){
					$str.= ",";
				}
				$str.= $split[1];
			}
			unset($split);
		}
		if($str != "OPTIMIZE TABLE "){
			DB::exec($str);
		}
		Console::WriteLine("completed!");
	}
}
?>