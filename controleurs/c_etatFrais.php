<?php

/**
* Contrôleur des fiches de frais en cours
*
* Contrôleur permettant l'affichage des fiches de frais déjà saisies. Permet une sélection du mois désiré, et l'affichage de
* la fiche correspondante.
*
*
* @package   GSB-AppliFrais/controleurs
* @author    Bazebimio Jaïrus, Bouvry Sophie, Ducrocq Maxime
* @version 1 (Décembre 2016)
* @copyright 2016 Bazebimio Jaïrus, Bouvry Sophie, Ducrocq Maxime
*/

include ("vues/v_sommaire.php");
$action = $_REQUEST ['action'];
$idVisiteur = $_SESSION ['idVisiteur'];
switch ($action) {
	case 'selectionnerMois' :
		{
			$anneePass = date ( "Y" );
			$lesMois = $pdo->getLesMoisDisponibles ( $idVisiteur );
			// Afin de sélectionner par défaut le dernier mois dans la zone de liste
			// on demande toutes les clés, et on prend la première,
			// les mois étant triés décroissants
			$lesCles = array_keys ( $lesMois );
			if (! empty ( $lesCles )) {
				$moisASelectionner = $lesCles [0];
				include ("vues/v_listeMois.php");
			}
			break;
		}
	case 'voirEtatFrais' :
		{
			$leMois = $_REQUEST ['lstMois'];
			$lesMois = $pdo->getLesMoisDisponibles ( $idVisiteur );
			$moisASelectionner = $leMois;
			$anneePass = date ( "Y" );
			include ("vues/v_listeMois.php");
			$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait ( $idVisiteur, $leMois );
			$lesFraisForfait = $pdo->getLesFraisForfait ( $idVisiteur, $leMois );
			$lesInfosFicheFrais = $pdo->getLesInfosFicheFrais ( $idVisiteur, $leMois );
			$numAnnee = substr ( $leMois, 0, 4 );
			$numMois = substr ( $leMois, 4, 2 );
			$libEtat = $lesInfosFicheFrais ['libEtat'];
			$montantValide = $lesInfosFicheFrais ['montantValide'];
			$nbJustificatifs = $lesInfosFicheFrais ['nbJustificatifs'];
			if (empty ( $nbJustificatifs )) {
				$nbJustificatifs = 0;
			}
			$dateModif = $lesInfosFicheFrais ['dateModif'];
			$dateModif = dateAnglaisVersFrancais ( $dateModif );
			include ("vues/v_etatFrais.php");
		}
}
?>