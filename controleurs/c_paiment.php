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

$pdo->majEtatFicheFrais($idvisiteur,$mois,'MP');

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

$pdo->majEtatFicheFrais($idvisiteur,$mois,'RB');
	header("Location:index.php?uc=paiement&action=suivrePaiement");
break; 
}
	
	}
?> 