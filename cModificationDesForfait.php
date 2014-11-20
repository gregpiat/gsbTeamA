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
  
 

	try {
	  $dns = 'mysql:host=localhost;dbname=gsb_frais';
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
	?><form method="post" action="traitementModificationForfaits.php"><?php
while ($donnees = $reponse->fetch())
{
?>
<br>
<p>
    <strong>FORFAIT</strong> : <?php echo $donnees['libelle']; ?><br />
</p>
 
<p>
     <input type="text" name=<?php echo $donnees['id'];?> value =<?php echo $donnees['montant'];?>>
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
