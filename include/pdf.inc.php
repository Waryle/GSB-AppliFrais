
<?php class PDF extends FPDF {
    // Header
    function Header() {
        // Logo
        $this->Image('images/logo.jpg',8,2,80);
        // Saut de ligne
        $this->Ln(20);
    }
    // Footer
    function Footer() {
        // Positionnement à 1,5 cm du bas
        $this->SetY(-15);
        // Adresse
        $this->Cell(196,5,'GSB',0,0,'C');
    }
}

function entete_table($position_entete){
    global $pdf;
    $pdf->SetDrawColor(183); // Couleur du fond
    $pdf->SetFillColor(221); // Couleur des filets
    $pdf->SetTextColor(0); // Couleur du texte
    $pdf->SetY($position_entete);
    $pdf->SetX(8);
    $pdf->Cell(158,8,'ID',1,0,'L',1);
    $pdf->SetX(166); // 8 + 96
    $pdf->Cell(158,8,'Nom',1,0,'L',1);
    $pdf->SetX(166); // 8 + 96
    $pdf->Cell(10,8,'Prénom',1,0,'C',1);
    $pdf->SetX(176); // 104 + 10
    $pdf->Cell(24,8,'Montant',1,0,'C',1);
    $pdf->Ln(); // Retour à la ligne
}


function Positiondetail($position_detail, $uneFiche){
    global $pdf;
     $pdf->SetY($position_detail);
     $pdf->SetX(8);
      $pdf->MultiCell(158,8,utf8_decode($uneFiche['idVisiteur']),1,'L');
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

?>