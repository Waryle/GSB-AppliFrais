<div id="contenu">
<?php if($_SESSION['typeUtilisateur'] == 'Visiteur'){ ?>

      <h2>Renseigner ma fiche de frais du mois <?php echo $numMois."-".$numAnnee ?></h2>
    
         <form method="POST"  action="index.php?uc=gererFrais&action=validerMajFraisForfait">
             <?php 
    } 
        else {?>
         <form method="POST"  action="index.php?uc=valider&action=validerMajFraisForfait&lstvisiteur=<?php echo $idvisiteur?>&mois=<?php echo $mois?>">
         <h2><?php echo $visiteur_prenom.' '.$visiteur_nom ?>,fiche de frais du mois <?php echo $numMois."-".$numAnnee ?></h2>
        	<h3><a href="index.php?uc=valider&action=selectionnerMois&lstvisiteur=<?php echo $idvisiteur?>" onclick="return confirm('Voulez-vous vraiment revenir en arrière ?');">Retour</a></h3>
           	<h3><a href="index.php?uc=valider&action=validerfiche&idvisiteur=<?php echo $idvisiteur?>&mois=<?php echo $mois?>" onclick="return confirm('Voulez-vous vraiment valider cette fiche?');">Valider fiche</a></h3>
        	 <!--<form method="POST"  action="index.php?uc=valider&action=validerMajFraisForfait">-->
        	<?php }?>
     
      <div class="corpsForm">
          
          <fieldset>
            <legend>Eléments forfaitisés
            </legend>
			<?php
			$montanttotalfraisF = 0;
				foreach ($lesFraisForfait as $unFrais)
				{
					$idFrais = $unFrais['idfrais'];
					$libelle = $unFrais['libelle'];
					$quantite = $unFrais['quantite'];
					$montant = $unFrais['montant'];
					$montanttotalfraisF = $montanttotalfraisF + ($unFrais['montant']*$unFrais['quantite']);
			?>
					<p>
						<label for="idFrais"><?php echo $libelle ?></label>
						<input type="text" id="idFrais" name="lesFrais[<?php echo $idFrais?>]" size="10" maxlength="5" value="<?php echo $quantite?>" > &nbsp; &nbsp;
						
						<input type="text" id="montanttotal" name="montant" size="10" maxlength="5" value="<?php echo $quantite*$montant?>" disabled>
					</p>
			
			<?php
				}
			
			?>
			
			
			
			
           
          </fieldset>
      </div>
      <div class="piedForm">
      <p>
        <input id="ok" type="submit" value="Valider" size="20" />
        <input id="annuler" type="reset" value="Effacer" size="20" />
      </p> 
      </div>
         <p>Le montant des frais forfaitisés total est de <?php echo $montanttotalfraisF?></p>
      </form>
  