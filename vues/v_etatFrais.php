<h3>Fiche de frais du mois <?php echo $numMois."-".$numAnnee?> : 
    </h3>
<div class="encadre">
	<p>
        Etat : <?php echo $libEtat?> depuis le <?php echo $dateModif?> <br> Montant validé : <?php echo $montantValide?>
              
                     
    </p>
   <?php
			$montanttotalfrais = 0;
			if (! empty ( $lesFraisForfait )) {
				?>
  	<table class="listeLegere">
		<caption>Eléments forfaitisés</caption>
		<tr>

			<th>Totaux</th>
         <?php
				foreach ( $lesFraisForfait as $unFraisForfait ) {
					$libelle = $unFraisForfait ['libelle'];
					?>	
			<th> <?php echo $libelle?></th>
		 <?php
				}
				?>
		</tr>
		<tr>
			<td></td>
        <?php
				foreach ( $lesFraisForfait as $unFraisForfait ) {
					$quantite = $unFraisForfait ['quantite'];
					?>
                <td class="qteForfait"><?php echo $quantite?> </td>
		 <?php
				}
				?>
		</tr>
		<tr>
			<td>Totaux</td>
     <?php
				
				foreach ( $lesFraisForfait as $unFraisForfait ) {
					$montantfrais = $unFraisForfait ['quantite'] * $unFraisForfait ['montant'];
					$montanttotalfrais = $montanttotalfrais + $montantfrais;
					?>
                <td><?php echo $montantfrais."€"?> </td>
     <?php
				}
				?>
    </tr>
	</table>
    <?php }else{?>
    <p>Il n'y a aucun frais forfaitisé pour cette fiche</p>
  <?php   }?>
  	<table class="listeLegere">
		<caption>Descriptif des éléments hors forfait -<?php echo $nbJustificatifs ?> justificatifs reçus -
       </caption>
		<tr>
			<th class="date">Date</th>
			<th class="libelle">Libellé</th>
			<th class='montant'>Montant</th>

		</tr>
        <?php
								$montanttotalfraisHF = 0;
								foreach ( $lesFraisHorsForfait as $unFraisHorsForfait ) {
									$date = $unFraisHorsForfait ['date'];
									$libelle = $unFraisHorsForfait ['libelle'];
									$montant = $unFraisHorsForfait ['montant'];
									$chaine = stripos ( $libelle, "REFUSE" );
									if ($chaine === false) {
										$montanttotalfraisHF = $montanttotalfraisHF + $unFraisHorsForfait ['montant'];
									}
									
									?>
             <tr>
			<td><?php echo $date ?></td>
			<td><?php echo $libelle ?></td>
			<td><?php echo $montant ?></td>
		</tr>
        <?php
								}
								
								?>
    <tr>
			<td>Total</td>
			<td></td>
			<td><?php echo $montanttotalfraisHF."€" ?></td>
		</tr>
	</table>
	<p>Total de tous les frais confondus : <?php echo $montanttotalfraisHF+$montanttotalfrais."€" ?></p>
</div>
</div>














