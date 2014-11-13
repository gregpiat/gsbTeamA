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
      if  (connectComptable() ) {
          echo 'Vous devez être connecté en temps que comptable pour accéder à cette page';
      }
  }
  require($repInclude . "_entete.inc.html");
  require($repInclude . "_sommaire.inc.php");
  
  
  
  
  // acquisition des donnÃ©es entrÃ©es, ici le numÃ©ro de mois et l'Ã©tape du traitement
  /*$moisSaisi=lireDonneePost("lstMois", "");
  $etape=lireDonneePost("etape",""); 

  if ($etape != "demanderConsult" && $etape != "validerConsult") {
      // si autre valeur, on considÃ¨re que c'est le dÃ©but du traitement
      $etape = "demanderConsult";        
  } 
  if ($etape == "validerConsult") { // l'utilisateur valide ses nouvelles donnÃ©es
                
      // vÃ©rification de l'existence de la fiche de frais pour le mois demandÃ©
      $existeFicheFrais = existeFicheFrais($idConnexion, $moisSaisi, obtenirIdUserConnecte());
      // si elle n'existe pas, on la crÃ©e avec les Ã©lets frais forfaitisÃ©s Ã  0
      if ( !$existeFicheFrais ) {
          ajouterErreur($tabErreurs, "Le mois demand� est invalide");
      }
      else {
          // rÃ©cupÃ©ration des donnÃ©es sur la fiche de frais demandÃ©e
          $tabFicheFrais = obtenirDetailFicheFrais($idConnexion, $moisSaisi, obtenirIdUserConnecte());
      }
  } */                                 
?>

<?php
        if(isset($_POST['lstVisiteur'])) {
            $lstVisiteur=$_POST['lstVisiteur'];
        }
        else {
            $listeVisiteur=-1;
        }
?>

  <!-- Division principale -->
  <div id="contenu">
      <h2>Valider fiche de rais</h2>
      <h3>Liste des visiteur : </h3>
      <form action="" method="post">
          <div class="corpsForm">
              <input type="hidden" name
      <form name ="form1" action="" method="post">
          
          <select name="lstVisiteur" onchange="fom1.submit();">
              <label for="lstVisiteur">Visiteur : </label>
              <option value = -1>-- Liste des utilisateur --</option>
            <?php
                /*// on propose tous les mois pour lesquels le visiteur a une fiche de frais
                $req = obtenirReqMoisFicheFrais(obtenirIdUserConnecte());
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
                mysql_free_result($idJeuMois);*/
            // acquisition de la liste de visiteurs
              $listeVisiteur = listeVisiteur();
              while ($donnees = mysql_fetch_array($listeVisiteur)) {
?>              
              <option value='<?php echo $donnees["nom"];
                                   echo $donnees["prenom"];
                      if($listeVisiteur == $donnees['nom'] and $listeVisiteur == $donnees['prenom']) { echo "selected"; }
                      ?>'>
                      <?php echo $donnees['nom'];
                            echo $donnees['prenom'];
                      ?>
              </option>
<?php
              }
?>
        </select>
      </p>
      </div>
      <div class="piedForm">
      <p>
        <input id="ok" type="submit" value="Valider" size="20"
               title="Demandez Ã  consulter cette fiche de frais" />
        <input id="annuler" type="reset" value="Effacer" size="20" />
      </p> 
      </div>
        
      </form>
<?php      

// demande et affichage des diffÃ©rents Ã©lÃ©ments (forfaitisÃ©s et non forfaitisÃ©s)
// de la fiche de frais demandÃ©e, uniquement si pas d'erreur dÃ©tectÃ© au contrÃ´le
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
    <p>Montant validÃ© : <?php echo $tabFicheFrais["montantValide"] ;
        ?>              
    </p>
<?php          
            // demande de la requÃªte pour obtenir la liste des Ã©lÃ©ments 
            // forfaitisÃ©s du visiteur connectÃ© pour le mois demandÃ©
            $req = obtenirReqEltsForfaitFicheFrais($moisSaisi, obtenirIdUserConnecte());
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
  	   <caption>QuantitÃ©s des Ã©lÃ©ments forfaitisÃ©s</caption>
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
  	   <caption>Descriptif des Ã©lÃ©ments hors forfait - <?php echo $tabFicheFrais["nbJustificatifs"]; ?> justificatifs reÃ§us -
       </caption>
             <tr>
                <th class="date">Date</th>
                <th class="libelle">LibellÃ©</th>
                <th class="montant">Montant</th>                
             </tr>
<?php          
            // demande de la requÃªte pour obtenir la liste des Ã©lÃ©ments hors
            // forfait du visiteur connectÃ© pour le mois demandÃ©
            $req = obtenirReqEltsHorsForfaitFicheFrais($moisSaisi, obtenirIdUserConnecte());
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
?>    
  </div>
<?php        
  require($repInclude . "_pied.inc.html");
  require($repInclude . "_fin.inc.php");
?> 

