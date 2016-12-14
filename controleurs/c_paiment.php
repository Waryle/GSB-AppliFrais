<?php
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
	ob_end_clean();
			
			$comptable=$pdo->getPrenomComptable($_SESSION['idVisiteur']);
			
$pdf = new PDF('P','mm','A4');
$pdf->AddPage();
$pdf->SetFont('Helvetica','',11);
$pdf->SetTextColor(0);
$pdf->Text(8,60,utf8_decode('Le '.date("d.m.y"). "  généré par ".$comptable['prenom']));
$nom = 'misePaiement-'.date("d.m.y").'.pdf';
  $pdf->SetDrawColor(183); // Couleur du fond
    $pdf->SetFillColor(221); // Couleur des filets
    $pdf->SetTextColor(0); // Couleur du texte
    $pdf->SetY(70);
    $pdf->SetX(8);
    $pdf->Cell(20,8,'ID',1,0,'L',1);
    $pdf->SetX(28); // 8 + 96
    $pdf->Cell(60,8,'Nom',1,0,'L',1);
    $pdf->SetX(88); // 8 + 96
    $pdf->Cell(80,8,utf8_decode('Prénom'),1,0,'L',1);
    $pdf->SetX(168); // 104 + 10
      $pdf->Cell(20,8,'Montant',1,0,'L',1);
	    $pdf->Ln(); // Retour à la ligne
$lesfiches = $pdo->GetlesFicheEtat ( "VA" );
$position_detail=78;
foreach ($lesfiches as $uneFiche )  {

  $pdf->SetY($position_detail);
     $pdf->SetX(8);
      $pdf->MultiCell(20,8,utf8_decode($uneFiche['idVisiteur']),1,'L');
	  
	  
    $pdf->SetY($position_detail);
    $pdf->SetX(28);
    $pdf->MultiCell(60,8,utf8_decode($uneFiche['nom']),1,'L');
	
	
    $pdf->SetY($position_detail);
    $pdf->SetX(88);
	$pdf->MultiCell(80,8,utf8_decode($uneFiche['prenom']),1,'L');
	
    $pdf->SetY($position_detail);
    $pdf->SetX(168);
    $pdf->MultiCell(20,8,$uneFiche['montantValide'],1,'L');
	
	$pdf->SetY($position_detail);
	$pdf->SetX(188);
    $position_detail += 8;
}

$pdf->Output($nom,'I');
		
		}

}
?>