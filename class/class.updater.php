<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
Class Updater{
	public static function Check(){
		Console::WriteLine("Checking for update...");
		$gitbuild = intval(file_get_contents("http://raw.github.com/BurakDev/BloonProject/BloonCrypto/revision"));
		$localbuild = file_get_contents("revision");
		Console::WriteLine("Git build : ".$gitbuild.", Local build : ".$localbuild);
		if($gitbuild == $localbuild){
			Console::WriteLine("Completed! No update needed.");
		}else if($gitbuild > $localbuild){
			Console::WriteLine("Completed! BloonCrypto need update !");
			Console::WriteLine();
			Console::WriteLine("If you want update emulator from GitHub, say y (or yes)");
			Console::WriteLine("The Update will be in the folder : BloonProject-BloonCrypto");
			Console::Write(">>> ");
			$get = trim(fgets(STDIN));
			Switch($get){
				case "yes":
				case "Y":
				case "y":
					self::GetZip();
				break;
				Default:
					Console::WriteLine("Okay, emulator is not updated.");
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
		file_put_contents($filename, file_get_contents("https://github.com/BurakDev/BloonProject/archive/BloonCrypto.zip"));
		Console::WriteLine("Downloaded and writed to disk.");
		Console::WriteLine("Unzip new release...");
		self::_unzip($filename, './');
		unlink($filename);
		Console::WriteLine("Unzip finished. Now emulator close, restart it.");
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
