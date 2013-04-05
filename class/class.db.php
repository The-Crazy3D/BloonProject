<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
Class DB{

	private $_sqlConnexion;
	
	private static $_instance = null;
	
	public static function getInstance(){
		if( self::$_instance === null )
			self::$_instance = new self();
		return self::$_instance;
	}
	
	private function __construct(){
		/*
			$_sqlConnexion should be set here and "global $sql" should disappear...
		*/
		Console::Write("Connecting to database...");
		
		try{
			if(Config::Get("db.port") != 3306){
				$portext = chr(58).Config::Get("db.port");
			}else{
				$portext = "";
			}
			$this->_sqlConnexion = new PDO('mysql:host='.Config::Get("db.hostname").$portext.';dbname='.Config::Get("db.name"), Config::Get("db.username"), Config::Get("db.password"));
		}catch(Exception $error){
			Console::WriteLine("failed!");
			Console::WriteLine("Error : ".$error->getMessage());
			exit;
		}
		
		Console::WriteLine("completed!");
	}
	
	public static function query($req){
		global $sql;
		$exe = $sql->prepare($req);
		$exe->execute();
		if($exe->rowCount() < 1){
			return false;
		}else if($exe->rowCount() == 1){
			return $exe->fetch(PDO::FETCH_OBJ);
		}
	}
	public static function mquery($req){
		global $sql;
		$exe = $sql->prepare($req);
		$exe->execute();
		if($exe->rowCount() < 1){
			return false;
		}else{
			$result = array();
			while($query = $exe->fetch(PDO::FETCH_OBJ)){
				$result[] = $query;
			}
			return $result;
		}
	}
	public static function numrows($req){
		global $sql;
		$exe = $sql->prepare($req);
		$exe->execute();
		return $exe->rowCount();
	}
	public static function exec($req){
		global $sql;
		$exe = $sql->prepare($req);
		$exe->execute();
		return true;
	}
}
?>