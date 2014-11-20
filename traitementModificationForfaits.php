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
    if (isset($_POST['ETP']) && (isset($_POST['KM'])) && (isset($_POST['NUI'])) && (isset($_POST['REP']))){
            $etp=$_POST['ETP'];
            $km=$_POST['KM'];
            $nui=$_POST['NUI'];
            $rep=$_POST['REP'];
            $testVariablesEnvoyees = true;
  }
  
    // Vérification du type et de la valeur des variables envoyées.

	
	if (verifierTypeDesVariables($_POST['ETP'], $_POST['KM'], $_POST['NUI'], $_POST['REP']) == true){
		$testTypes = true;
	}
	else{
		?><script>alert("Un forfait doit-être une valeur numérique !")</script><?php
	}
	
	if ($testTypes == true){
		if(verifierValeurDesVariables($_POST['ETP'], $_POST['KM'], $_POST['NUI'], $_POST['REP']) == true){
			$testPos = true;
		}
		else{
			?><script>alert("Un forfait doit-être positif !")</script><?php
		}
	}
	
	
	
	
	
	
	

    try {
            connecterServeurBDModifForfaits();
            $bdd = connecterServeurBDModifForfaits();
    } 
    catch ( Exception $e ) {
	echo "Connection à MySQL impossible : ", $e->getMessage();
	die();
    }

  if (($testVariablesEnvoyees) == true && ($testTypes == true) && ($testPos == true)){
        $bdd->exec('UPDATE fraisforfait SET montant="'.$etp.'" WHERE id="ETP"');
        $bdd->exec('UPDATE fraisforfait SET montant="'.$km.'" WHERE id="KM"');
        $bdd->exec('UPDATE fraisforfait SET montant="'.$nui.'" WHERE id="NUI"');
        $bdd->exec('UPDATE fraisforfait SET montant="'.$rep.'" WHERE id="REP"');
        ?><script>alert("Les forfaits ont bien été modifiés")</script><?php
  }

?>
<meta http-equiv="refresh" content="0.1;http://localhost/gsbTeamA/cModificationDesForfait.php" />