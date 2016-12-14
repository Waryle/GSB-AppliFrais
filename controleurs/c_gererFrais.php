<?php

/**
 * Contrôleur de gestion de fiches de frais
 *
 * Contrôleur permettant d'afficher la fiche de frais en cours et de modifier son contenu.
 *
 *
 * @package   GSB-AppliFrais/controleurs
 * @author    Bazebimio Jaïrus, Bouvry Sophie, Ducrocq Maxime
 * @version 1 (Décembre 2016)
 * @copyright 2016 Bazebimio Jaïrus, Bouvry Sophie, Ducrocq Maxime
 */

include ("vues/v_sommaire.php");
$idVisiteur = $_SESSION ['idVisiteur'];
$mois = getMois ( date ( "d/m/Y" ) );
$numAnnee = substr ( $mois, 0, 4 );
$numMois = substr ( $mois, 4, 2 );
$action = $_REQUEST ['action'];
switch ($action) {
	case 'saisirFrais' :
		{
			$jour=date("d"); 
			//probleme au 1er janvier à corriger
$etatfiche = $pdo->getEtat($idVisiteur, $mois-1);
		if ($pdo->estPremierFraisMois ( $idVisiteur, $mois ) and  $etatfiche['idEtat']=='CL' ) {
				$pdo->creeNouvellesLignesFrais ( $idVisiteur, $mois );
			}
			break;
		}
	case 'validerMajFraisForfait' :
		{
			$lesFrais = $_REQUEST ['lesFrais'];
			if (lesQteFraisValides ( $lesFrais )) {
				$pdo->majFraisForfait ( $idVisiteur, $mois, $lesFrais );
			} else {
				ajouterErreur ( "Les valeurs des frais doivent être numériques" );
				include ("vues/v_erreurs.php");
			}
			break;
		}
	case 'validerCreationFrais' :
		{
			$dateFrais = $_REQUEST ['dateFrais'];
			$libelle = $_REQUEST ['libelle'];
			$montant = $_REQUEST ['montant'];
			valideInfosFrais ( $dateFrais, $libelle, $montant );
			if (nbErreurs () != 0) {
				include ("vues/v_erreurs.php");
			} else {
				$pdo->creeNouveauFraisHorsForfait ( $idVisiteur, $mois, $libelle, $dateFrais, $montant );
			}
			break;
		}
	case 'supprimerFrais' :
		{
			$idFrais = $_REQUEST ['idFrais'];
			$pdo->supprimerFraisHorsForfait ( $idFrais );
			break;
		}
                
                case 'cloturerFicheFrais': {
                                $idVisiteur = $_SESSION['idVisiteur'];
                                $mois       = $_POST['mois'];
                                $etat       = "CL";
                                $montant    = 0;
                                $pdo->majEtatFicheFrais( $idVisiteur, $mois, $etat, $montant );
                               	header ( "Location:index.php?uc=gererFrais&action=saisirFrais" );
                }
}

$ficheFrais = $pdo->getEtat( $idVisiteur, $mois - 1 );
if ( $ficheFrais['idEtat'] == "CL" ) {
                $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait( $idVisiteur, $mois );
                $lesFraisForfait     = $pdo->getLesFraisForfait( $idVisiteur, $mois );
} else {
                $mois--;
                $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait( $idVisiteur, $mois - 1 );
                $lesFraisForfait     = $pdo->getLesFraisForfait( $idVisiteur, $mois - 1 );
                $numMois--;
                if ( $numMois == 0 ) {
                                $numMois = 12;
                                $numAnnee--;
                }
}

include( "vues/v_listeFraisForfait.php" );
include( "vues/v_listeFraisHorsForfait.php" );
include( "vues/v_boutonCloture.php" );
?>