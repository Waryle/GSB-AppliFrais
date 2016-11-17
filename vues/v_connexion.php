<div id="contenu">

	<?php
	// test , si il s'agit d'un comptable ou un visiteur , et change le résultat du formulaire en fonction de l'utilisateur
	if (! isset ( $_REQUEST ['utilisateur'] )) {
		$utilisateur = "visiteur";
	} else {
		$utilisateur = $_REQUEST ['utilisateur'];
	}
	if ($utilisateur == "visiteur") {
		?>
		<h2>Identification utilisateur</h2>


	<form method="POST"
		action="index.php?uc=connexion&action=valideConnexion"
		id="FormConnexion">


		<p>
			<label for="nom">Login*</label> <input id="login" type="text"
				name="login" size="30" maxlength="45">
		</p>
		<p>
			<label for="mdp">Mot de passe*</label> <input id="mdp"
				type="password" name="mdp" size="30" maxlength="45">
		</p>
		<input id="typeUtilisateur" name="typeUtilisateur" type="hidden" value="visiteur"> <input
			type="submit" value="Valider" name="valider"> <input type="reset"
			value="Annuler" name="annuler">
		</p>
	</form>
	<p>
		<a href="index.php?uc=connexion&utilisateur=comptable"> Se connecter
			en tant que comptable </a>
	</p>
	<?php
	} elseif ($utilisateur == "comptable") {
		?>
		<h2>Identification utilisateur</h2>
	<form method="POST"
		action="index.php?uc=connexion&action=valideConnexion&utilisateur=comptable"
		id="FormConnexion">



		<p>
			<label for="nom">Login*</label> <input id="login" type="text"
				name="login" size="30" maxlength="45">
		</p>
		<p>
			<label for="mdp">Mot de passe*</label> <input id="mdp"
				type="password" name="mdp" size="30" maxlength="45">
		</p>
		<input id="typeUtilisateur" name="typeUtilisateur" type="hidden" value="comptable" /> <input
			type="submit" value="Valider" name="valider" /> <input type="reset"
			value="Annuler" name="annuler">
		</p>
	</form>
	<p>
		<a href="index.php?uc=connexion&utilisateur=visiteur"> Se connecter en
			tant que visiteur </a>
	<?php } ?> 

















</div>