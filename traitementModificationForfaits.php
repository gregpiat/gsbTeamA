<?php
/** 
 * Script de contrôle et d'affichage du cas d'utilisation "Consulter une fiche de frais"
 * @package default
 * @todo  RAS
 */
  $repInclude = './include/';
  require($repInclude . "_init.inc.php");
  

  require($repInclude . "_entete.inc.html");
  require($repInclude . "_sommaire.inc.php");
  $etp=htmlspecialchars($_POST['ETP']);
  $km=htmlspecialchars($_POST['KM']);
  $nui=htmlspecialchars($_POST['NUI']);
  $rep=htmlspecialchars($_POST['REP']);
  
  //VERIFIER
  //LES TYPES
  // DES DONNEES
  // PASSEES
  // EN POST !
  

	try {
	  $dns = 'mysql:host=localhost;dbname=gsb_frais';
	  $utilisateur = 'root';
	  $motDePasse = 'root';
 
	// Options de connection
		$options = array(
		PDO::MYSQL_ATTR_INIT_COMMAND    => "SET NAMES utf8"
	);

	// Initialisation de la connection
	$bdd = new PDO( $dns, $utilisateur, $motDePasse, $options );
	} catch ( Exception $e ) {
	echo "Connection à MySQL impossible : ", $e->getMessage();
	die();
}

  
  $bdd->exec('UPDATE fraisforfait SET montant="'.$etp.'" WHERE id="ETP"');
  $bdd->exec('UPDATE fraisforfait SET montant="'.$km.'" WHERE id="KM"');
  $bdd->exec('UPDATE fraisforfait SET montant="'.$nui.'" WHERE id="NUI"');
  $bdd->exec('UPDATE fraisforfait SET montant="'.$rep.'" WHERE id="REP"');


?>
<script>alert("Les forfaits ont bien été modifiés")</script>
<meta http-equiv="refresh" content="0.1;http://172.17.100.160/cModificationDesForfait.php" />