<?php

/**
 * Définition d'une classe permettant de gérer les utilisateurs 
 * en relation avec la base de données
 *
 */

class UtilisateurManager
{
	private $_db; // Instance de PDO - objet de connexion au SGBD

	/** 
	 * Constructeur = initialisation de la connexion vers le SGBD
	 */
	public function __construct($db)
	{
		$this->_db = $db;
	}

	/**
	 * verification de l'identité d'un utilisateur (Login/password)
	 * @param string $login
	 * @param string $password
	 * @return utilisateur si authentification ok, false sinon
	 */
	public function verif_identification($login, $mdp)
	{
		//echo $login." : ".$password;
		$req = "SELECT `id_utilisateur`, `nom`, `prenom`, `id_iut`, `email`, `admin`,`mdp` 
		FROM sae301_utilisateur
		WHERE id_iut=:login";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array(":login" => $login));
		//Sécurité du mot de passe avec le hashage qui se fait lors de l'inscription
		if ($data = $stmt->fetch()) {
			if (password_verify($mdp, $data["mdp"])) {
				$utilisateur = new Utilisateur($data);
				return $utilisateur;
			}

		} else
			return false;
	}
	public function add(Utilisateur $utilisateur)
	{
		// calcul d'un nouveau code d'itineraire non déja utilisé = Maximum + 1
		$stmt = $this->_db->prepare("SELECT max(id_utilisateur) AS maximum 
		FROM sae301_utilisateur");
		$stmt->execute();
		$utilisateur->setId_utilisateur($stmt->fetchColumn() + 1);

		//Vérification de l'existance ou non de l'id IUT dans la BDD

		$stmt = $this->_db->prepare("SELECT COUNT(*) AS count 
		FROM sae301_utilisateur
		WHERE id_iut = :id_iut");
		$stmt->bindValue(':id_iut', $utilisateur->id_iut(), PDO::PARAM_STR);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($res['count'] > 0) {
			// L'information existe déjà dans la base de données

			echo "Cette information existe déjà. Veuillez fournir une information unique.";
		}


		// requete d'ajout dans la BD
		$req = "INSERT INTO `sae301_utilisateur`(`id_utilisateur`, `nom`, `prenom`,`mdp`, `id_iut`,`email`) VALUES (?,?,?,?,?,?)";
		$stmt = $this->_db->prepare($req);
		//hashage du mot de passe pour le sécuriser
		$res = $stmt->execute(array($utilisateur->id_utilisateur(), $utilisateur->nom(), $utilisateur->prenom(), password_hash($utilisateur->mdp(), PASSWORD_DEFAULT), $utilisateur->id_iut(), $utilisateur->email()));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $res;

	}

	public function perso($idperso)
	{
		$reqUtilisateur = "SELECT `id_utilisateur`, `nom`, `prenom`, `id_iut`, `email`
                        FROM sae301_utilisateur
                        WHERE id_utilisateur = :idperso;";
		$stmtUtilisateur = $this->_db->prepare($reqUtilisateur);
		$stmtUtilisateur->execute(array(":idperso" => $idperso));

		$errorInfoUtilisateur = $stmtUtilisateur->errorInfo();
		if ($errorInfoUtilisateur[0] != 0) {
			print_r($errorInfoUtilisateur);
		}

		$utilisateur = null;

		if ($dataUtilisateur = $stmtUtilisateur->fetch()) {
			$utilisateur = new Utilisateur($dataUtilisateur);

			$reqProjets = "SELECT sae301_projets.id_projets, sae301_projets.titre
                        FROM sae301_publie
                        JOIN sae301_projets ON sae301_publie.id_projets = sae301_projets.id_projets
                        WHERE sae301_publie.id_utilisateur = :idperso;";
			$stmtProjets = $this->_db->prepare($reqProjets);
			$stmtProjets->execute(array(":idperso" => $idperso));

			$errorInfoProjets = $stmtProjets->errorInfo();
			if ($errorInfoProjets[0] != 0) {
				print_r($errorInfoProjets);
			}

			$projets = array();

			while ($dataProjets = $stmtProjets->fetch()) {
				$dataProjets = array_map(function ($value) {
					return is_string($value) ? utf8_encode($value) : $value;
				}, $dataProjets);
				$projets[] = new Projets($dataProjets, $utilisateur);
			}
		}

		return array('utilisateur' => $utilisateur, 'projets' => $projets);
	}

	public function getUtilisateurs()
	{
		$uti = array();
		$req = "SELECT * FROM sae301_utilisateur";
		$stmt = $this->_db->prepare($req);
		$stmt->execute();

		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// récup des données
		while ($donnees = $stmt->fetch()) {
			$donnees = array_map(function ($value) {
				return is_string($value) ? utf8_encode($value) : $value;
			}, $donnees);
			$uti[] = new Utilisateur($donnees);
		}
		return $uti;
	}

	public function ajoutUtilisateur(Utilisateur $utilisateur)
	{
		$stmt = $this->_db->prepare("SELECT max(id_utilisateur) AS maximum FROM sae301_utilisateur");
		$stmt->execute();
		$utilisateur->setId_utilisateur($stmt->fetchColumn() + 1);

		// requete d'ajout dans la BD
		$req = "INSERT INTO `sae301_utilisateur`(`id_utilisateur`,`nom`, `prenom`, `id_iut`, `email`,`mdp`) VALUES (?,?,?,?,?,?)";
		$stmt = $this->_db->prepare($req);

		$req = $stmt->execute(array($utilisateur->id_utilisateur(), $utilisateur->nom(), $utilisateur->prenom(), $utilisateur->id_iut(), $utilisateur->email(),  password_hash($utilisateur->mdp(), PASSWORD_DEFAULT)));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $req;
	}
	public function supprUtilisateur($utilisateur)
	{
		$req = "DELETE FROM sae301_utilisateur
                WHERE id_utilisateur = :id_utilisateur";
		$stmt = $this->_db->prepare($req);
		$stmt->execute([':id_utilisateur' => $utilisateur]);
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $stmt;
	}
	public function modifUtilisateur($utilisateur)
	{
		$req = "SET NAMES utf8; UPDATE sae301_utilisateur SET nom = :nom,
				prenom = :prenom,
				id_iut = :id_iut,
				email = :email,
				mdp = :mdp
                WHERE id_utilisateur = :id_utilisateur";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(
			array(
				":nom" => $utilisateur->nom(),
				":prenom" => $utilisateur->prenom(),
				":id_iut" => $utilisateur->id_iut(),
				":email" => $utilisateur->email(),
				":mdp" =>  password_hash($utilisateur->mdp(), PASSWORD_DEFAULT),
				":id_utilisateur" => $utilisateur->id_utilisateur()
			)
		);
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $stmt->rowCount();
	}

}
?>