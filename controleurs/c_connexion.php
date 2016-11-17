<?php
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
			
			// Test le type d'utilisateur et change la requête en fonction du type d'utilisateur
			if ($_POST ['typeUtilisateur'] == "visiteur") {
				$utilisateur = $pdo->getInfosVisiteur ( $login, $mdp );
			} 

			elseif ($_POST ['typeUtilisateur'] == "comptable") {
				$utilisateur = $pdo->getInfosComptable ( $login, $mdp );
			}
			
			if (! is_array ( $utilisateur )) {
				ajouterErreur ( "Login incorrect" );
				include ("vues/v_erreurs.php");
				include ("vues/v_connexion.php");
			} else {
				
				// test de cryptage du mot de passe
				
				if (!password_verify ( $mdp, $utilisateur ['mdp'] )) {
					ajouterErreur ( " Mot de passe incorrect" );
					include ("vues/v_erreurs.php");
					include ("vues/v_connexion.php");
				} else {
					
					$id = $utilisateur ['id'];
					$nom = $utilisateur ['nom'];
					$prenom = $utilisateur ['prenom'];
					$typeUtilisateur = $_POST['typeUtilisateur'] ;
					connecter ( $id, $nom, $prenom );
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