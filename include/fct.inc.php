﻿<?php
/** 
 * Fonctions pour l'application GSB
 
 * @package default
 * @author Cheri Bibi
 * @version    1.0
 */
/**
 * Teste si un quelconque visiteur est connecté
 * 
 * @return vrai ou faux
 */
function estConnecte() {
	return isset ( $_SESSION ['idVisiteur'] );
}
/**
 * Enregistre dans une variable session les infos d'un visiteur
 *
 * @param
 *        	$id
 * @param
 *        	$nom
 * @param
 *        	$prenom
 */
function connecter($id, $nom, $prenom, $typeUtilisateur) {
	$_SESSION ['idVisiteur'] = $id;
	$_SESSION ['nom'] = $nom;
	$_SESSION ['prenom'] = $prenom;
	$_SESSION ['typeUtilisateur'] = $typeUtilisateur;
}
/**
 * Détruit la session active
 */
function deconnecter() {
	session_destroy ();
}
/**
 * Transforme une date au format français jj/mm/aaaa vers le format anglais aaaa-mm-jj
 *
 * @param $madate au
 *        	format jj/mm/aaaa
 * @return la date au format anglais aaaa-mm-jj
 *        
 */
function dateFrancaisVersAnglais($maDate) {
	@list ( $jour, $mois, $annee ) = explode ( '/', $maDate );
	return date ( 'Y-m-d', mktime ( 0, 0, 0, $mois, $jour, $annee ) );
}
/**
 * Transforme une date au format format anglais aaaa-mm-jj vers le format français jj/mm/aaaa
 *
 * @param $madate au
 *        	format aaaa-mm-jj
 * @return la date au format format français jj/mm/aaaa
 *        
 */
function dateAnglaisVersFrancais($maDate) {
	@list ( $annee, $mois, $jour ) = explode ( '-', $maDate );
	$date = "$jour" . "/" . $mois . "/" . $annee;
	return $date;
}
/**
 * retourne le mois au format aaaamm selon le jour dans le mois
 *
 * @param $date au
 *        	format jj/mm/aaaa
 * @return le mois au format aaaamm
 *        
 */
function getMois($date) {
	@list ( $jour, $mois, $annee ) = explode ( '/', $date );
	if (strlen ( $mois ) == 1) {
		$mois = "0" . $mois;
	}
	return $annee . $mois;
}

/* gestion des erreurs */
/**
 * Indique si une valeur est un entier positif ou nul
 *
 * @param
 *        	$valeur
 * @return vrai ou faux
 *        
 */
function estEntierPositif($valeur) {
	return preg_match ( "/[^0-9]/", $valeur ) == 0;
}

/**
 * Indique si un tableau de valeurs est constitué d'entiers positifs ou nuls
 *
 * @param $tabEntiers :
 *        	le tableau
 * @return vrai ou faux
 *        
 */
function estTableauEntiers($tabEntiers) {
	$ok = true;
	foreach ( $tabEntiers as $unEntier ) {
		if (! estEntierPositif ( $unEntier )) {
			$ok = false;
		}
	}
	return $ok;
}
/**
 * Vérifie si une date est inférieure d'un an à la date actuelle
 *
 * @param
 *        	$dateTestee
 * @return vrai ou faux
 *        
 */
function estDateDepassee($dateTestee) {
	$dateActuelle = date ( "d/m/Y" );
	@list ( $jour, $mois, $annee ) = explode ( '/', $dateActuelle );
	$annee --;
	$AnPasse = $annee . $mois;
	@list ( $jourTeste, $moisTeste, $anneeTeste ) = explode ( '/', $dateTestee );
	return ($anneeTeste . $moisTeste < $AnPasse);
}
/**
 * Vérifie la validité du format d'une date française jj/mm/aaaa
 *
 * @param
 *        	$date
 * @return vrai ou faux
 *        
 */
function estDateValide($date) {
	$tabDate = explode ( '/', $date );
	$dateOK = true;
	if (count ( $tabDate ) != 3) {
		$dateOK = false;
	} else {
		if (! estTableauEntiers ( $tabDate )) {
			$dateOK = false;
		} else {
			if (! checkdate ( $tabDate [1], $tabDate [0], $tabDate [2] )) {
				$dateOK = false;
			}
		}
	}
	return $dateOK;
}

/**
 * Vérifie que le tableau de frais ne contient que des valeurs numériques
 *
 * @param
 *        	$lesFrais
 * @return vrai ou faux
 *        
 */
function lesQteFraisValides($lesFrais) {
	return estTableauEntiers ( $lesFrais );
}
/**
 * Vérifie la validité des trois arguments : la date, le libellé du frais et le montant
 *
 * des message d'erreurs sont ajoutés au tableau des erreurs
 *
 * @param
 *        	$dateFrais
 * @param
 *        	$libelle
 * @param
 *        	$montant
 */
function valideInfosFrais($dateFrais, $libelle, $montant) {
	if ($dateFrais == "") {
		ajouterErreur ( "Le champ date ne doit pas être vide" );
	} elseif (! estDatevalide ( $dateFrais )) {
		ajouterErreur ( "Date invalide" );
	} elseif (estDateDepassee ( $dateFrais )) {
		ajouterErreur ( "date d'enregistrement du frais dépassé, plus de 1 an" );
	} elseif (estTropGrand ( $dateFrais )) {
		ajouterErreur ( "date d'enregistrement du frais pas entammée" );
	}
	if ($libelle == "") {
		ajouterErreur ( "Le champ description ne peut pas être vide" );
	}
	if ($montant == "") {
		ajouterErreur ( "Le champ montant ne peut pas être vide" );
	} elseif (! is_numeric ( $montant )) {
		ajouterErreur ( "Le champ montant doit être numérique" );
	}
}
/**
 * Ajoute le libellé d'une erreur au tableau des erreurs
 *
 * @param $msg :
 *        	le libellé de l'erreur
 */
function ajouterErreur($msg) {
	if (! isset ( $_REQUEST ['erreurs'] )) {
		$_REQUEST ['erreurs'] = array ();
	}
	$_REQUEST ['erreurs'] [] = $msg;
}
/**
 * Retoune le nombre de lignes du tableau des erreurs
 *
 * @return le nombre d'erreurs
 */
function nbErreurs() {
	if (! isset ( $_REQUEST ['erreurs'] )) {
		return 0;
	} else {
		return count ( $_REQUEST ['erreurs'] );
	}
}

/**
 * Jairus:
 * fonction estTropGrand($moisTestee) fonction qui vérifie si le mois saisi est supérieur au mois en cours et
 * retourne vrai si le mois saisi dépasse celui en cours @param $moisTestee @return vrai ou faux
 */
function estTropGrand($date) {
	$dateActuelle = date ( "d/m/Y" );
	@list ( $jour, $mois, $annee ) = explode ( '/', $dateActuelle );
	@list ( $j, $m, $an ) = explode ( '/', $date );
	$aujourdhui = $annee . $mois;
	return ($an . $m > $aujourdhui);
}

/**
 * Découpe le string en prenant ";" comme séparateur
 * 
 * @param String $string        	
 * @return String[]
 */
function decouperStringSeparateurPointVirgule($string) {
	@list ( $idVisiteur, $mois, $montantValid ) = explode ( ';', $string );
	$info ['idVisiteur'] = $idVisiteur;
	$info ['mois'] = $mois;
	$info ['montantValide'] = $montantValide;
	return $info;
}

?>
<script type="text/javascript">
function info() {
  alert("Mise à jour prise en compte");
}
</script>


