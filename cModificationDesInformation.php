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
          $txtnom = lireDonneePost("txtnom","");
          $txtprenom = lireDonneePost("txtprenom","");
          $txtville = lireDonneePost("txtville","");
          $txtcp = lireDonneePost("txtcp","");
          $txtlogin = lireDonneePost("txtlogin","");
	  setcookie ("id","$idSaisi");




	?>
		<div id="contenu">
		  <h2>Modification des informations</h2>
                  <fieldset>
                  <legend><h3>Selection d'un visiteur</h3></legend>
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
				   title="Valide la selection" />
		  </p> 
		  </div>
			
		  </form>
                  </fieldset>
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
                      $nom = $reqUser['nom'];
                      $prenom = $reqUser['prenom'];
                      $login =$reqUser['login'];
                      $cp =$reqUser['cp'];
                      $ville=$reqUser['ville'];
                    }
	?>
                  <fieldset>
		  <form action="" method="post">
			  <div class="corpsForm">
				  <input type="hidden" name="saisi" value="validerSaisi" />
			  <p> 
				<label for="txtnom">Nom : </label>
				<input type="text" id="txtnom" name="txtnom" size="15" maxlength="30" value="<?php echo $nom; ?>"><br><br>
                                <label for="txtprenom">Prénom : </label>
				<input type="text" id="txtprenom" name="txtprenom" size="15" maxlength="30" value="<?php echo $prenom; ?>"><br><br>
                                <label for="txtlogin">Login : </label>
				<input type="text" id="txtlogin" name="txtlogin" size="15" maxlength="30" value="<?php echo $login; ?>"><br><br>
                                <label for="txtville">Ville : </label>
				<input type="text" id="txtville" name="txtville" size="15" maxlength="30" value="<?php echo $ville; ?>"><br><br>
                                <label for="txtcp">CP : </label>
				<input type="text" id="txtcp" name="txtcp" size="15" maxlength="30" value="<?php echo $cp; ?>"><br><br>
                                <label for="txtadresse">Adresse : </label>
				<input type="text" id="txtadresse" name="txtadresse" size="15" maxlength="30" value="<?php echo $adresse; ?>"><br><br>
				<label for="txtMDP">Mdp : </label>
				<input type="text" id="txtMDP" name="txtMDP" size="15" maxlength="30" value="<?php echo $mdp; ?>">
			  </p>
			  </div>
			  <div class="piedForm">
			  <p>
				<input id="okSaisi" type="submit" value="Valider" size="20"
					   title="Valide les données modifié" />
			  </p> 
			  </div>
		  </form>
                  </fieldset>
	<?php
		}
	}
	if ( $saisi == "validerSaisi" ) {
		if ( nbErreurs($tabErreurs) > 0 ) {
			echo toStringErreurs($tabErreurs) ;

		}
		else {
			UpdateModifVisiteur($_COOKIE['id'],$txtnom, $txtprenom, $txtlogin, $txtMDP, $txtadresse, $txtcp, $txtville);
                        $req = recupModifVisiteur($_COOKIE['id']);
                        echo "<strong>Les informations modifié</strong><br>";
			while ($reqUser = mysql_fetch_array($req)) {
			  echo "Adresse : ", $reqUser['adresse'];
                          echo "<br>";
			  echo "mdp : ",$reqUser['mdp'];
                          echo "<br>";
                          echo "Nom : ",$reqUser['nom'];
                          echo "<br>";
                          echo "Prenom : ",$reqUser['prenom'];
                          echo "<br>";
                          echo "Login : ",$reqUser['login'];
                          echo "<br>";
                          echo "CP : ",$reqUser['cp'];
                          echo "<br>";
                          echo "Ville : ",$reqUser['ville'];
                          echo "<br>";
			}
			echo "<strong>Mise a jour effectué</strong>";
                        ?>
                        <form action="" method="post">
                            <input type="hidden" name="etape" value="validerConsult" />
                            <input type="hidden" name="lstVisiteur" value="<?php echo $_COOKIE['id']; ?>" />
                            <input id='okSaisi' type='submit' value='Modifier' size='20'/>

                        </form>
                        <?php
		}
	}
}

else{
	echo "Vous devez vous identifier";
}      
  require($repInclude . "_pied.inc.html");
  require($repInclude . "_fin.inc.php");
?> 