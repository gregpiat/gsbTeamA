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
  require($repInclude . "_entete.inc.html");
  require($repInclude . "_sommaire.inc.php");
?>
<div id="contenu">
      <h2>Modification des forfaits </h2>
	  <p>Merci de n'entrer que des valeurs de type numerique, ou les modifications ne prendront pas effet.</p>
	  
<?php
			
	try {
	  $dns = 'mysql:host=localhost;dbname=gsb 2';
	  $utilisateur = 'root';
	  $motDePasse = 'root';
 
	// Options de connection
		$options = array(
		PDO::MYSQL_ATTR_INIT_COMMAND    => "SET NAMES utf8"
	);
 
	// Initialisation de la connection
	$bdd = new PDO( $dns, $utilisateur, $motDePasse, $options );
	} catch ( Exception $e ) {
	echo "Connection à MySQL impossible : ", $e->getMessage();
	die();
}
// Si tout va bien, on peut continuer

// On récupère tout le contenu de la table fraisForfait
 $reponse = $bdd->query('SELECT * from fraisforfait');

// On affiche chaque entrée une à une
	?>
		<div style="display:inline-block;">
			<fieldset><legend>Modification des forfaits</legend>
				<form method="post" action="traitementModificationForfaits.php"><?php
				while ($donnees = $reponse->fetch())
				{
				?>
				<br>
				<p>
					<strong>FORFAIT</strong> : <?php echo $donnees['libelle']; ?><br />
				</p>
				 
				<p>
					 <input type="number" name=<?php echo $donnees['id'];?> value =<?php echo $donnees['montant'];?> required="required" min="0" max="1000" step="any">
				</p>

				</p>
				<?php
				}
				?>
				<p>
					 <input type="submit" value="Modifier les forfaits" />
				 </p>
				</form>
				<br><br><br>
				
				
				<?php
				$reponse->closeCursor(); // Termine le traitement de la requête
				?>
					
			</fieldset>
			
		<div style="float:right;">
			<fieldset><legend>Creation d'un forfaits</legend>
				<form method="post" action="traitementCreationForfait.php">
					<strong>Nom</strong> :
					<input type="text" name="nomNF" required="required" maxlength="20">
					<strong>Valeur</strong> :
					<input type="number" name="valeurNF" required="required" min="0" max="1000" step="any">
					<input type="submit" value="Creer le forfait" />
				</form>
			</fieldset>
		</div>
	</div>
</div>
	

