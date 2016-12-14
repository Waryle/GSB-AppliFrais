<?php

/**
 * Contrôleur de mise en paiement des fiches de frais
 *
 * Contrôleur permettant l'affichage des fiches de frais déjà saisies.
 *
 *
 * @package   GSB-AppliFrais/controleurs
 * @author    Bazebimio Jaïrus, Bouvry Sophie, Ducrocq Maxime
 * @version 1 (Décembre 2016)
 * @copyright 2016 Bazebimio Jaïrus, Bouvry Sophie, Ducrocq Maxime
 */


include ("vues/v_sommaire.php");
$action = $_REQUEST ['action'];
switch ($action) {
	case 'voirFicheaRembrouser' :
		{
			$lesfiches = $pdo->GetlesFicheEtat ( "VA" );
			include ("vues/v_mettreEnPaiement.php");
			break;
		}
	case 'MiseEnPaiement' :
		{
			foreach ( $_POST ['choix'] as $fiche ) {
				$infos = decouperStringSeparateurPointVirgule ( $fiche );
				$pdo->majEtatFicheFrais ( $infos ["idVisiteur"], $infos ['mois'], 'MP', $infos ['montantValide'] );
			}
			header ( "Location:index.php?uc=paiement&action=voirFicheaRembrouser" );
			break;
		}
	case 'suivrePaiement' :
		{
			$lesfiches = $pdo->GetlesFicheEtat ( "MP" );
			include ("vues/v_suivrepaiement.php");
			break;
		}
	case 'rembourser' :
		{

	{
			foreach ( $_POST ['choix'] as $fiche ) {
				$infos = decouperStringSeparateurPointVirgule ( $fiche );
				$pdo->majEtatFicheFrais ( $infos ["idVisiteur"], $infos ['mois'], 'RB', $infos ['montantValide'] );
			}
			header ( "Location:index.php?uc=paiement&action=suivrePaiement" );
			break;
		}
		}
		case 'pdf' :{
$pdf = new PDF('P','mm','A4');
$pdf->AddPage();
$pdf->SetFont('Helvetica','',11);
$pdf->SetTextColor(0);
$pdf->Text(8,38,'Le '.date("m.d.y"));
$nom = 'misePaiement-'.date("m.d.y").'.pdf';
  $pdf->SetDrawColor(183); // Couleur du fond
    $pdf->SetFillColor(221); // Couleur des filets
    $pdf->SetTextColor(0); // Couleur du texte
    $pdf->SetY(58);
    $pdf->SetX(8);
    $pdf->Cell(15,8,'ID',1,0,'L',1);
    $pdf->SetX(166); // 8 + 96
    $pdf->Cell(158,8,'Nom',1,0,'L',1);
    $pdf->SetX(166); // 8 + 96
    $pdf->Cell(10,8,'Prénom',1,0,'C',1);
    $pdf->SetX(176); // 104 + 10
    $pdf->Cell(24,8,'Montant',1,0,'C',1);
    $pdf->Ln(); // Retour à la ligne
$lesfiches = $pdo->GetlesFicheEtat ( "MP" );
$position_detail=66;
foreach ($lesfiches as $uneFiche )  {

  $pdf->SetY($position_detail);
     $pdf->SetX(8);
      $pdf->MultiCell(15,8,utf8_decode($uneFiche['idVisiteur']),1,'L');
    $pdf->SetY($position_detail);
    $pdf->SetX(166);
    $pdf->MultiCell(158,8,utf8_decode($uneFiche['nom']),1,'L');
    $pdf->SetY($position_detail);
    $pdf->SetX(166);
    $pdf->MultiCell(10,8,$uneFiche['prenom'],1,'C');
    $pdf->SetY($position_detail);
    $pdf->SetX(176);
    $pdf->MultiCell(24,8,$uneFiche['montantValide'],1,'R');
    $position_detail += 8;
}
$pdf->Output($nom,'I');
		}

}
?>