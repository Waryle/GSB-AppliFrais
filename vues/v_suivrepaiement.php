<h3>Les fiches validés</h3>
<div id="contenu">
<?php
if (! empty ( $lesfiches )) {
	?>
	 <form method="post"
		action="index.php?uc=paiement&action=rembourser">
<table class="listeLegere">

		<tr>

			<th>Nom</th>
			<th>Prenom</th>
			<th>mois</th>
			<th>Montant</th>
			<th>A été remboursé</th>
		
		
		</tr>
<?php
	foreach ( $lesfiches as $uneFiche ) {
		?>
		
		
		
		<tr>
			<td><?php echo $uneFiche['nom']?></td>
			<td><?php echo $uneFiche['prenom']?></td>
			<td><?php echo $uneFiche['mois']?></td>
			<td><?php echo $uneFiche['montantValide']?></td>
			<td><input type="checkbox" id="choixValideFiche" name="choix[]"
					value="<?php echo $uneFiche['idVisiteur'].";".$uneFiche['mois'].";".$uneFiche['montantValide'].";" ?>" /></td>
		</tr>

<?php
	}
	
	?>		
</table>
<input type="submit" value="Valider" />
</form><?php
} else {
	?>
	 <p>Toutes les remboursements ont été effectués</p>
<?php }?>
</div>