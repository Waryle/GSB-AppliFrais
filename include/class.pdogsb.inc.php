 <?php
	/** 
	 * Classe d'accès aux données. 
	 
	 * Utilise les services de la classe PDO
	 * pour l'application GSB
	 * Les attributs sont tous statiques,
	 * les 4 premiers pour la connexion
	 * $monPdo de type PDO 
	 * $monPdoGsb qui contiendra l'unique instance de la classe
	 
	 * @package default
	 * @author Cheri Bibi
	 * @version    1.0
	 * @link       http://www.php.net/manual/fr/book.pdo.php
	 */
	class PdoGsb {
		private static $serveur = 'mysql:host=localhost';
		private static $bdd = 'dbname=gsb_frais';
		private static $user = 'root';
		private static $mdp = '';
		private static $monPdo;
		private static $monPdoGsb = null;
		/**
		 * Constructeur privé, crée l'instance de PDO qui sera sollicitée
		 * pour toutes les méthodes de la classe
		 */
		private function __construct() {
			PdoGsb::$monPdo = new PDO ( PdoGsb::$serveur . ';' . PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp );
			PdoGsb::$monPdo->query ( "SET CHARACTER SET utf8" );
		}
		public function _destruct() {
			PdoGsb::$monPdo = null;
		}
		/**
		 * Fonction statique qui crée l'unique instance de la classe
		 *
		 * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
		 *
		 * @return l'unique objet de la classe PdoGsb
		 */
		public static function getPdoGsb() {
			if (PdoGsb::$monPdoGsb == null) {
				PdoGsb::$monPdoGsb = new PdoGsb ();
			}
			return PdoGsb::$monPdoGsb;
		}
		/**
		 * Retourne les informations d'un visiteur
		 *
		 * @param
		 *        	$login
		 * @param
		 *        	$mdp
		 * @return l'id, le nom et le prénom sous la forme d'un tableau associatif
		 */
		public function getInfosVisiteur($login, $mdp) {
			$req = PdoGsb::$monPdo->prepare ( "select visiteur.id as id, visiteur.nom as nom, visiteur.prenom as prenom, visiteur.mdp as mdp from visiteur 
        where visiteur.login=:login" );
			$req->execute ( array (
					'login' => $login 
			) );
			$ligne = $req->fetch ();
			return $ligne;
		}
		
		/**
		 * Retourne les informations d'un comptable
		 *
		 * @param
		 *        	$login
		 * @param
		 *        	$mdp
		 * @return l'id, nom et le prenom sous la forme d'un tableau associatif
		 */
		public function getInfosComptable($login, $mdp) {
			$req = PdoGsb::$monPdo->prepare ( "select comptable.id as id, comptable.nom as nom, comptable.prenom as prenom,comptable.mdp as mdp from comptable
        where comptable.login= :login" );
			$req->execute ( array (
					'login' => $login 
			) );
			$ligne = $req->fetch ();
			return $ligne;
		}
		
		/**
		 * Retourne sous forme d'un tableau associatif toutes les lignes de frais hors forfait
		 * concernées par les deux arguments
		 *
		 * La boucle foreach ne peut être utilisée ici car on procède
		 * à une modification de la structure itérée - transformation du champ date-
		 *
		 * @param
		 *        	$idVisiteur
		 * @param $mois sous
		 *        	la forme aaaamm
		 * @return tous les champs des lignes de frais hors forfait sous la forme d'un tableau associatif
		 */
		public function getLesFraisHorsForfait($idVisiteur, $mois) {
			$req = PdoGsb::$monPdo->prepare ( "select * from lignefraishorsforfait where lignefraishorsforfait.idvisiteur = :idVisiteur 
        and lignefraishorsforfait.mois = :mois " );
			$req->execute ( array (
					'idVisiteur' => $idVisiteur,
					'mois' => $mois 
			) );
			$lesLignes = $req->fetchAll ();
			$nbLignes = count ( $lesLignes );
			for($i = 0; $i < $nbLignes; $i ++) {
				$date = $lesLignes [$i] ['date'];
				$lesLignes [$i] ['date'] = dateAnglaisVersFrancais ( $date );
			}
			return $lesLignes;
		}
		/**
		 * Retourne le nombre de justificatif d'un visiteur pour un mois donné
		 *
		 * @param
		 *        	$idVisiteur
		 * @param $mois sous
		 *        	la forme aaaamm
		 * @return le nombre entier de justificatifs
		 */
		public function getNbjustificatifs($idVisiteur, $mois) {
			$req = PdoGsb::$monPdo->prepare ( "select fichefrais.nbjustificatifs as nb from  fichefrais where fichefrais.idvisiteur = :idVisiteur and fichefrais.mois = :mois" );
			$req->execute ( array (
					'idVisiteur' => $idVisiteur,
					'mois' => $mois 
			) );
			$laLigne = $req->fetch ();
			return $laLigne ['nb'];
		}
		/**
		 * Retourne sous forme d'un tableau associatif toutes les lignes de frais au forfait
		 * concernées par les deux arguments
		 *
		 * @param
		 *        	$idVisiteur
		 * @param $mois sous
		 *        	la forme aaaamm
		 * @return l'id, le libelle et la quantité sous la forme d'un tableau associatif
		 */
		public function getLesFraisForfait($idVisiteur, $mois) {
			$req = PdoGsb::$monPdo->prepare ( "select fraisforfait.id as idfrais, fraisforfait.libelle as libelle, 
        lignefraisforfait.quantite as quantite, fraisforfait.montant as montant from lignefraisforfait inner join fraisforfait 
        on fraisforfait.id = lignefraisforfait.idfraisforfait
        where lignefraisforfait.idvisiteur = :idVisiteur and lignefraisforfait.mois= :mois
        order by lignefraisforfait.idfraisforfait" );
			$req->execute ( array (
					'idVisiteur' => $idVisiteur,
					'mois' => $mois 
			) );
			$lesLignes = $req->fetchAll ();
			return $lesLignes;
		}
		/**
		 * Retourne tous les id de la table FraisForfait
		 *
		 * @return un tableau associatif
		 */
		public function getLesIdFrais() {
			$req = "select fraisforfait.id as idfrais from fraisforfait order by fraisforfait.id";
			$res = PdoGsb::$monPdo->query ( $req );
			$lesLignes = $res->fetchAll ();
			return $lesLignes;
		}
		/**
		 * Met à jour la table ligneFraisForfait
		 *
		 * Met à jour la table ligneFraisForfait pour un visiteur et
		 * un mois donné en enregistrant les nouveaux montants
		 *
		 * @param
		 *        	$idVisiteur
		 * @param $mois sous
		 *        	la forme aaaamm
		 * @param $lesFrais tableau
		 *        	associatif de clé idFrais et de valeur la quantité pour ce frais
		 * @return un tableau associatif
		 */
		public function majFraisForfait($idVisiteur, $mois, $lesFrais) {
			$lesCles = array_keys ( $lesFrais );
			
			foreach ( $lesCles as $unIdFrais ) {
				$qte = $lesFrais [$unIdFrais];
				
				$req = PdoGsb::$monPdo->prepare ( "update lignefraisforfait set lignefraisforfait.quantite = :qte
            where lignefraisforfait.idvisiteur = :idVisiteur and lignefraisforfait.mois = :mois
            and lignefraisforfait.idfraisforfait = :unIdFrais " );
				$req->execute ( array (
						'qte' => $qte,
						'idVisiteur' => $idVisiteur,
						'mois' => $mois,
						'unIdFrais' => $unIdFrais 
				) );
			}
			return true;
		}
		
		/**
		 * met à jour le nombre de justificatifs de la table ficheFrais
		 * pour le mois et le visiteur concerné
		 *
		 * @param
		 *        	$idVisiteur
		 * @param $mois sous
		 *        	la forme aaaamm
		 */
		public function majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs) {
			$req = PdoGsb::$monPdo->prepare ( "update fichefrais set nbjustificatifs = :nbJustificatifs where fichefrais.idvisiteur = :idVisiteur and fichefrais.mois = :mois" );
			$req->execute ( array (
					'nbJustificatifs' => $nbJustificatifs,
					'idVisiteur' => $idVisiteur,
					'mois' => $mois 
			) );
		}
		/**
		 * Teste si un visiteur possède une fiche de frais pour le mois passé en argument
		 *
		 * @param
		 *        	$idVisiteur
		 * @param $mois sous
		 *        	la forme aaaamm
		 * @return vrai ou faux
		 */
		public function estPremierFraisMois($idVisiteur, $mois) {
			$ok = false;
			$req = PdoGsb::$monPdo->prepare ( "select count(*) as nblignesfrais from fichefrais 
        where fichefrais.mois = :mois and fichefrais.idvisiteur = :idVisiteur " );
			$req->execute ( array (
					'mois' => $mois,
					'idVisiteur' => $idVisiteur 
			) );
			$laLigne = $req->fetch ();
			if ($laLigne ['nblignesfrais'] == 0) {
				$ok = true;
			}
			return $ok;
		}
		/**
		 * Retourne le dernier mois en cours d'un visiteur
		 *
		 * @param
		 *        	$idVisiteur
		 * @return le mois sous la forme aaaamm
		 */
		public function dernierMoisSaisi($idVisiteur) {
			$req = PdoGsb::$monPdo->prepare ( "select max(mois) as dernierMois from fichefrais where fichefrais.idvisiteur = :idVisiteur" );
			$req->execute ( array (
					'idVisiteur' => $idVisiteur 
			) );
			$laLigne = $req->fetch ();
			$dernierMois = $laLigne ['dernierMois'];
			return $dernierMois;
		}
		
		/**
		 * Crée une nouvelle fiche de frais et les lignes de frais au forfait pour un visiteur et un mois donnés
		 *
		 * récupère le dernier mois en cours de traitement, met à 'CL' son champs idEtat, crée une nouvelle fiche de frais
		 * avec un idEtat à 'CR' et crée les lignes de frais forfait de quantités nulles
		 * 
		 * @param
		 *        	$idVisiteur
		 * @param $mois sous
		 *        	la forme aaaamm
		 */
		public function creeNouvellesLignesFrais($idVisiteur, $mois) {
			$dernierMois = $this->dernierMoisSaisi ( $idVisiteur );
			$laDerniereFiche = $this->getLesInfosFicheFrais ( $idVisiteur, $dernierMois );
			if ($laDerniereFiche ['idEtat'] == 'CR') {
				$this->majEtatFicheFrais ( $idVisiteur, $dernierMois, 'CL', 0 );
			}
			$req = PdoGsb::$monPdo->prepare ( "insert into fichefrais(idvisiteur,mois,nbJustificatifs,montantValide,dateModif,idEtat) 
        values(:idVisiteur,:mois,0,0,now(),'CR')" );
			$req->execute ( array (
					'idVisiteur' => $idVisiteur,
					'mois' => $mois 
			) );
			$lesIdFrais = $this->getLesIdFrais ();
			foreach ( $lesIdFrais as $uneLigneIdFrais ) {
				$unIdFrais = $uneLigneIdFrais ['idfrais'];
				$req = PdoGsb::$monPdo->prepare ( "insert into lignefraisforfait(idvisiteur,mois,idFraisForfait,quantite) 
            values(:idVisiteur,:mois ,:unIdFrais,0)" );
				$req->execute ( array (
						'idVisiteur' => $idVisiteur,
						'mois' => $mois,
						'unIdFrais' => $unIdFrais 
				) );
			}
		}
		/**
		 * Crée un nouveau frais hors forfait pour un visiteur un mois donné
		 * à partir des informations fournies en paramètre
		 *
		 * @param
		 *        	$idVisiteur
		 * @param $mois sous
		 *        	la forme aaaamm
		 * @param $libelle :
		 *        	le libelle du frais
		 * @param $date :
		 *        	la date du frais au format français jj//mm/aaaa
		 * @param $montant :
		 *        	le montant
		 */
		public function creeNouveauFraisHorsForfait($idVisiteur, $mois, $libelle, $date, $montant) {
			$dateFr = dateFrancaisVersAnglais ( $date );
			$req = PdoGsb::$monPdo->prepare ( "insert into lignefraishorsforfait (idVisiteur, mois, libelle, date, montant)
        values(:idVisiteur, :mois , :libelle , :dateFr, :montant)" );
			$req->execute ( array (
					'idVisiteur' => $idVisiteur,
					'mois' => $mois,
					'libelle' => $libelle,
					'dateFr' => $dateFr,
					'montant' => $montant 
			) );
			// Mise a jout de la date de modification des fiches de frais
			// $req2 = "update ficheFrais set dateModif = now()
			// where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
			// PdoGsb::$monPdo->exec($req2);
		}
		/**
		 * Supprime le frais hors forfait dont l'id est passé en argument
		 *
		 * @param
		 *        	$idFrais
		 */
		public function supprimerFraisHorsForfait($idFrais) {
			$req = PdoGsb::$monPdo->prepare ( "delete from lignefraishorsforfait where lignefraishorsforfait.id = :idFrais " );
			$req->execute ( array (
					'idFrais' => $idFrais 
			) );
		}
		/**
		 * Retourne les mois pour lesquel un visiteur a une fiche de frais
		 *
		 * @param
		 *        	$idVisiteur
		 * @return un tableau associatif de clé un mois -aaaamm- et de valeurs l'année et le mois correspondant
		 */
		public function getLesMoisDisponibles($idVisiteur) {
			$req = PdoGsb::$monPdo->prepare ( "select fichefrais.mois as mois from  fichefrais where fichefrais.idvisiteur =:idVisiteur 
        order by fichefrais.mois desc " );
			$req->execute ( array (
					'idVisiteur' => $idVisiteur 
			) );
			$lesMois = array ();
			$laLigne = $req->fetch ();
			while ( $laLigne != null ) {
				$mois = $laLigne ['mois'];
				$numAnnee = substr ( $mois, 0, 4 );
				$numMois = substr ( $mois, 4, 2 );
				$lesMois ["$mois"] = array (
						"mois" => "$mois",
						"numAnnee" => "$numAnnee",
						"numMois" => "$numMois" 
				);
				$laLigne = $req->fetch ();
			}
			return $lesMois;
		}
		/**
		 * Retourne les informations d'une fiche de frais d'un visiteur pour un mois donné
		 *
		 * @param
		 *        	$idVisiteur
		 * @param $mois sous
		 *        	la forme aaaamm
		 * @return un tableau avec des champs de jointure entre une fiche de frais et la ligne d'état
		 */
		public function getLesInfosFicheFrais($idVisiteur, $mois) {
			$req = PdoGsb::$monPdo->prepare ( "select ficheFrais.idEtat as idEtat, ficheFrais.dateModif as dateModif, ficheFrais.nbJustificatifs as nbJustificatifs, 
            ficheFrais.montantValide as montantValide, etat.libelle as libEtat from  fichefrais inner join Etat on ficheFrais.idEtat = Etat.id 
            where fichefrais.idvisiteur = :idVisiteur and fichefrais.mois = :mois " );
			$req->execute ( array (
					'idVisiteur' => $idVisiteur,
					'mois' => $mois 
			) );
			$laLigne = $req->fetch ();
			return $laLigne;
		}
		/**
		 * Modifie l'état et la date de modification d'une fiche de frais
		 *
		 * Modifie le champ idEtat et met la date de modif à aujourd'hui
		 * 
		 * @param
		 *        	$idVisiteur
		 * @param $mois sous
		 *        	la forme aaaamm
		 */
		public function majEtatFicheFrais($idVisiteur, $mois, $etat, $montant) {
			$req = PdoGsb::$monPdo->prepare ( "update ficheFrais set idEtat = :etat, dateModif = now(), montantValide = :montant
        where fichefrais.idvisiteur =:idVisiteur and fichefrais.mois = :mois" );
			$req->execute ( array (
					'etat' => $etat,
					'montant' => $montant,
					'idVisiteur' => $idVisiteur,
					'mois' => $mois 
			) );
		}
		public function getLesMoiscloture($idVisiteur) {
			$req = PdoGsb::$monPdo->prepare ( "select  fichefrais.mois as mois from  fichefrais where fichefrais.idvisiteur = :idVisiteur and fichefrais.idEtat='CL' 
        order by fichefrais.mois desc " );
			$req->execute ( array (
					'idVisiteur' => $idVisiteur 
			) );
			$lesMois = array ();
			$laLigne = $req->fetch ();
			while ( $laLigne != null ) {
				$mois = $laLigne ['mois'];
				$numAnnee = substr ( $mois, 0, 4 );
				$numMois = substr ( $mois, 4, 2 );
				$lesMois ["$mois"] = array (
						"mois" => "$mois",
						"numAnnee" => "$numAnnee",
						"numMois" => "$numMois" 
				);
				$laLigne = $req->fetch ();
			}
			return $lesMois;
		}
		
		/**
		 * Retourne les informations d'un visiteur
		 *
		 * @param
		 *        	$idvisiteur
		 * @return l'id, nom et le prenom sous la forme d'un tableau associatif
		 */
		public function getVisiteur($idvisiteur) {
			$req = PdoGsb::$monPdo->prepare ( "select  visiteur.nom as nom, visiteur.prenom as prenom from visiteur
        where visiteur.id= :idVisiteur" );
			$req->execute ( array (
					'idVisiteur' => $idvisiteur 
			) );
			$ligne = $req->fetch ();
			return $ligne;
		}
		public function SetLibelleFraisHorsForfait($idfrais) {
			$req = PdoGsb::$monPdo->prepare ( "select  libelle from lignefraishorsforfait
        where lignefraishorsforfait.id = :idFrais" );
			$req->execute ( array (
					'idFrais' => $idfrais 
			) );
			$libelle = $req->fetch ();
			$titre = 'REFUSE ' . $libelle [0];
			$req2 = PdoGsb::$monPdo->prepare ( "update lignefraishorsforfait set libelle = :titre where lignefraishorsforfait.id = :idFrais" );
			$req2->execute ( array (
					'titre' => $titre,
					'idFrais' => $idfrais 
			) );
		}
		public function repportFrais($idfrais, $mois, $visiteur) {
			$newmois = $this->dernierMoisSaisi ( $visiteur );
			$etat = $this->getEtat ( $visiteur, $newmois );
			
			if ($etat ["idEtat"] == "CL") {
				
				$this->creeNouvellesLignesFrais ( $visiteur, $mois );
				$newmois = $this->dernierMoisSaisi ( $visiteur );
				$this->majLigneFraisHorsForfait ( $idfrais, $newmois );
			} else {
				
				$this->majLigneFraisHorsForfait ( $idfrais, $newmois );
			}
		}
		public function majLigneFraisHorsForfait($idfrais, $newmois) {
			$req = PdoGsb::$monPdo->prepare ( "update lignefraishorsforfait set lignefraishorsforfait.mois = :newMois
        where lignefraishorsforfait.id= :idFrais " );
			$req->execute ( array (
					'newMois' => $newmois,
					'idFrais' => $idfrais 
			) );
		}
		public function getEtat($visiteur, $mois) {
			$req = PdoGsb::$monPdo->prepare ( "select fichefrais.idEtat
        from fichefrais
        where fichefrais.idVisiteur= :visiteur and fichefrais.mois = :mois" );
			$req->execute ( array (
					'visiteur' => $visiteur,
					'mois' => $mois 
			) );
			$ligne = $req->fetch ();
			return $ligne;
		}
		
		/**
		 * public function GetlesFicheValider(){
		 * $req = "select DISTINCT ficheFrais.idVisiteur as idVisiteur, visiteur.nom as nom, visiteur.prenom as prenom, fichefrais.montantValide as montantValide, ficheFrais.mois as mois
		 * from fichefrais
		 * inner join visiteur
		 * on ficheFrais.idVisiteur = visiteur.id
		 * where idEtat ='VA'";
		 * $res = PdoGsb::$monPdo->query($req);
		 * $lesLignes = $res->fetchAll();
		 * return $lesLignes;
		 * }
		 */
		
		/**
		 * public function GetlesFicheMiseEnpaiement(){
		 * $req = "select DISTINCT ficheFrais.idVisiteur as idVisiteur, visiteur.nom as nom, visiteur.prenom as prenom, fichefrais.montantValide as montantValide, ficheFrais.mois as mois
		 * from fichefrais
		 * inner join visiteur
		 * on ficheFrais.idVisiteur = visiteur.id
		 * where idEtat ='MP'";
		 * $res = PdoGsb::$monPdo->query($req);
		 * $lesLignes = $res->fetchAll();
		 * return $lesLignes;
		 * } *
		 */
		
		/**
		 * Retourne les visiteurs ayant des fiche de frais cloturée
		 *
		 * Retourne tout les visiteurs ayant des fiche de frais cloturée
		 */
		public function GetlesVisiteurFicheCloture() {
			$req = "select DISTINCT ficheFrais.idVisiteur as idVisiteur,  visiteur.nom as nom, visiteur.prenom as prenom
             from  fichefrais 
             inner join visiteur
             on ficheFrais.idVisiteur = visiteur.id 
            where idEtat ='CL'";
			$res = PdoGsb::$monPdo->query ( $req );
			$lesLignes = $res->fetchAll ();
			return $lesLignes;
		}
		public function GetlesFicheEtat($etat) {
			$req = PdoGsb::$monPdo->prepare ( "select DISTINCT ficheFrais.idVisiteur as idVisiteur,  visiteur.nom as nom, visiteur.prenom as prenom, fichefrais.montantValide as montantValide, ficheFrais.mois as mois
             from  fichefrais 
             inner join visiteur
             on ficheFrais.idVisiteur = visiteur.id 
            where idEtat = :etat" );
			$req->execute ( array (
					'etat' => $etat 
			) );
			$lesLignes = $req->fetchAll ();
			return $lesLignes;
		}
	}
	
	?>