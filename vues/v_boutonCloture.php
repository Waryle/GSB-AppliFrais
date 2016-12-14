<?php
	$jourCourant = date("d") ;
	if(date("m") == "1")
		$moisPrecedent = date("Y")-1 . "12" ;
	else
		$moisPrecedent = date("Y").date("m")-1;
	$fiche = $pdo->getEtat($_SESSION['idVisiteur'], $moisPrecedent) ;
	
	if($jourCourant >= 1 && $jourCourant <= 14 && $fiche[0] == 'CR') {
?>
	<p>
		<form action=index.php?uc=gererFrais&action=cloturerFicheFrais method="POST">
			<input type=hidden name=mois value=<?php echo $moisPrecedent ; ?> />
			<input type=submit value="Clôturer la fiche actuelle" />
		</form>
	</p>
<?php 
	}
?>