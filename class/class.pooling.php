<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
Class Pooling{
	public static function ManagePool(){
		global $sql;
		$needed = ceil(Core::UsersOnline()/4);
		$count = count($sql);
		if($needed > $count){
			$this->RemoveUselessPool($needed);
		}else if($needed < $count){
			$calc = $needed-$count;
			for($i = 0; $i < $calc; $i++){
				$this->NewPool();
			}
		}
	}
	private function NewPool(){
		global $sql;
		try{
			if(Config::Get("db.port") != 3306){
				$portext = chr(58).Config::Get("db.port");
			}else{
				$portext = "";
			}
			@$sql[count($sql)+1] = new PDO('mysql:host='.Config::Get("db.hostname").$portext.';dbname='.Config::Get("db.name"), Config::Get("db.username"), Config::Get("db.password"),array(PDO::ATTR_PERSISTENT => true));
		}catch(Exception $error){
			Console::WriteLine("Error in pool : ".$error->getMessage());
		}
	}
	private function RemoveUselessPool($needed){
		global $sql;
		$count = count($sql);
		$useless = $count-$needed;
		for($i = 0; $i < $useless; $i++){
			$c = array_pop($sql);
			$c->closeCursor();
			unset($c);
		}
	}
}
?>