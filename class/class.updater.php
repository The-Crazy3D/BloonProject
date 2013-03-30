<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 * Thanks to Ligams for this unZip SCRIPT.
 */
 
 
Class Updater{
	public static function curl_load($url)
		{
 		$curl = curl_init();
 		$userAgent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13';
 
 		curl_setopt($curl,CURLOPT_URL,$url); 
 		curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE);
		curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,10); 
		curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
 		curl_setopt($curl, CURLOPT_FAILONERROR, TRUE); 
 		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
 		curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);
 		curl_setopt($curl, CURLOPT_TIMEOUT, 10); 	
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
 
		$curl_contents = curl_exec($curl);
 		curl_close($curl);
 		return $curl_contents;
		}
	public static function Check(){
		Console::WriteLine("Checking for a new build...");
		$gitbuild = intval(curl_load("https://raw.github.com/BurakDev/BloonProject/BloonCrypto/revision"));
		$localbuild = file_get_contents("revision");
		Console::WriteLine("Git build : ".$gitbuild.", Local build : ".$localbuild);
		if($gitbuild == $localbuild){
			Console::WriteLine("Completed! No new build.");
		}else if($gitbuild < $localbuild){
			Console::WriteLine("Better build impossibru..");
		}else if($gitbuild > $localbuild){
			Console::WriteLine("Completed! BloonCrypto Build ".$gitbuild." has been released  !");
			Console::WriteLine();
			Console::WriteLine("If you want to download the new build from GitHub, say y (or yes)");
			Console::WriteLine("The new build will be in the folder : BloonProject-BloonCrypto");
			Console::Write(">>> ");
			$get = trim(fgets(STDIN));
			Switch($get){
				case "yes":
				case "Y":
				case "y":
					self::GetZip();
				break;
				Default:
					Console::WriteLine("No new build for you.");
				break;
			}
		}else{
		}
	}
	public static function GetZip(){
		Console::WriteLine("Downloading last version from Github...");
		$filename = "BloonCrypto.zip";
		if(file_exists($filename)){
			unlink($filename);
		}
		touch($filename);
		file_put_contents($filename, curl_load("https://github.com/BurakDev/BloonProject/archive/BloonCrypto.zip"));
		Console::WriteLine("Downloaded and writed to disk.");
		Console::WriteLine("Unzip new release...");
		self::_unzip($filename, './');
		unlink($filename);
		Console::WriteLine("Unzip finished. Now close emulator, remplace the files from your directory by the files on BloonProject-BloonCrypto");
		exit;
	}
	

function _unzip($zipfile, $dest)
{
if (extension_loaded('zip')){
$zip = zip_open($zipfile);	
if (substr( $dest, strlen( $dest ) - 1 ) != '/')
$dest = $dest . '/';

if ($zip){
while ($zip_entry = zip_read($zip))
{
$file = zip_entry_name($zip_entry);
$aFolderStructure = preg_split('@[\\|/]@', $file);

if (count($aFolderStructure) > 1){
for ($i = 0; $i < count($aFolderStructure) - 1; $i++){
$dossierFull = $dest;
for ($j = 0; $j < count($aFolderStructure) - 1; $j++){
if (substr( $dossierFull, strlen( $dossierFull ) - 1 ) != '/')
$dossierFull = $dossierFull . '/';

$dossierFull = $dossierFull . $aFolderStructure[$j];
}

if (!file_exists($dossierFull) && !is_dir($dossierFull))
mkdir($dossierFull, 0666);
}
}

if(!is_dir($dest . $file)){
$fp = @fopen($dest . $file, "w+");

if(zip_entry_open($zip, $zip_entry, "r")){
$buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
zip_entry_close($zip_entry);
}

fwrite($fp, $buf);
fclose($fp);
}
}
zip_close($zip);
return true;
}
return false;
}
}
}
?>
