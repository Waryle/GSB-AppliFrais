<?php
/**
 * Contrôleur de Connexion
 *
 * Contrôleur gérant les connexions : affiche la page de connexion si l'utilisateur n'est pas connecté, exécute les commandes requises
 * pour une demande de connexion.
 *
 *
 * @package   GSB-AppliFrais/controleurs
 * @author    Bazebimio Jaïrus, Bouvry Sophie, Ducrocq Maxime
 * @version 1 (Décembre 2016)
 * @copyright 2016 Bazebimio Jaïrus, Bouvry Sophie, Ducrocq Maxime
 */


if (! isset ( $_REQUEST ['action'] )) {
	$_REQUEST ['action'] = 'demandeConnexion';
}
$action = $_REQUEST ['action'];
switch ($action) {
	case 'demandeConnexion' :
		{
			include ("vues/v_connexion.php");
			break;
		}
	case 'valideConnexion' :
		{
			$login = $_REQUEST ['login'];
			$mdp = $_REQUEST ['mdp'];
			
			// Teste le type d'utilisateur et change la requête en fonction du type d'utilisateur pour atteindre la table correspondante
			if ($_POST ['typeUtilisateur'] == "Visiteur") {
				$utilisateur = $pdo->getInfosVisiteur ( $login, $mdp );
			} 

			elseif ($_POST ['typeUtilisateur'] == "Comptable") {
				$utilisateur = $pdo->getInfosComptable ( $login, $mdp );
			}
			
			// Si le login n'est pas trouvé dans la base, message d'erreur affiché
			if (! is_array ( $utilisateur )) {
				ajouterErreur ( "Login incorrect" );
				include ("vues/v_erreurs.php");
				include ("vues/v_connexion.php");
			} else {
				
				// test de cryptage du mot de passe
				
				if (! password_verify ( $mdp, $utilisateur ['mdp'] )) {
					ajouterErreur ( " Mot de passe incorrect" );
					include ("vues/v_erreurs.php");
					include ("vues/v_connexion.php");
				} else {
					
					$id = $utilisateur ['id'];
					$nom = $utilisateur ['nom'];
					$prenom = $utilisateur ['prenom'];
					$typeUtilisateur = $_POST ['typeUtilisateur'];
					connecter ( $id, $nom, $prenom, $typeUtilisateur );
					include ("vues/v_sommaire.php");
				}
			}
			break;
		}
	
	default :
		{
			include ("vues/v_connexion.php");
			break;
		}
}
?>