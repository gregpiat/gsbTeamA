<?php
/** 
 * Script de contrôle et d'affichage du cas d'utilisation "Consulter une fiche de frais"
 * @package default
 * @todo  RAS
 */
  $repInclude = './include/';
  require($repInclude . "_init.inc.php");

  // page inaccessible si visiteur non connecté
  if ( ! estVisiteurConnecte() ) {
      header("Location: cSeConnecter.php");  
	  
  }
  $VerificationComptable = connectComptable(obtenirIdUserConnecte());
  if($VerificationComptable ==null ){
	  require($repInclude . "_entete.inc.html");
	  require($repInclude . "_sommaire.inc.php");
	  
	  // acquisition des données entrées, ici le numéro de mois et l'étape du traitement
	  $idSaisi=lireDonneePost("lstVisiteur", "");
	  $etape=lireDonneePost("etape","");
	  $saisi = lireDonneePost("saisi","");
	  $txtadresse = lireDonneePost("txtadresse","");
	  $txtMDP = lireDonneePost("txtMDP","");
	  setcookie ("id","$idSaisi");


	?>
		<div id="contenu">
		  <h2>Modification des informations</h2>
		  <h3>Selection d'un visiteur</h3>
		  <form action="" method="post">
		  <div class="corpsForm">
			  <input type="hidden" name="etape" value="validerConsult" />
		  <p>
			<label for="lstVisiteur">Visiteur: </label>
			<select id="lstVisiteur" name="lstVisiteur" title="Sélectionnez le mois souhaité pour la fiche de frais">
				<?php
					// on propose tous les mois pour lesquels le visiteur a une fiche de frais
					  $detail = detailAllVisiteur();
					while ($lgUser = mysql_fetch_array($detail)) {
					  $id = $lgUser['id'];
					  $nom = $lgUser['nom'];
					  $prenom = $lgUser['prenom'];
				?>    
				<option value="<?php echo $lgUser['id']; ?>"<?php if ($idSaisi == $id) { ?> selected="selected"<?php } ?>><?php  echo $lgUser['nom']; echo "&nbsp;"; echo $lgUser['prenom']; ?></option>
				<?php
   
					}
				?>
			</select>
		  </p>
		  </div>
		  <div class="piedForm">
		  <p>
			<input id="ok" type="submit" value="Valider" size="20"
				   title="Demandez à consulter cette fiche de frais" />
			<input id="annuler" type="reset" value="Effacer" size="20" />
		  </p> 
		  </div>
			
		  </form>
		  <?php      

// demande et affichage des différents éléments (forfaitisés et non forfaitisés)
// de la fiche de frais demandée, uniquement si pas d'erreur détecté au contrôle
	if ( $etape == "validerConsult" ) {
		if ( nbErreurs($tabErreurs) > 0 ) {
			echo toStringErreurs($tabErreurs) ;
		}
		else {
			$req = recupModifVisiteur($idSaisi);
			while ($reqUser = mysql_fetch_array($req)) {
			  $adresse = $reqUser['adresse'];
			  $mdp = $reqUser['mdp'];
			}
	?>
		  <form action="" method="post">
			  <div class="corpsForm">
				  <input type="hidden" name="saisi" value="validerSaisi" />
			  <p> 
				<label for="txtadresse">Adresse : </label>
				<input type="text" id="txtadresse" name="txtadresse" size="15" maxlength="30" value="<?php echo $adresse; ?>"><br><br>
				<label for="txtMDP">Mdp : </label>
				<input type="text" id="txtMDP" name="txtMDP" size="15" maxlength="30" value="<?php echo $mdp; ?>">
			  </p>
			  </div>
			  <div class="piedForm">
			  <p>
				<input id="okSaisi" type="submit" value="Valider" size="20"
					   title="Demandez à consulter cette fiche de frais" />
				<input id="annuler" type="reset" value="Effacer" size="20" />
			  </p> 
			  </div>
		  </form>
	<?php
		}
	}
	if ( $saisi == "validerSaisi" ) {
		if ( nbErreurs($tabErreurs) > 0 ) {
			echo toStringErreurs($tabErreurs) ;

		}
		else {
			UpdateModifVisiteur($_COOKIE['id'],$txtadresse,$txtMDP);

			echo "Mise a jour effectué";
		}
	}
}

else{
	echo "Vous devez vous identifier";
}      
  require($repInclude . "_pied.inc.html");
  require($repInclude . "_fin.inc.php");
?> 