<?php
/** 
 * Contient la division pour le sommaire, sujet à des variations suivant la 
 * connexion ou non d'un utilisateur, et dans l'avenir, suivant le type de cet utilisateur 
 * @todo  RAS
 */

?>
    <!-- Division pour le sommaire -->
    <div id="menuGauche">
     <div id="infosUtil">
    <?php      
      if (estVisiteurConnecte() ) {
          $idUser = obtenirIdUserConnecte() ;
          $lgUser = obtenirDetailVisiteur($idConnexion, $idUser);
          $nom = $lgUser['nom'];
          $prenom = $lgUser['prenom'];
		  $comptableRef = $lgUser['comptableReferent'];
    ?>
        <h2>
    <?php  
            echo $nom . " " . $prenom ;
    ?>
        </h2>
		<?php
		 if($comptableRef == null){
        echo "<h3>Comptable</h3>";
		}
		else
		{
		echo "<h3>Visiteur medical</h3>";
		}
		?>
    <?php
       }
    ?>  
      </div>  
<?php      
  if (estVisiteurConnecte() ) {
?>
        <ul id="menuList">
           <li class="smenu">
               <br><a href="cAccueil.php" title="Page d'accueil">Accueil</a><br>
           </li>
           <li class="smenu">
              <a href="cSeDeconnecter.php" title="Se déconnecter">Se deconnecter</a><br>
           </li>
           <li class="smenu">
               <a href="cSaisieFicheFrais.php" title="Saisie fiche de frais du mois courant">Saisie fiche de frais</a><br>
           </li>
           <li class="smenu">
              <a href="cConsultFichesFrais.php" title="Consultation de mes fiches de frais">Mes fiches de frais</a><br>
           </li>
	   <?php 
	       if($comptableRef == null){
	   ?>
           <li class="smenu">
              <a href="cAccepterFicheFrais.php" title="Fiches de frais valide">Valider fiches de frais</a><br>
           </li>
	   <li class="smenu">
              <a href="cModificationDesForfait.php" title="Modification des Forfaits">Modification des Forfaits</a><br>
           </li>
	   <li class="smenu">
              <a href="cModificationDesInformation.php" title="Modification des Informations">Ajout,modification des visiteurs</a><br>
           </li>
           <?php
             }
	   ?>
         </ul>
        <?php
          // affichage des éventuelles erreurs déjà détectées
          if ( nbErreurs($tabErreurs) > 0 ) {
              echo toStringErreurs($tabErreurs) ;
          }
  }
        ?>
    </div>
    