<h3>Les fiches validés</h3>
<div id="contenu">
<?php 
if(!empty($lesfiches)){
	?>
<table class="listeLegere">

 <tr>
         
         <th>Nom</th>
         <th>Prenom</th>
         <th>mois</th>
         <th>Montant</th>
          <th>A été remboursé</th>

<tr>
<?php
foreach ($lesfiches as $uneFiche)
	{?>
		<tr>
<td><?php echo $uneFiche['nom']?></td>
<td><?php echo $uneFiche['prenom']?></td>
<td><?php echo $uneFiche['mois']?></td>
<td><?php echo $uneFiche['montantValide']?></td>
<td><a href="index.php?uc=paiement&action=rembourser&mois=<?php echo $uneFiche['mois'] ?>&idvisiteur=<?php echo $uneFiche['idVisiteur']?>&montant=<?php echo $uneFiche['montantValide']?>" 
        onclick="return confirm('Voulez-vous vraiment confirmer le remboursement?');">A été remboursé</a></td>
		</tr>

<?php

	}

		?>		
</table><?php
}else{
		?>
	 <p>
    Toutes les remboursements ont été effectués
    </p>
<?php }?>
</div>