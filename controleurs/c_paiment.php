<?php include("vues/v_sommaire.php");
$action = $_REQUEST['action'];

switch($action){
	case 'voirFicheaRembrouser':{
$lesfiches = $pdo->GetlesFicheEtat("VA");
include("vues/v_mettreEnPaiement.php");
	break; 
}
	

	case 'MiseEnPaiement':{
$idvisiteur = $_REQUEST['idvisiteur'];
$mois = $_REQUEST['mois'];
$montant = $_REQUEST['montant'] ;

$pdo->majEtatFicheFrais($idvisiteur,$mois,'MP', $montant);

header("Location:index.php?uc=paiement&action=voirFicheaRembrouser");
	break; 
}


case 'suivrePaiement':{
	$lesfiches = $pdo->GetlesFicheEtat("MP");
	include("vues/v_suivrepaiement.php");
break; 
}


case 'rembourser':{
	$idvisiteur = $_REQUEST['idvisiteur'];
$mois = $_REQUEST['mois'];
$montant = $_REQUEST['montant'] ;
$pdo->majEtatFicheFrais($idvisiteur,$mois,'RB', $montant);
	header("Location:index.php?uc=paiement&action=suivrePaiement");
break; 
}
	
	}
?> 