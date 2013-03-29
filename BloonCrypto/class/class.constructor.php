<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject
 */
Class Constructor{
	var $packet;
	public function SetHeader($header){
		if(is_numeric($header)){
			$this->packet = HabboEncoding::EncodeBit8($header);
			return true;
		}else{
			Console::WriteLine("Le header doit être composer de chiffre.");
			return false;
		}
	}
	public function SetInt8($int){
		if(is_numeric($int)){
			$this->packet .= HabboEncoding::EncodeBit8($int);
			return true;
		}else{
			Console::WriteLine("Le vl64 doit être composer de chiffre.");
			return false;
		}
	}
	public function SetInt24($int){
		if(is_numeric($int)){
			$this->packet .= HabboEncoding::EncodeBit24($int);
			return true;
		}else{
			Console::WriteLine("Le B64 doit être composer de chiffre.");
			return false;
		}
	}
	public function SetStr($str="",$withlen=false){
		if($withlen){
			$this->packet .= HabboEncoding::EncodeBit8(strlen($str));
		}
		$this->packet .= $str;
		return true;
	}
	public function get(){
		$this->packet = HabboEncoding::EncodeBit24(strlen($this->packet)).$this->packet;
		return $this->packet;
	}
}
?>