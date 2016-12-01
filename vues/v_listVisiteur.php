
<div id="contenu">
	<h2>Fiche de frais à valider</h2>
      
      <?php
						if (! empty ( $lesVisiteurs )) {
							?>
	 	<h3>Visiteur à sélectionner :</h3>
	<form action="index.php?uc=valider&action=selectionnerMois"
		method="post">
		<div class="corpsForm">

			<p>

				<label for="lstvisiteur" accesskey="n">Visiteur : </label> <select
					id="lstvisiteur" name="lstvisiteur">
            <?php
							foreach ( $lesVisiteurs as $uneligne ) {
								?>
				
				
				<option value="<?php echo $uneligne['idVisiteur'] ?>"><?php echo  $uneligne['idVisiteur']." ".$uneligne['nom']." ".$uneligne['prenom']; ?> </option>
			<?php
							}
							
							?>    
            
        </select>



			</p>
		</div>
		<div class="piedForm">
			<p>
				<input id="ok" type="submit" value="Valider" size="20" /> <input
					id="annuler" type="reset" value="Effacer" size="20" />
			</p>
		</div>

	</form>
  <?php
						
} else {
							?>
        <p>Aucune fiche de frais disponible</p>
       <?php } ?>