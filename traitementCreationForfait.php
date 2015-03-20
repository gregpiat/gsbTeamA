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
  
  
  
   $testTypes = false;
   $testVariablesEnvoyees = false;
   $testPos = false;
   
  // Vérification de la récupération des varialbes
    if (isset($_POST['nomNF']) && (isset($_POST['valeurNF']))){
            $nom=$_POST['nomNF'];
            $valeur=$_POST['valeurNF'];
            $testVariablesEnvoyees = true;
	}
  
    // Vérification du type et de la valeur des variables envoyées.
	if (verifierTypeDesVariablesNF($_POST['nomNF'], $_POST['valeurNF'])== true){
		$testTypes = true;
	}
	else{
		?><script>alert("Un forfait doit-être une valeur numérique !")</script><?php
	}
	
	if ($testTypes == true){
		if(verifierValeurDesVariablesNF($_POST['valeurNF'])== true){
			$testPos = true;
		}
		else{
			?><script>alert("Un forfait doit-être positif !")</script><?php
		}
	}
	
	
	// Connection à la base de données
    try {
            connecterServeurBDModifForfaits();
            $bdd = connecterServeurBDModifForfaits();
    } 
    catch ( Exception $e ) {
	echo "Connection à MySQL impossible : ", $e->getMessage();
	die();
    }
	
	$newId = substr($nom, 0, 3);
	$newId = strtoupper($newId);
	

	// Si les variables ont été reçues, sont de type numérique et sont de valeur positives,
	// on les insert dans la base de données.
  if (($testVariablesEnvoyees) == true && ($testTypes == true) && ($testPos == true)){	
        try{
			$bdd->exec("INSERT INTO `fraisforfait`(`id`, `libelle`, `montant`) VALUES ('".$newId."','".$nom."',".$valeur.")");
			?><script>alert("Le forfait à bien été créé")</script><?php
		}
		catch(Exception $e){
			?><script>alert('<?php echo $e->getMessage()?>')</script><?php
		}
		
        
  }

?>
<meta http-equiv="refresh" content="0.1;http://localhost/gsbTeamA/cModificationDesForfait.php" />