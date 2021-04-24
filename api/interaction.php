<?php
/*
	HomeIntelligence API
	If called by POST method, an audio data called "audio_data" must be sent (.wav)
	If called from GET, a parameter called "text" must be sent.
*/

$userText = ''; // Empty first
if ($_SERVER['REQUEST_METHOD'] === 'POST') { // THERE IS AN AUDIO
        $input = $_FILES['audio_data']['tmp_name'];
        move_uploaded_file($input, "/var/www/html/scripts/test_tmp.wav");
        $userText = shell_exec("cd /var/www/html/scripts && bash stt.sh 2>&1");
}
else if($_SERVER['REQUEST_METHOD'] === 'GET')
{
        $userText = $_GET['text'];
}
$userText = removeAccents($userText);
include 'userDB.php'; // The database defined by the user

// Politesse
$db_politesse = $db['politesse'];
$db_politesse_keys = array_keys($db_politesse);
$i = 0;
foreach($db_politesse as $politesse)
{
  if(strpos($userText, $db_politesse_keys[$i]) !== false){
	echo $politesse;
  }
  $i = $i + 1;
}
$i = 0;
echo '</br>Vous avez dit: '.$userText;
//Ordres
echo ' <br><br><i><b><span style="text-decoration: underline;">[ANALYSE SEMANTIQUE]</span><br>';
echo '<br><span style="text-decoration: underline;">Découpage de la phrase :</span> <br>';
$cutUserText = preg_split( "/ (et|puis|aussi) /", $userText );
$i = 0;
$ordres = $db['ordre'];
$ordres_keys = array_keys($ordres);
$lastKey = null;
foreach($cutUserText as $text) // Pour chaque portion de texte découpé
{
	echo '</br>';
	echo "(". $i. ")"  . $text;
	$i++;
	$containsOrder = false;
	
	foreach($ordres_keys as $key) // Pour chaque ordre (allumer ou éteindre par exemple)
	{ 
		if(strpos($text, $key) !== false){
			$containsOrder = true; $lastKey = $key; echo ' </b><span style="color: red;">['.$key.'] est détecté.</span><b>';
			$objets_array = $ordres[$key];
			$objets_array_keys = array_keys($objets_array);
			foreach($objets_array_keys as $objet)
			{
				if(strpos($text, $objet) !== false){
					$id = $ordres[$key][$objet]; 
					echo '[OBJET RECONNU: ' . $objet . '][ID='.$id.']'; 
					if(strpos($id, "+") !== false){
						echo "[Composition d'action]";
						$cutID = explode('+', $id );
						foreach($cutID as $id_)
						{shell_exec('rm -f jee* && wget "'.$db['homeapi'].$id_.'" > /dev/null 2>/dev/null &');} sleep(0.5);
					}
					else{
						shell_exec('rm -f jee* && wget "'.$db['homeapi'].$id.'" > /dev/null 2>/dev/null &');
					}
				}
			}
		}
	}
	if($containsOrder == false && $lastKey != null){ 
		echo ' </b><span style="color: red;">['.$lastKey.'][LIEN PRECEDENT] est détecté.</span><b>';
		$objets_array = $ordres[$lastKey];
		$objets_array_keys = array_keys($objets_array);
		foreach($objets_array_keys as $key)
		{
			if(strpos($text, $key) !== false){
				$id = $objets_array[$key]; 
				if(strpos($id, "+") !== false)
				{
					echo "[Composition d'action]";
                                        $cutID = explode('+', $id );
                                        foreach($cutID as $id_)
                                        {shell_exec('wget "'.$db['homeapi'].$id_.'" > /dev/null 2>/dev/null &');} sleep(0.5);
				}
				else
				{
					shell_exec('wget "'.$db['homeapi'].$id.'" > /dev/null 2>/dev/null &');
				}
			}
		}
	}
}




function removeAccents($str) { // Used to remove accent
  $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', 'Ά', 'ά', 'Έ', 'έ', 'Ό', 'ό', 'Ώ', 'ώ', 'Ί', 'ί', 'ϊ', 'ΐ', 'Ύ', 'ύ', 'ϋ', 'ΰ', 'Ή', 'ή');
  $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', 'Α', 'α', 'Ε', 'ε', 'Ο', 'ο', 'Ω', 'ω', 'Ι', 'ι', 'ι', 'ι', 'Υ', 'υ', 'υ', 'υ', 'Η', 'η');
  return str_replace($a, $b, $str);
}
?>
