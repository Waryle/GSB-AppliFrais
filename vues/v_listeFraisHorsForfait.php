<table class="listeLegere">


<?php
if (!empty($lesFraisHorsForfait)) {
?>


<?php
    if ($_SESSION['typeUtilisateur'] == 'Comptable') {
?>
<p>
Etat : <?php
        echo $libEtat;
?> depuis le <?php
        echo $dateModif;
?> <br>
<?php
        echo $nbJustificatifs;
?> justificatifs reçus 
</p>
<?php
    }
    
    
?>

              <caption>Descriptif des éléments hors forfait</caption>
             <tr>
                <th class="date">Date</th>
                        <th class="libelle">Libellé</th>  
                <th class="montant">Montant</th>  
                <th class="action">Supprimer</th>
              <?php
    if ($_SESSION['typeUtilisateur'] == 'Comptable') {
?> 
               <th class="action">Repporter</th>     <?php
    }
?>                  
             </tr>
          
    <?php
    $montanttotalfraisHF = 0;
    
    foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
        $libelle = $unFraisHorsForfait['libelle'];
        $date    = $unFraisHorsForfait['date'];
        $montant = $unFraisHorsForfait['montant'];
        $id      = $unFraisHorsForfait['id'];
        
?>        
            <tr>
                <td> <?php
        echo $date;
?></td>
                <td><?php
        echo $libelle;
?></td>
                <td><?php
        echo $montant;
?></td>
                
      <?php
        $chaine = stripos($libelle, "REFUSE");
        if ($_SESSION['typeUtilisateur'] == 'Comptable' and $chaine === false) {
            $montanttotalfraisHF = $montanttotalfraisHF + $montant;
?>
<td><a href="index.php?uc=valider&action=supprimerFrais&idFrais=<?php
            echo $id;
?>&lstvisiteur=<?php
            echo $idvisiteur;
?>&mois=<?php
            echo $mois;
?>" onclick="return confirm('Voulez-vous vraiment supprimer ce frais?');">Supprimer ce frais</a></td>

<td><a href="index.php?uc=valider&action=repporterfrais&idFrais=<?php
            echo $id;
?>&lstvisiteur=<?php
            echo $idvisiteur;
?>&mois=<?php
            echo $mois;
?>" 
        onclick="return confirm('Voulez-vous vraiment repporter ce frais?');">Repporter ce frais</a></td>
       <?php
        } elseif ($_SESSION['typeUtilisateur'] == 'Visiteur') {
            $montanttotalfraisHF = $montanttotalfraisHF + $montant;
?>

<td><a href="index.php?uc=gererFrais&action=supprimerFrais&idFrais=<?php
            echo $id;
?>" 
        onclick="return confirm('Voulez-vous vraiment supprimer ce frais?');">Supprimer ce frais</a></td>
<?php
        } else {
?>
<td></td>
<td></td>
<?php
        }
?>
            </tr>
    <?php
        
    }
?>      
       <tr>
         <td>Total</td>
       <td></td>
        <td><?php
    echo $montanttotalfraisHF . "€";
?></td>
       </tr>                                  
    </table>
    
  
   <?php
   
   $montanttotal = $montanttotalfraisHF + $_SESSION['montantTotalFraisForfait'] ;
   
   echo "Montant total : ". $montanttotal ;
  ?> <h3><a href="index.php?uc=valider&action=validerfiche&idvisiteur=<?php echo $idvisiteur?>&mois=<?php echo $mois?>&montant=<?php echo $montanttotal ?>" onclick="return confirm('Voulez-vous vraiment valider cette fiche?');">Valider fiche</a></h3>
   <?php 
   $_SESSION['montantTotalFraisForfait'] = null ;
   unset($_SESSION['montantTotalFraisForfait']) ;
} else {
?>
   <p>
    il n'y a pas de frais hors forfait pour cette fiche
    </p>
<?php
}
?>
   <?php
if ($_SESSION['typeUtilisateur'] == 'Visiteur') {
?>
     <form action="index.php?uc=gererFrais&action=validerCreationFrais" method="post">
      <div class="corpsForm">
        
          <fieldset>
            <legend>Nouvel élément hors forfait
            </legend>
            <p>
              <label for="txtDateHF">Date (jj/mm/aaaa): </label>
              <input type="text" id="txtDateHF" name="dateFrais" size="10" maxlength="10" value=""  />
            </p>
            <p>
              <label for="txtLibelleHF">Libellé</label>
              <input type="text" id="txtLibelleHF" name="libelle" size="70" maxlength="256" value="" />
            </p>
            <p>
              <label for="txtMontantHF">Montant : </label>
              <input type="text" id="txtMontantHF" name="montant" size="10" maxlength="10" value="" />
            </p>
          </fieldset>
      </div>
      <div class="piedForm">
      <p>
        <input id="ajouter" type="submit" value="Ajouter" size="20" />
        <input id="effacer" type="reset" value="Effacer" size="20" />
      </p> 
      </div>
        
      </form>
      <?php
}


?>

  </div>