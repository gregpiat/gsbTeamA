<?php
  
  $repInclude = './include/';
  require($repInclude . "_init.inc.php");
  
  // Si nous sommes le 10 du mois, nous appelons modifierEtat qui cloture
  // les fiches de frais qui ne le sont pas encore du mois en court
  	$today = date("j"); 
	if($today == 10) {
		$mois = date("Ym");
		modifierEtat($mois, connecterServeurBD());
	}

	
	
// Ce script sera appelé régulièrement grâce au crontab
// On doit faire crontab -e sur la debian
// On doit ajouter la ligne suivante au fichier : 23 55 10 * * php -f /var/www/appliFrais/scriptClotureDesFiches.php
?>

