<?php include("vues/v_sommaire.php");
$action = $_REQUEST['action'];

switch($action){
	case 'selectionneVisiteur':{
		$lesVisiteurs=$pdo->GetlesVisiteurFicheCloture();
include("vues/v_listVisiteur.php");
	break;
}

	case 'selectionnerMois':{
$idvisiteur = $_REQUEST['lstvisiteur'];
$lesMois=$pdo->getLesMoiscloture($idvisiteur);
include("vues/v_listeMois.php");
	break;
}

case 'voirFraisVisiteur' :{
	$mois = $_REQUEST['lstMois'];

	$idvisiteur = $_REQUEST['idvisiteur'];
	$visiteur = $pdo->getVisiteur($idvisiteur);
	$visiteur_prenom = $visiteur['prenom'];
	$visiteur_nom  = $visiteur['nom'];
	
	//$lesMois=$pdo->getLesMoiscloture($idvisiteur);
		$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idvisiteur,$mois);
		$lesFraisForfait= $pdo->getLesFraisForfait($idvisiteur,$mois);
		$lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idvisiteur,$mois);
		$numAnnee =substr( $mois,0,4);
		$numMois =substr( $mois,4,2);
		$libEtat = $lesInfosFicheFrais['libEtat'];
	
		$nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
		if(empty($nbJustificatifs)){
			$nbJustificatifs =0 ;
		}
		$dateModif =  $lesInfosFicheFrais['dateModif'];
		$dateModif =  dateAnglaisVersFrancais($dateModif);
		//include("vues/v_listeMois.php");
		$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idvisiteur,$mois);
		$lesFraisForfait= $pdo->getLesFraisForfait($idvisiteur,$mois);
		include("vues/v_listeFraisForfait.php");
		include("vues/v_listeFraisHorsForfait.php");
		break;
}



case 'validerfiche' :{

	$idvisiteur= $_REQUEST['idvisiteur'];
	$mois= $_REQUEST['mois'];
	$montant= $_REQUEST['montant'];
	$pdo->majEtatFicheFrais($idvisiteur,$mois,"VA", $montant);
}
	break;


case 'validerMajFraisForfait' :{
	$mois = $_REQUEST['mois'];

$idvisiteur = $_REQUEST['lstvisiteur'];
	$visiteur = $pdo->getVisiteur($idvisiteur);
	$visiteur_prenom = $visiteur['prenom'];
	$visiteur_nom  = $visiteur['nom'];
$lesFrais = $_REQUEST['lesFrais'];
		if(lesQteFraisValides($lesFrais)){
	  	 	if($pdo->majFraisForfait($idvisiteur,$mois,$lesFrais)){
echo '<script> info() ;</script>';
header("Location:index.php?uc=valider&action=voirFraisVisiteur&lstMois=$mois&idvisiteur=$idvisiteur");
	  	 	}

		}
		else{
			ajouterErreur("Les valeurs des frais doivent être numériques");
			include("vues/v_erreurs.php");
		}
		
	break;
}

	case 'supprimerFrais':{
		$mois = $_REQUEST['mois'];

		$idvisiteur = $_REQUEST['lstvisiteur'];
		$visiteur = $pdo->getVisiteur($idvisiteur);
		$visiteur_prenom = $visiteur['prenom'];
		$visiteur_nom  = $visiteur['nom'];
		$idFrais = $_REQUEST['idFrais'];
		$pdo->SetLibelleFraisHorsForfait($idFrais);
		header("Location:index.php?uc=valider&action=voirFraisVisiteur&lstMois=$mois&idvisiteur=$idvisiteur");


	  
		break;
	}

		case 'repporterfrais':{
			$mois = $_REQUEST['mois'];

$idvisiteur = $_REQUEST['lstvisiteur'];
	$visiteur = $pdo->getVisiteur($idvisiteur);
	$visiteur_prenom = $visiteur['prenom'];
	$visiteur_nom  = $visiteur['nom'];
		$idFrais = $_REQUEST['idFrais'];
	var_dump($idFrais);
	var_dump($mois);
	var_dump($idvisiteur);
	  $pdo->repportFrais($idFrais, $mois, $idvisiteur);

	    header("Location:index.php?uc=valider&action=voirFraisVisiteur&lstMois=$mois&idvisiteur=$idvisiteur");

		break;
	}

	}
?>