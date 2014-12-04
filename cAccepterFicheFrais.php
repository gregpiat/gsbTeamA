<?php
/** 
 * Script de contrôle et d'affichage du cas d'utilisation "Consulter une fiche de frais"
 * @package default
 * @todo  RAS
 */
  $repInclude = './include/';
  require($repInclude . "_init.inc.php");

  // page inaccessible si utilisateur non connectée en tant que comptable 
  if ( ! estVisiteurConnecte() ) {
      header("Location: cSeConnecter.php");
  }
  
  $VerificationComptable = connectComptable(obtenirIdUserConnecte());
  if($VerificationComptable ==null ){
  require($repInclude . "_entete.inc.html");
  require($repInclude . "_sommaire.inc.php");
	  
  // acquisition des données entrées, ici le visiteur, le mois et l'étape du traitement
  $idSaisi=lireDonneePost("lstVisiteur", "");
  $etape=lireDonneePost("etape","");
  $moisSaisi=lireDonneePost("lstMois", "");
  
  if ($etape != "demanderConsult" && $etape != "validerConsult") {
     // si autre valeur, on considÃ¨re que c'est le dÃ©but du traitement
     $etape = "demanderConsult";        
  } 
  if ($etape == "validerConsult") { // l'utilisateur valide ses nouvelles donnÃ©es
     // vÃ©rification de l'existence de la fiche de frais pour le mois demandÃ©
     $existeFicheFrais = existeFicheFrais($idConnexion, $moisSaisi, $idSaisi);
     // si elle n'existe pas, on la crÃ©e avec les Ã©lets frais forfaitisÃ©s Ã  0
  if ( !$existeFicheFrais ) {
     ajouterErreur($tabErreurs, "Le mois demandé est invalide");
  }
  else {
     // rÃ©cupÃ©ration des donnÃ©es sur la fiche de frais demandÃ©e
     $tabFicheFrais = obtenirDetailFicheFrais($idConnexion, $moisSaisi, $idSaisi);
     }
  }                              

?>

                <div id="contenu">
		  <h2>Choisissez le visiteur et le mois concernés par la validation</h2>
		  <form action="" method="post">
		  <div class="corpsForm">
			  <input type="hidden" name="etape" value="validerConsult" />
		  <p>
                       <h3>Selection d'un visiteur</h3>
			<label for="lstVisiteur">Visiteur : </label>
			<select id="lstVisiteur" name="lstVisiteur" title="Sélectionnez le visiteur souhaité pour la validation">
				<?php
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
                        <input id="ok" type="submit" value="Valider" size="20"
				   title="Valider le visiteur selectionne" />
			<input id="annuler" type="reset" value="Annuler" size="20" />
                        <h3>Selection d'un mois</h3>
                        <label for="lstMois">Mois : </label>
			<select id="lstVisiteur" name="lstMois" title="Sélectionnez le mois souhaité pour la validation">
				<?php
					  $req = obtenirReqMoisFicheFrais($idSaisi);
                                          $idJeuMois = mysql_query($req, $idConnexion);
                                          $lgMois = mysql_fetch_assoc($idJeuMois);
                                          while ( is_array($lgMois) ) {
                                            $mois = $lgMois["mois"];
                                            $noMois = intval(substr($mois, 4, 2));
                                            $annee = intval(substr($mois, 0, 4));
                                ?>    
                                <option value="<?php echo $mois; ?>"<?php if ($moisSaisi == $mois) { ?> selected="selected"<?php } ?>><?php echo obtenirLibelleMois($noMois) . " " . $annee; ?></option>
                                <?php
                                    $lgMois = mysql_fetch_assoc($idJeuMois);        
                                          }
                                    mysql_free_result($idJeuMois);
                                ?>
			</select>
		  </p>
		  </div>
		  <div class="piedForm">
		  <p>
			<input id="ok" type="submit" value="Valider" size="20"
				   title="Valider le visiteur selectionne" />
			<input id="annuler" type="reset" value="Annuler" size="20" />
		  </p> 
		  </div>
			
		  </form>
                  <?php      

  if ($etape != "demanderConsult" && $etape != "validerConsult") {
      // si autre valeur, on considÃ¨re que c'est le dÃ©but du traitement
      $etape = "demanderConsult";        
  } 
  if ($etape == "validerConsult") { // l'utilisateur valide ses nouvelles donnÃ©es
                
      // vÃ©rification de l'existence de la fiche de frais pour le mois demandÃ©
      $existeFicheFrais = existeFicheFrais($idConnexion, $moisSaisi, $idSaisi);
      // si elle n'existe pas, on la crÃ©e avec les Ã©lets frais forfaitisÃ©s Ã  0
      if ( !$existeFicheFrais ) {
          ajouterErreur($tabErreurs, "Le mois demandé est invalide");
      }
      else {
          // rÃ©cupÃ©ration des donnÃ©es sur la fiche de frais demandÃ©e
          $tabFicheFrais = obtenirDetailFicheFrais($idConnexion, $moisSaisi, $idSaisi);
      }
  }                             

?>
<?php
        if(isset($_POST['lstVisiteur'])) {
            $lstVisiteur=$_POST['lstVisiteur'];
        }
        else {
            $listeVisiteur=-1;
        }


    

// demande et affichage des différents éléments (forfaitisés et non forfaitisés)
// de la fiche de frais demandée, uniquement si pas d'erreur détectée au contrôle
    if ( $etape == "validerConsult" ) {
        if ( nbErreurs($tabErreurs) > 0 ) {
            echo toStringErreurs($tabErreurs) ;
        }
        else {
?>
    <h3>Fiche de frais du mois de <?php echo obtenirLibelleMois(intval(substr($moisSaisi,4,2))) . " " . substr($moisSaisi,0,4); ?> : 
    <em><?php echo $tabFicheFrais["libelleEtat"]; ?> </em>
    depuis le <em><?php echo $tabFicheFrais["dateModif"]; ?></em></h3>
    <div class="encadre">
    <p>Montant validé : <?php echo $tabFicheFrais["montantValide"] ;
        ?>              
    </p>
<?php          
            // demande de la requÃªte pour obtenir la liste des Ã©lÃ©ments 
            // forfaitisÃ©s du visiteur connectÃ© pour le mois demandÃ©
            $req = obtenirReqEltsForfaitFicheFrais($moisSaisi, $idSaisi);
            $idJeuEltsFraisForfait = mysql_query($req, $idConnexion);
            echo mysql_error($idConnexion);
            $lgEltForfait = mysql_fetch_assoc($idJeuEltsFraisForfait);
            // parcours des frais forfaitisÃ©s du visiteur connectÃ©
            // le stockage intermÃ©diaire dans un tableau est nÃ©cessaire
            // car chacune des lignes du jeu d'enregistrements doit Ãªtre doit Ãªtre
            // affichÃ©e au sein d'une colonne du tableau HTML
            $tabEltsFraisForfait = array();
            while ( is_array($lgEltForfait) ) {
                $tabEltsFraisForfait[$lgEltForfait["libelle"]] = $lgEltForfait["quantite"];
                $lgEltForfait = mysql_fetch_assoc($idJeuEltsFraisForfait);
            }
            mysql_free_result($idJeuEltsFraisForfait);
            ?>
  	<table class="listeLegere">
  	   <caption>Quantités des éléments forfaitisés</caption>
        <tr>
            <?php
            // premier parcours du tableau des frais forfaitisÃ©s du visiteur connectÃ©
            // pour afficher la ligne des libellÃ©s des frais forfaitisÃ©s
            foreach ( $tabEltsFraisForfait as $unLibelle => $uneQuantite ) {
            ?>
                <th><?php echo $unLibelle ; ?></th>
            <?php
            }
            ?>
        </tr>
        <tr>
            <?php
            // second parcours du tableau des frais forfaitisÃ©s du visiteur connectÃ©
            // pour afficher la ligne des quantitÃ©s des frais forfaitisÃ©s
            foreach ( $tabEltsFraisForfait as $unLibelle => $uneQuantite ) {
            ?>
                <td class="qteForfait"><?php echo $uneQuantite ; ?></td>
            <?php
            }
            ?>
        </tr>
    </table>
  	<table class="listeLegere">
  	   <caption>Descriptif des éléments hors forfait - <?php echo $tabFicheFrais["nbJustificatifs"]; ?> justificatifs reçus -
       </caption>
             <tr>
                <th class="date">Date</th>
                <th class="libelle">Libellé</th>
                <th class="montant">Montant</th>                
             </tr>
<?php          
            // demande de la requÃªte pour obtenir la liste des Ã©lÃ©ments hors
            // forfait du visiteur connectÃ© pour le mois demandÃ©
            $req = obtenirReqEltsHorsForfaitFicheFrais($moisSaisi, $idSaisi);
            $idJeuEltsHorsForfait = mysql_query($req, $idConnexion);
            $lgEltHorsForfait = mysql_fetch_assoc($idJeuEltsHorsForfait);
            
            // parcours des Ã©lÃ©ments hors forfait 
            while ( is_array($lgEltHorsForfait) ) {
            ?>
                <tr>
                   <td><?php echo $lgEltHorsForfait["date"] ; ?></td>
                   <td><?php echo filtrerChainePourNavig($lgEltHorsForfait["libelle"]) ; ?></td>
                   <td><?php echo $lgEltHorsForfait["montant"] ; ?></td>
                </tr>
            <?php
                $lgEltHorsForfait = mysql_fetch_assoc($idJeuEltsHorsForfait);
            }
            mysql_free_result($idJeuEltsHorsForfait);
  ?>
    </table>
  </div>
<?php
        }
    }
  }
?>    
  </div>
<?php        
  require($repInclude . "_pied.inc.html");
  require($repInclude . "_fin.inc.php");
?> 
 
