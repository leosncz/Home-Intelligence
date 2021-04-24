<?php

include 'top.php';
include 'api/userDB.php'
?>

<center>
<div class="alert alert-warning" role="alert">
  Certains navigateurs ne sont pas compatibles avec le formulaire d'intéraction vocale
</div>

<p class="font-weight-light">Le système d'interaction permanente est <i><b><?php echo shell_exec("cd /var/www/html/scripts/permanent && cat running_state"); ?></b></i>, <span class="btn btn-primary" onclick="alert('Cette fonction n\'est pas encore disponible, une fois activée, le micro par défaut du serveur écoutera en permanence un HOTWORD défini et une fois entendu le système écoutera les ordres à exécuter.');">je veux l'activer</span>

<p class="font-weight-light">Le retour vocal (<span class="btn btn-primary" onclick="alert('Lors d\'une intéraction utilisant le microphone du serveur, ce dernier aura la possiblité de réagir vocalement. Vous pouvez modifier ces intéractions en modifiant les fichiers audio présents dans /var/www/html/scripts/audio/...');">?</span>) est <?php $state = shell_exec("cat /var/www/html/scripts/audioState");  if(strpos($state,"ACTIVE") !== false){echo "<i><b>ACTIVE, </b></i><span class=\"btn btn-primary\" onclick=\"window.location.replace('sound.php?audio=no');\">je veux le désactiver</span>";}else{echo '<i><b>DESACTIVE, </b></i><span class="btn btn-primary" onclick="window.location.replace(\'sound.php?audio=ACTIVE\');">je veux l\'activer</span>';}?></p>

<div class="card" style="width: 70%;">
  <div class="card-body">
    <p>Chemin vers l'API domotique : <span class="btn btn-primary" onclick="alert('Lors de la détéction d\'un ordre, un appel sera fait à cette adresse suivi de l\'ID configuré dans l\'action.');">?</span></p>
	<p><?php echo $db['homeapi']; ?></p> 
  </div>
</div>

<div class="card" style="width: 70%;">
  <div class="card-body">
    <p>Ordres: </p>
     <?php
	$ordres = array_keys($db['ordre']);
	foreach($ordres as $ordre)
	{
		$ordre_lieu_nom = array_keys($db['ordre'][$ordre]);
		echo '['.$ordre.'] est composé de :</br>';
		$i = 0;
		foreach ($db['ordre'][$ordre] as $lieu)
		{
			echo '('.$ordre_lieu_nom[$i].') : ID= '.$lieu.'</br>'; $i++;
		}
	}
	?>
  </div>
</div>


<div class="card" style="width: 70%;">
  <div class="card-body">
    <p>Politesse :</p>

<?php


$politesses = $db['politesse'];
$politesses_key = array_keys($politesses);

foreach($politesses_key as $key)
{
	echo '<p>['.$key.'] déclenche la réponse ['.$politesses[$key].']</p>';
}

?>        
 
  </div>
</div>


</center>
