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
   
  // Vérification de la récupération des varialbes
    if (isset($_POST['ETP']) && (isset($_POST['KM'])) && (isset($_POST['NUI'])) && (isset($_POST['REP']))){
            $etp=$_POST['ETP'];
            $km=$_POST['KM'];
            $nui=$_POST['NUI'];
            $rep=$_POST['REP'];
            $testVariablesEnvoyees = true;
  }
  
    // Vérification du type des variables envoyées.
    if((is_numeric($_POST['ETP']))&& (is_numeric($_POST['KM'])) && (is_numeric($_POST['NUI'])) && (is_numeric($_POST['REP']))){
            $testTypes = true;
    }
  
  

    try {
            connecterServeurBDModifForfaits();
            $bdd = connecterServeurBDModifForfaits();
    } 
    catch ( Exception $e ) {
	echo "Connection à MySQL impossible : ", $e->getMessage();
	die();
    }

  if (($testVariablesEnvoyees) == true && ($testTypes == true)){
        $bdd->exec('UPDATE fraisforfait SET montant="'.$etp.'" WHERE id="ETP"');
        $bdd->exec('UPDATE fraisforfait SET montant="'.$km.'" WHERE id="KM"');
        $bdd->exec('UPDATE fraisforfait SET montant="'.$nui.'" WHERE id="NUI"');
        $bdd->exec('UPDATE fraisforfait SET montant="'.$rep.'" WHERE id="REP"');
        ?><script>alert("Les forfaits ont bien été modifiés")</script><?php
  }

?>
<meta http-equiv="refresh" content="0.1;http://localhost/gsbTeamA/cModificationDesForfait.php" />