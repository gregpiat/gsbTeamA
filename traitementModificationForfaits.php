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
  
  
   $testTypes = true;
   $testVariablesEnvoyees = false;
   $testPos = true;
	
	$array = array_keys($_POST);
	foreach($array as $value){
		  if(!is_numeric($_POST[$value])){
			  $testTypes = false;
		  }
		  else{
			  if($_POST[$value] < 0){
				  $testPos = false;
			  }
		  }
	}
  
    // Vérification du type et de la valeur des variables envoyées.
	
	
	// Connection à la base de données
    try {
            connecterServeurBDModifForfaits();
            $bdd = connecterServeurBDModifForfaits();
    } 
    catch ( Exception $e ) {
	echo "Connection à MySQL impossible : ", $e->getMessage();
	die();
    }

	// Si les variables ont été reçues, sont de type numérique et sont de valeur positives,
	//on les insert dans la base de données.
   if ($testTypes == true && $testPos == true){
	 $array = array_keys($_POST);
	 foreach($array as $value){
		 $bdd->exec('UPDATE fraisforfait SET montant="'.$_POST[$value].'" WHERE id="'.$value.'"');
	 }
        ?><script>alert("Les forfaits ont bien été modifiés")</script><?php
   }
   else if ($testTypes == false){
	   ?><script>alert("Un forfait doit-être une valeur numérique !")</script><?php
   }
   else if($testPos == false){
	   ?><script>alert("Un forfait doit-être positif !")</script><?php
   }

?>
<meta http-equiv="refresh" content="0.1;http://172.17.21.23/cModificationDesForfait.php" />