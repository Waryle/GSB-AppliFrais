    <!-- Division pour le sommaire -->
    <div id="menuGauche">
     <div id="infosUtil">
    
        <h2>
    	
				  <?php echo $_SESSION['typeUtilisateur'] ?> 
				  <div id="nomV"><?php echo $_SESSION['prenom']."  ".$_SESSION['nom']  ?></div>
		
</h2>
    
      </div>  
        <ul id="menuList">
		
		<?php 
	//SOPHIE :Affichage du menu en fonction du type d'utilisateur
		if( $_SESSION['typeUtilisateur'] == "Visiteur"){?>
           <li class="smenu">
              <a href="index.php?uc=gererFrais&action=saisirFrais" title="Saisie fiche de frais ">Saisie fiche de frais</a>
           </li>
           <li class="smenu">
              <a href="index.php?uc=etatFrais&action=selectionnerMois" title="Consultation de mes fiches de frais">Mes fiches de frais</a>
           </li>
          <?php  }else{ ?>
           
             <li class="smenu">
              <a href="index.php?uc=valider&action=selectionneVisiteur" title="Valider fiche">Valider fiche de frais</a>
           </li>
           <li class="smenu">
              <a href="index.php?uc=paiement&action=voirFicheaRembrouser" title="Mettre en paiement">Mettre en paiement</a>
           </li>
            <li class="smenu">
              <a href="index.php?uc=paiement&action=suivrePaiement" title="Suivre paiment">Suivre paiment</a>
           </li>
           
           
          <?php   }?>
 	   <li class="smenu">
              <a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">Déconnexion</a>
           </li>
         </ul>
        
    </div>
    