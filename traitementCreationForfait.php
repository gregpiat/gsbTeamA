<?php
/** 
 * Script de contr�le et d'affichage du cas d'utilisation "Consulter une fiche de frais"
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
   
  // V�rification de la r�cup�ration des varialbes
    if (isset($_POST['nomNF']) && (isset($_POST['valeurNF']))){
            $nom=$_POST['nomNF'];
            $valeur=$_POST['valeurNF'];
            $testVariablesEnvoyees = true;
	}
  
    // V�rification du type et de la valeur des variables envoy�es.
	if (verifierTypeDesVariablesNF($_POST['nomNF'], $_POST['valeurNF'])== true){
		$testTypes = true;
	}
	else{
		?><script>alert("Un forfait doit-�tre une valeur num�rique !")</script><?php
	}
	
	if ($testTypes == true){
		if(verifierValeurDesVariablesNF($_POST['valeurNF'])== true){
			$testPos = true;
		}
		else{
			?><script>alert("Un forfait doit-�tre positif !")</script><?php
		}
	}
	
	
	// Connection � la base de donn�es
    try {
            connecterServeurBDModifForfaits();
            $bdd = connecterServeurBDModifForfaits();
    } 
    catch ( Exception $e ) {
	echo "Connection � MySQL impossible : ", $e->getMessage();
	die();
    }
	
	$newId = substr($nom, 0, 3);
	$newId = strtoupper($newId);
	

	// Si les variables ont �t� re�ues, sont de type num�rique et sont de valeur positives,
	// on les insert dans la base de donn�es.
  if (($testVariablesEnvoyees) == true && ($testTypes == true) && ($testPos == true)){	
        try{
			$bdd->exec("INSERT INTO `fraisforfait`(`id`, `libelle`, `montant`) VALUES ('".$newId."','".$nom."',".$valeur.")");
			?><script>alert("Le forfait � bien �t� cr��")</script><?php
		}
		catch(Exception $e){
			?><script>alert('<?php echo $e->getMessage()?>')</script><?php
		}
		
        
  }

?>
<meta http-equiv="refresh" content="0.1;http://localhost/gsbTeamA/cModificationDesForfait.php" />