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
          $newSaisi = lireDonneePost("newSaisi","");
	  $txtadresse = lireDonneePost("txtadresse","");
	  $txtMDP = lireDonneePost("txtMDP","");
          $txtnom = lireDonneePost("txtnom","");
          $txtprenom = lireDonneePost("txtprenom","");
          $txtville = lireDonneePost("txtville","");
          $txtcp = lireDonneePost("txtcp","");
          $txtlogin = lireDonneePost("txtlogin","");
          $txtDateEmbauche = lireDonneePost("txtDateEmbauche","");
          $lstResponsable = lireDonneePost("lstResponsable","");
          $txtID = lireDonneePost("txtID","");
	  setcookie ("id","$idSaisi");
	?>
		<div id="contenu">
		  <h2>Modification des informations et Ajout d'un nouveaux visiteur</h2>
                  <button style="width: 150px;" onclick="masquer_div('creerVisiteur');" />Ajout d'un visiteur</button>
                  <button style="width: 200px;" onclick="masquer_div('modificationVisiteur');" />Modification des informations</button>
                  <div>
                    <fieldset id="modificationVisiteur" style="display:none;">
                    <legend><h3>Selection d'un visiteur</h3></legend>
                    <form action="" method="post">
                    <div class="corpsForm">
                            <input type="hidden" name="etape" value="validerConsult" />
                    <p>
                          <label for="lstVisiteur">Visiteur: </label>
                          <select id="lstVisiteur" name="lstVisiteur" title="Sélectionnez le nom du visiteur">
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
                  </div>
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
                
                  <fieldset name="modificationVisiteur" style="display:block;">
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
        ?>
                	
		<div>
                  <fieldset id="creerVisiteur" style="display:none;">
                      <legend><h3>Ajout d'un nouveaux visiteur</h3></legend>
                      <div class="corpsForm">
                        <form action="" method="post">
                            <input type="hidden" name="newSaisi" value="saisiNewVisiteur" />
                        <label for="txtnom">Nom : </label>
                        <input type="text" id="txtnom" name="txtnom" size="15" maxlength="30" required="required"><br><br>
                          <label for="txtprenom">Prénom : </label>
                          <input type="text" id="txtprenom" name="txtprenom" size="15" maxlength="30" required="required"><br><br>
                          <label for="txtlogin">Login : </label>
                          <input type="text" id="txtlogin" name="txtlogin" size="15" maxlength="30" required="required"><br><br>
                          <label for="txtville">Ville : </label>
                          <input type="text" id="txtville" name="txtville" size="15" maxlength="30 required="required""><br><br>
                          <label for="txtcp">CP : </label>
                          <input type="text" id="txtcp" name="txtcp" size="15" maxlength="30" required="required"><br><br>
                          <label for="txtadresse">Adresse : </label>
                          <input type="text" id="txtadresse" name="txtadresse" size="15" maxlength="30" required="required"><br><br>
                          <label for="txtMDP">Mdp : </label>
                          <input type="text" id="txtMDP" name="txtMDP" size="15" maxlength="30" required="required"><br><br>
                          <label for="txtMDP">ID : </label>
                          <input type="text" id="txtID" name="txtID" size="15" maxlength="30" required="required"><br><br>
                          <label for="lstResponsable">Responsable: </label>
                          <select id="lstResponsable" name="lstResponsable" title="Sélectionnez un responsable" required="required">
                              <option value="c03">Michel Jean</option>
                              <option value="c04">Martin Jacques</option>
                          </select><br><br>
                          <label for="txtDateEmbauche">Date d'embauche : </label>
                          <input type="text" id="txtDateEmbauche" name="txtDateEmbauche" size="15" maxlength="30" placeholder="ex:année-mois-jours"required="required"><br><br>
                            <input id="okSaisiVisiteur" type="submit" value="Valider" size="20"
					   title="Valide les données ajouté" />
                        </form>
                         </div>
                    </fieldset>
                </div>
		  <?php      
	if ( $newSaisi == "saisiNewVisiteur" ) {
		if ( nbErreurs($tabErreurs) > 0 ) {
			echo toStringErreurs($tabErreurs) ;
		}
		else {
                    $valideSaisi = true;
                    $reqNewSaisi = detailAllVisiteur();
                    while ($reqUser = mysql_fetch_array($reqNewSaisi)) {
                      $id = $reqUser['id'];
                      $nom = $reqUser['nom'];
                      $login =$reqUser['login'];
                      if(($id == $txtID) || ($nom == $txtnom) || ($login == $txtlogin)){
                          $valideSaisi = false;
                      }
                    }
                    if($valideSaisi==true){
                        AjoutDunNouveauxVisiteur($txtID,$txtnom,$txtprenom,$txtlogin,$txtMDP,$txtadresse,$txtcp,$txtville,$txtDateEmbauche,$lstResponsable);
                        echo "Le nouvelle utilisateur à était ajouté";
                    }
                    else
                    {
                        echo "<strong>La saisi d'un nom, id ou login existe déjà, changer les champs !</strong>";
                        ?> <script>alert("La saisi d'un nom, id ou login existe déjà, changer les champs !");</script><?php
                    }
                }
        }
?>
<script>
		function masquer_div(id)
		{
		  if (document.getElementById(id).style.display == 'none') {
                           if(id != document.getElementById("creerVisiteur") && document.getElementById("creerVisiteur").style.display == 'block')
                           {
                               document.getElementById("creerVisiteur").style.display = 'none';
                           }
                           else
                           {
                                if(id != document.getElementById("modificationVisiteur") && document.getElementById("modificationVisiteur").style.display == 'block')
                                {
                                     document.getElementById("modificationVisiteur").style.display = 'none';
                                }
                            }
                            document.getElementById(id).style.display = 'block';
		  }
		  else {
			   document.getElementById(id).style.display = 'none';  
		  }
                  if(	document.getElementsByName("modificationVisiteur").style.display == 'block'){
                      	document.getElementsByName("modificationVisiteur").style.display = 'none';
                  }
                  else{
                      	document.getElementsByName("modificationVisiteur").style.display = 'none';
                  }
		}
</script>
<?php
}

else{
	echo "Vous devez vous identifier";
}      
  //require($repInclude . "_pied.inc.html");
  require($repInclude . "_fin.inc.php");
?> 