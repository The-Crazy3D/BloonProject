<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
Class Pooling{
	public static function GetPool(){
		global $pools,$sql;
		$timenow = time();
		$result = 0;
		if(isset($pools)){
			foreach($pools as $key => $time){
				if($time <= $timenow){
					$result = $key;
					$timenow = $time;
				}
			}
		}
		return $result;
	}
	public static function ManagePool(){
		global $sql;
		$users_online = Core::UsersOnline();
		if($users_online == 0){
			$users_online = 1;
		}
		$needed = ceil($users_online/4);
		$count = count($sql);
		if($needed < $count){
			self::RemoveUselessPool($needed);
		}else if($needed > $count){
			$calc = $needed-$count;
			for($i = 0; $i < $calc; $i++){
				self::NewPool();
			}
		}
	}
	private static function NewPool(){
		global $sql,$pools;
		try{
			if(Config::Get("db.port") != 3306){
				$portext = chr(58).Config::Get("db.port");
			}else{
				$portext = "";
			}
			$count = count($sql)+1;
			@$sql[$count] = new PDO('mysql:host='.Config::Get("db.hostname").$portext.';dbname='.Config::Get("db.name"), Config::Get("db.username"), Config::Get("db.password"),array(PDO::ATTR_PERSISTENT => true));
			$pools[$count] = time();
		}catch(Exception $error){
			Console::WriteLine("Error in pool : ".$error->getMessage());
		}
	}
	private static function RemoveUselessPool($needed){
		global $sql,$pools;
		$count = count($sql);
		$useless = $count-$needed;
		for($i = 0; $i < $useless; $i++){
			$c = array_pop($sql);
			array_pop($pools);
			unset($c);
		}
	}
}
?>