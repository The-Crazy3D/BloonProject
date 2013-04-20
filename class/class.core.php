<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
Class Core{

	/**
	*	$_instance
	*	@desc single instance of the core class
	*/
	
	private static $_instance = null;
	
	/**
	*	__construct()
	*	@desc constructor of the class Core
	*/
	
	public function __construct(){
	
	}
	
	/**
	*	getInstance()
	*	@desc get the Core singleton
	*	@return Core instance
	*/
	
	public static function getInstance(){
		if( self::$_instance === null )
			self::$_instance = new self();
		return self::$_instance;
	}
	
	/**
	*	say( $msg, $type )
	*	@version 1.1 (became static to be respectfull with POO rules)
	*	@param $msg string content of the message
	*	@param $type int type of message (should we print the current date ?)
	*	@desc display the specified message
	*/
	
	public static function say($msg="",$type=""){
		$print = "";
		if($type == 1){
			$print.= "[".date("d-m-Y")." ".date("H:i")."] ";
		}
		$msg = str_replace(chr(0), "[0]", $msg);
		$msg = str_replace(chr(1), "[1]", $msg);
		$msg = str_replace(chr(2), "[2]", $msg);
		$msg = str_replace(chr(3), "[3]", $msg);
		$msg = str_replace(chr(4), "[4]", $msg);
		$msg = str_replace(chr(5), "[5]", $msg);
		$msg = str_replace(chr(6), "[6]", $msg);
		$msg = str_replace(chr(7), "[7]", $msg);
		$msg = str_replace(chr(8), "[8]", $msg);
		$msg = str_replace(chr(9), "[9]", $msg);
		$msg = str_replace(chr(10), "[10]", $msg);
		$msg = str_replace(chr(11), "[11]", $msg);
		$msg = str_replace(chr(12), "[12]", $msg);
		$msg = str_replace(chr(13), "[13]", $msg);
		$msg = str_replace(chr(14), "[14]", $msg);
		$msg = str_replace(chr(15), "[15]", $msg);
		$msg = str_replace(chr(0xFF), "[0xFF]", $msg);
		$print.= $msg."\n";
		print($print);
	}
	public static function getuserbysocket($socket){
	  global $users;
	  $found=null;
	  foreach($users as $user){
		if($user->socket==$socket){ $found=$user; break; }
	  }
	  return $found;
	}
	public static function getuserbyusername($socket){
	  global $users;
	  $found=null;
	  foreach($users as $user){
		if($user->username==$socket){ $found=$user; break; }
	  }
	  return $found;
	}
	public static function getuserbyuserid($socket){
	  global $users;
	  $found=null;
	  foreach($users as $user){
		if($user->userid==$socket){ $found=$user; break; }
	  }
	  return $found;
	}
	public static function getuserbyuseridarray($socket){
	  global $users;
	  $found[] = array();
	  foreach($users as $user){
		if($user->userid==$socket){ $found[]=$user; }
	  }
	  return $found;
	}
	public static function disconnect($socket){
	  global $sockets,$users;
	  $found=null;
	  $n=count($users);
	  for($i=0;$i<$n;$i++){
		if($users[$i]->socket==$socket){ $found=$i; break; }
	  }
	  $usertemp = self::getuserbysocket($socket);
	  if(isset($usertemp->room_id) && is_numeric($usertemp->room_id)){
		$construct = New Constructor;
		$construct->SetHeader(Packet::GetHeader('UserLeftRoom'));
		$construct->SetStr($usertemp->userid,true);
		self::SendToRoom($usertemp->room_id, $construct->get(),$usertemp->userid);
		DB::exec("UPDATE rooms SET users_now = users_now-1 WHERE id = '".$usertemp->room_id."'");
	  }
	  if(isset($usertemp->room_id) && is_numeric($usertemp->userid)){
		DB::exec("UPDATE users SET online = '0' WHERE id = '".$usertemp->userid ."'");
		$friend = DB::mquery("SELECT u.id,u.username,u.look,u.online,u.motto FROM messenger_friendships m, users u WHERE m.user_one_id = ".$usertemp->userid ." AND u.id = m.user_two_id AND u.online = '1' ORDER BY -online;");
		if($friend){
			foreach($friend as $fuser){
				self::RemoveFriendBar($fuser->id,$usertemp->userid);
			}
		}
	  }
	  if(Config::Get("emu.messages.connections")){
		self::say("[".$usertemp->countconnection ."] Connection lost from ".$usertemp->ip,1);
	  }
	  if(!is_null($found)){ array_splice($users,$found,1); }
	  $index = array_search($socket,$sockets);
	  socket_close($socket);
	  if($index>=0){ array_splice($sockets,$index,1); }
	}
	public static function connect($socket){
	  global $sockets,$users,$countconnection;
	  if(!isset($countconnection)){
		$countconnection = 0;
	  }
	  socket_getpeername($socket, $ip, $port);
	  $user = new User();
	  $user->id = uniqid();
	  $user->socket = $socket;
	  $user->ip = $ip;
	  $user->port = $port;
	  $user->countconnection = $countconnection;
	  if(Config::Get("emu.messages.connections")){
		self::say("[".$countconnection."] Connection from ".$ip,1);
	  }
	  $countconnection++;
	  array_push($users,$user);
	  array_push($sockets,$socket);
	}
	public static function Socket($address,$port){
	  $fp = @fsockopen($address, $port, $errno, $errstr, 0.5);
	  if($fp){
	    Console::WriteLine("Can't bind ".$address.":".$port." because is already used by other program.");
		exit;
	  }
	  $master=socket_create(AF_INET, SOCK_STREAM, SOL_TCP)     or die("socket_create() failed");
	  socket_set_option($master, SOL_SOCKET, SO_REUSEADDR, 1)  or die("socket_option() failed");
	  socket_bind($master, $address, $port)                    or die("socket_bind() failed");
	  socket_listen($master,20)                                or die("socket_listen() failed");
	  Console::WriteLine("Listening for connections on port : ".$port);
	  return $master;
	}
	public static function send($client,$msg){
	  socket_write($client,$msg,strlen($msg));
	  if(Config::Get("emu.messages.debug")){
		self::say("[OUTGOING]".$msg,1);
	  }
	}
	public static function HexaString($hex){
		$string='';
		$hex = str_replace(" ", "", strtolower($hex));
		for ($i=0; $i < strlen($hex)-1; $i+=2)
		{
			$string .= chr(hexdec($hex[$i].$hex[$i+1]));
		}
		return $string;
	}
	
	public static function GetHeader($packet) {
		$packdata = @substr($packet, 6);
		$packet = @substr($packet, 4);
		$len = (strlen($packet)-2);
		$neg = "-".$len;
		if($len != 0){
			$packet = @substr($packet, 0, $neg);
		}
		$decode = HabboEncoding::DecodeBit8($packet);
		$say = Array($decode,$packet,$packdata);
		return $say;
	}
	
	/**
	*	BufferParser( $buffer )
	*	@desc return an array according to the buffer sent
	*	@param $buffer string buffer value
	*	@return $packet array containing buffer information.
	*/
	
	public static function BufferParser($buffer){
		$tache = true;
		$packet = array();
		while($tache){
			if(strlen($buffer) > 0){
				$split = str_split($buffer);
				if(!isset($split[0]) || !isset($split[1]) || !isset($split[2]) || !isset($split[3])){
					break;
				}
				$lenth = HabboEncoding::DecodeBit24($split[0].$split[1].$split[2].$split[3])+4;
				$packet[] = substr($buffer, 0, $lenth);
				$buffer = substr($buffer, $lenth);
			}else{
				$tache = false;
			}
		}
		return $packet;
	}
	
	/**
	*	GetNextString( $str )
	*	@desc ...
	*	@param $str string
	*	@param @mode boolean
	*	@return array of string
	*/
	
	public static function GetNextString($str,$mode=false){
		$stringArray = str_split($str);
		if($mode){
			$stringLenth = HabboEncoding::DecodeBit8($stringArray[0].$stringArray[1])+4;
		}else{
			$stringLenth = HabboEncoding::DecodeBit8($stringArray[0].$stringArray[1])+2;
		}
		$string = substr($str, 0, $stringLenth);
		if($mode){
			$string = substr($string, 4);
		}else{
			$string = substr($string, 2);
		}
		$reste = substr($str, $stringLenth);
		return Array($string, $reste);
	}
	public static function OnStartTasks(){
		Loader::LoadServerSettings();
		Loader::LoadBans();
		Loader::LoadRoles();
		Loader::LoadHelpCategories();
		Loader::LoadHelpTopics();
		Loader::LoadSoundtracks();
		Loader::LoadCataloguePages();
		Loader::LoadCatalogueItems();
		Loader::LoadNavigatorCategories();
		Loader::LoadNavigatorPublics();
		Loader::LoadRoomModels();
		Loader::LoadRoomAds();
		Loader::LoadBots();
		Loader::LoadAchievements();
		Loader::LoadChatFilter();
		Loader::LoadQuests();
		Loader::LoadGroups();
		Loader::LoadHandlers();
		Console::Beep();
		self::StatsTasks();
		self::CleanUpDatabase();
	}
	public static function CleanUpDatabase(){
		Console::Write("Cleaning up database...");
		DB::exec("UPDATE users SET online = '0'");
		DB::exec("UPDATE rooms SET users_now = '0'");
		Console::WriteLine("completed!");
	}
	public static function StatsTasks(){
		if(Config::Get("emu.messages.debug")){
			$addin = "[DEBUG] ";
		}else{
			$addin = "";
		}
		Console::SetTitle($addin.'BloonCrypto - Users online : '.self::UsersOnline().' - Rooms loaded : '.self::RoomsLoaded().' - Memory : '.self::get_php_memory());
		Pooling::ManagePool();
	}
	
	/**
	*	get_php_memory()
	*	@desc return the current programm consumtion
	*	@return $result string (memory + size type)
	*/
	
	public static function get_php_memory(){
		$mem_usage = memory_get_usage(true); 
		if ($mem_usage < 1024){
			$result = $mem_usage." o"; 
		}else if ($mem_usage < 1048576){
			$result = round($mem_usage/1024,2)." Ko"; 
		}else{
			$result = round($mem_usage/1048576,2)." Mo"; 
		}
		return $result;
	}
	public static function Crossdomain(){
		return '<?xml version="1.0"?>
		<!DOCTYPE cross-domain-policy SYSTEM "/xml/dtds/cross-domain-policy.dtd">
		<cross-domain-policy>
		<allow-access-from domain="*" to-ports="1-31111" />
		</cross-domain-policy>'.chr(0);
	}
	public static function GetModel($name){
		global $roommodels;
		foreach($roommodels as $model){
			if($name == $model->id){
				return $model;
			}
		}
	}
	public static function GetMap(){
		$map=array();
		for($x=1;$x<=60;$x++){
			for($y=1;$y<=60;$y++){
				// $rand=rand(1,4);
				// if($rand==1){
					// $map[$x.'x'.$y]=array('weight'=>'3.0');
				// } else {
					$map[$x.'x'.$y]=array('weight'=>'1.0');
				// }
			}
		}
		return $map;
	}
	public static function RotationCalculate($one,$two){
		$one_x = $one[0];
		$one_y = $one[1];
		$two_x = $two[0];
		$two_y = $two[1];
		
		if($one_x > $two_x && $one_y > $two_y){
			return 7;
		}else if($one_x < $two_x && $one_y < $two_y){
			return 3;
		}else if($one_x > $two_x && $one_y < $two_y){
			return 5;
		}else if($one_x < $two_x && $one_y > $two_y){
			return 1;
		}else if($one_x > $two_x){
			return 6;
		}else if($one_x < $two_x){
			return 2;
		}else if($one_y < $two_y){
			return 4;
		}else if($one_y > $two_y){
			return 0;
		}
		return 0;
	}
	public static function GetUserByRoom($room){
		global $users;
		$array = array();
		foreach($users as $user){
			if($room == $user->room_id){
				$array[] = $user;
			}
		}
		return $array;
	}
	public static function SendToRoom($room, $packet,$mode=""){
		global $users;
		foreach($users as $user){
			if($room == $user->room_id){
				if(is_numeric($mode) && $mode != $user->userid){
					self::send($user->socket, $packet);
				}
			}
		}
	}
	public static function SendToAllRoom($room, $packet){
		global $users;
		foreach($users as $user){
			if($room == $user->room_id){
				self::send($user->socket, $packet);
			}
		}
	}
	public static function SendToAll($packet){
		global $users;
		foreach($users as $user){
			self::send($user->socket, $packet);
		}
	}
	public static function LoadFriendBar($userid){
		$user = self::getuserbyuserid($userid);
		$friend = DB::mquery("SELECT u.id,u.username,u.look,u.online,u.motto FROM messenger_friendships m, users u WHERE m.user_one_id = ".$user->userid ." AND u.id = m.user_two_id ORDER BY -online;");
		if($friend){
		$construct = New Constructor;
		$construct->SetHeader(Packet::GetHeader('InitFriends'));
		$construct->SetInt24(1100);
		$construct->SetInt24(300);
		$construct->SetInt24(800);
		$construct->SetInt24(1100);
		$construct->SetInt24(0);
		$construct->SetInt24(count($friend));
		foreach($friend as $fdata){
			$construct->SetInt24($fdata->id);
			$construct->SetStr($fdata->username,true);
			$construct->SetInt24(0);
			if($fdata->online == 1){
				$construct->SetBoolean(true);
				$construct->SetBoolean(true);
			}else{
				$construct->SetInt8(0);
			}
			$construct->SetStr($fdata->look,true);
			$construct->SetInt24(0);
			$construct->SetStr($fdata->motto,true);
			$construct->SetInt24(0);
			$construct->SetBoolean(true);
			$construct->SetBoolean(true);
			$construct->SetBoolean(false);
		}
		self::send($user->socket, $construct->get());
		unset($construct,$friend,$fdata);
		}
	}
	public static function AddFriendBar($fid, $uid){
		$f = self::getuserbyuserid($fid);
		$u = self::getuserbyuserid($uid);
		$construct = New Constructor;
		$construct->SetHeader(Packet::GetHeader('FriendUpdate'));
		$construct->SetInt24(0);
		$construct->SetInt24(1);
		$construct->SetInt24(0);
		$construct->SetInt24($u->userid);
		$construct->SetStr($u->username,true);
		$construct->SetInt24(1);
		$construct->SetBoolean(true);
		$construct->SetBoolean(false);
		$construct->SetStr($u->look,true);		
		$construct->SetInt24(0);
		$construct->SetStr($u->motto,true);	
		$construct->SetInt24(0);
		$construct->SetBoolean(true);
		$construct->SetBoolean(true);
		$construct->SetBoolean(false);
		$construct->SetBoolean(false);
		self::send($f->socket, $construct->get());
	}
	public static function RemoveFriendBar($fid, $uid){
		$f = self::getuserbyuserid($fid);
		$u = self::getuserbyuserid($uid);
		$construct = New Constructor;
		$construct->SetHeader(Packet::GetHeader('FriendUpdate'));
		$construct->SetInt24(0);
		$construct->SetInt24(1);
		$construct->SetInt24(0);
		$construct->SetInt24($u->userid);
		$construct->SetStr($u->username,true);
		$construct->SetInt24(1);
		$construct->SetInt24(0);
		$construct->SetInt24(0);
		$construct->SetStr($u->motto,true);	
		$construct->SetInt24(0);
		$construct->SetBoolean(true);
		$construct->SetBoolean(true);
		$construct->SetBoolean(false);
		$construct->SetBoolean(false);
		self::send($f->socket, $construct->get());
	}
	public static function PermissionRank($rank, $permission){
		global $permissions;
		foreach($permissions['ranks'] as $perm){
			if($perm->rank == $rank){
				if(isset($perm->$permission)){
					if($perm->$permission == 1){
						return true;
					}else{
						return false;
					}
				}
			}
		}
	}
	public static function GetSettings($name){
		global $serversettings;
		if(isset($serversettings->$name)){
			$data = $serversettings->$name;
			if($data == "1"){
				return true;
			}else if($data == "0"){
				return false;
			}else{
				return $data;
			}
		}
	}
	public static function Chatlogs($userid, $roomid, $hour, $minute, $fulldate, $timestamp, $message, $username){
		if(self::GetSettings("enable_chatlogs")){
			$message = str_replace("'", "\'", $message);
			DB::exec("INSERT INTO chatlogs (user_id,room_id,hour,minute,full_date,timestamp,message,user_name) VALUES ('".$userid."','".$roomid."','".$hour."','".$minute."','".$fulldate."','".$timestamp."','".$message."','".$username."')");
		}
	}
	public static function Cmdlogs($userid, $username, $command, $extra, $timestamp){
		if(self::GetSettings("enable_cmdlogs")){
			$extra = str_replace("'", "\'", $extra);
			DB::exec("INSERT INTO cmdlogs (user_id,user_name,command,extra_data, timestamp) VALUES ('".$userid."','".$username."','".$command."','".$extra."','".$timestamp."')");
		}
	}
	public static function UsersOnline(){
		global $users;
		$cpt = 0;
		if(count($users) > 0){
			foreach($users as $user){
				if(is_numeric($user->userid)){
					$cpt++;
				}
			}
		}
		return $cpt;
	}
	public static function RoomsLoaded(){
		global $users;
		$cpt = 0;
		$array = array();
		if(count($users) > 0){
			foreach($users as $user){
				if(isset($user->room_id) && is_numeric($user->room_id)){
					if(!in_array($user->room_id, $array)){
						$cpt++;
						$array[] = $user->room_id;
					}
				}
			}
		}
		return $cpt;
	}
	public static function GetRevision(){
		return file_get_contents("revision");
	}
	public static function GetVersion(){
		return file_get_contents("version");
	}
	public static function DiffTime($microtime1, $microtime2){
		/* Source : http://www.phpcs.com/codes/TEMPS-GENERATION-PAGE-PHP_12903.aspx */
		list($micro1, $time1) = explode(' ', $microtime1);
		list($micro2, $time2) = explode(' ', $microtime2);
		$time = $time2 - $time1;
		if ($micro1 > $micro2){
			$time--;
			$micro = 1 + $micro2 - $micro1;	 
		}else{
			$micro = $micro2 - $micro1;
		}
		$micro += $time;
		$split = explode(".", $micro);
		return $split;
	}
	public static function GetTileData($x, $y, $model){
		$heightmap_step1 = explode(chr(0x0D), $model);
		foreach($heightmap_step1 as $heightmapkey => $heightmapdata){
			if($y == $heightmapkey){
				$split = str_split($heightmapdata);
				return $split[($x+1)];
			}
		}
	}
	public static function GetFloorItems($userid){
		return DB::mquery('SELECT i.id,f.type,f.sprite_id,f.allow_recycle,f.allow_trade,f.allow_inventory_stack,f.allow_marketplace_sell FROM items i, furniture f WHERE i.user_id = "'.$userid.'" AND i.room_id = 0 AND i.base_item = f.id AND f.type = "s"');
	}
	public static function GetWallItems($userid){
		return DB::mquery('SELECT i.*,f.* FROM items i, furniture f WHERE i.user_id = "'.$userid.'" AND i.room_id = 0 AND i.base_item = f.id AND f.type = "i"');
	}
	public static function getItemData($id){
		return DB::query("SELECT * FROM items WHERE id = '".$id."'");
	}
	public static function Evall($code){
		global $GLOBALS;
		foreach($GLOBALS as $varname){
			eval("global ".$varname.";");
		}
		eval($code);
	}
	public static function InitInventory($uid){
		$user = self::getuserbyuserid($uid);
		$construct = New Constructor;
		$construct->SetHeader(Packet::GetHeader('Inventory'));
		$construct->SetStr("S",true);
		$construct->SetInt24(1);
		$construct->SetInt24(1);
		$flooritems = Core::GetFloorItems($user->userid);
		if(!$flooritems){
			$construct->SetInt24(0);
		}else{
			$construct->SetInt24(count($flooritems));
			foreach($flooritems as $flooritem){
				$construct->SetInt24($flooritem->id);
				$construct->SetStr(strtoupper($flooritem->type),true);
				$construct->SetInt24($flooritem->id);
				$construct->SetInt24($flooritem->sprite_id);
				$construct->SetInt24(1);
				$construct->SetInt8(0);
				$construct->SetInt24(0);
				
				$construct->SetBoolean($flooritem->allow_recycle);
				$construct->SetBoolean($flooritem->allow_trade);
				$construct->SetBoolean($flooritem->allow_inventory_stack);
				$construct->SetBoolean($flooritem->allow_marketplace_sell);
				
				$construct->SetInt24(-1);
				$construct->SetInt8(0);
				$construct->SetInt24(0);
			}
		}
		Core::send($user->socket, $construct->get());
		unset($construct);
		
		$construct = New Constructor;
		$construct->SetHeader(Packet::GetHeader('Inventory'));
		$construct->SetStr("I",true);
		$construct->SetInt24(1);
		$construct->SetInt24(1);
		$wallitems = Core::GetWallItems($user->userid);
		if(!$wallitems){
			$construct->SetInt24(0);
		}else{
			$construct->SetInt24(count($wallitems));
			foreach($wallitems as $wallitem){
			}
		}
		Core::send($user->socket, $construct->get());
		
		unset($flooritems,$wallitems,$packet1,$packet2);
	}
	public static function GetTileFurni($x, $y, $roomid){
		global $globalroomitems;
		$result = array();
		$posarray = array();
		foreach($globalroomitems[$roomid] as $roomitems){
			$pos = array();
			$pos[] = array("x" => $roomitems->x,"y" => $roomitems->y);
			if($roomitems->length > 1){
				if($roomitems->rot == "2" || $roomitems->rot == "6"){
					for($i = 1; $i < $roomitems->length; $i++){
						$pos[] = array("x" => $roomitems->x,"y" => $roomitems->y+$i);
						for($j = 1; $j < $roomitems->width; $j++){
							$pos[] = array("x" => $roomitems->x+$j,"y" => $roomitems->y+$i);
						}
					}
					unset($i,$j);
				}else if($roomitems->rot == "0"| $roomitems->rot == "4"){
					for($i = 1; $i < $roomitems->length; $i++){
						$pos[] = array("x" => $roomitems->x+$i,"y" => $roomitems->y);
						for($j = 1; $j < $roomitems->width; $j++){
							$pos[] = array("x" => $roomitems->x+$i,"y" => $roomitems->y+$j);
						}
					}
					unset($i,$j);
				}
			}
			if($roomitems->width > 1){
				if($roomitems->rot == "2" || $roomitems->rot == "6"){
					for($i = 1; $i < $roomitems->width; $i++){
						$pos[] = array("x" => $roomitems->x,"y" => $roomitems->y+$i);
						for($j = 1; $j < $roomitems->length; $j++){
							$pos[] = array("x" => $roomitems->x+$j,"y" => $roomitems->y+$i);
						}
					}
					unset($i,$j);
				}else if($roomitems->rot == "0"| $roomitems->rot == "4"){
					for($i = 1; $i < $roomitems->width; $i++){
						$pos[] = array("x" => $roomitems->x+$i,"y" => $roomitems->y);
						for($j = 1; $j < $roomitems->length; $j++){
							$pos[] = array("x" => $roomitems->x+$i,"y" => $roomitems->y+$j);
						}
					}
					unset($i,$j);
				}
			}
			foreach($pos as $exec){
				if($exec['x'] == $x && $exec['y'] == $y){
					$result[] = $roomitems;
				}
			}
			unset($pos,$exec);
		}
		return $result;
	}
	public static function ReloadRoomFurni($roomid){
		global $globalroomitems;
		$globalroomitems[$roomid] = DB::mquery("SELECT f.width, f.length, f.sprite_id, f.can_sit, f.stack_height, f.interaction_type, i.id, i.x, i.z, i.extra_data, i.rot, i.y, i.z FROM furniture f, items i, rooms r
										WHERE f.id = i.base_item
										AND i.room_id = r.id
										AND r.id = ".$roomid);
		return $globalroomitems[$roomid];
	}
	public static function LoadCredits($userid){
		global $user;
		$user = self::getuserbyuserid($userid);
		$query = DB::query("SELECT credits FROM users WHERE id = '".$userid."'");
		$construct = New Constructor;
		$construct->SetHeader(Packet::GetHeader('CreditsBalance'));
		$construct->SetStr($query->credits .".0",true);
		$user->credits = $query->credits;
		Core::send($user->socket, $construct->get());
		return true;
	}
	public static function AddCredits($amount,$userid){
		DB::exec("UPDATE users SET credits = credits+".$amount." WHERE id = '".$userid."'");
		self::LoadCredits($userid);
		return true;
	}
	public static function RemoveCredits($amount,$userid){
		DB::exec("UPDATE users SET credits = credits-".$amount." WHERE id = '".$userid."'");
		self::LoadCredits($userid);
		return true;
	}
	public static function GetTileHeight($x, $y, $roomid){
		$furni = self::GetTileFurni($x, $y, $roomid);
		$height = 0;
		foreach($furni as $item){
			$height = $height+$item->stack_height;
		}
		return $height;
	}
}
?>