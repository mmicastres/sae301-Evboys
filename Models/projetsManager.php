<?php

/**
 * Définition d'une classe permettant de gérer les projets 
 *   en relation avec la base de données	
 */
class ProjetsManager
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
	 * retourne l'ensemble des itinéraires présents dans la BD 
	 * @return Projets[]
	 */

	//Affichage à la page d'accueil

	public function presentation()
	{
		$pros = array();
		$req = "SELECT `id_projets`, `titre`, `img`
		FROM sae301_projets";
		$stmt = $this->_db->prepare($req);
		$stmt->execute();

		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// récup des données
		while ($donnees = $stmt->fetch((PDO::FETCH_ASSOC))) {
			$donnees['img'] = base64_encode($donnees['img']);
			$utilisateur = new Utilisateur($donnees);
			$pros[] = new Projets($donnees, $utilisateur);
		}
		return $pros;
	}

	//Affichage de la liste des projets présents dans la bdd

	public function getList()
	{
		$pros = array();
		$req = "SELECT sae301_projets.id_projets,sae301_projets.titre,sae301_utilisateur.prenom,sae301_contexte.id,sae301_categories.categorie 
		FROM sae301_publie 
		JOIN sae301_projets ON sae301_publie.id_projets = sae301_projets.id_projets 
		JOIN sae301_utilisateur ON sae301_publie.id_utilisateur = sae301_utilisateur.id_utilisateur 
		JOIN sae301_contexte ON sae301_projets.id_contexte = sae301_contexte.id_contexte 
		JOIN sae301_categories ON sae301_projets.id_categories = sae301_categories.id_categories
		GROUP BY sae301_projets.id_projets";
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
			$utilisateur = new Utilisateur($donnees);
			$pros[] = new Projets($donnees, $utilisateur);
		}
		return $pros;
	}

	public function supprimerProjet($idProjet)
{
    $req = "DELETE FROM sae301_projets WHERE id_projets = :id_projets";
    $stmt = $this->_db->prepare($req);
    $stmt->execute([':id_projets' => $idProjet]);
    $errorInfo = $stmt->errorInfo();

    if ($errorInfo[0] != 0) {
        print_r($errorInfo);
        return false; // Échec
    }

    return true; // Réussite
}

	//Insertion des données saisie grâce a la fonction précédente dans la BDD

	public function modifProjet($projet)
	{
		$req = "SET NAMES utf8; UPDATE sae301_projets SET titre = :titre, "
			. "img = :img, "
			. "description = :description, "
			. "lien_demo  = :lien_demo, "
			. "lien_sources = :lien_sources, "
			. "id_contexte = :id_contexte, "
			. "id_categories= :id_categories "
			. " WHERE id_projets = :id_projets";


		$stmt = $this->_db->prepare($req);
		$stmt->execute(
			array(
				":id_projets" => $projet->id_projets(),
				":titre" => $projet->titre(),
				":img" => $projet->img(),
				":description" => $projet->description(),
				":lien_demo" => $projet->lien_demo(),
				":lien_sources" => $projet->lien_sources(),
				":id_contexte" => $projet->id_contexte(),
				":id_categories" => $projet->id_categories()

			)
		);
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $stmt->rowCount();
	}

	public function ajoutProjet(Projets $projets)
	{
		$stmt = $this->_db->prepare("SELECT max(id_projets) AS maximum 
		FROM sae301_projets");
		$stmt->execute();
		$projets->setId_projets($stmt->fetchColumn() + 1);

		// requete d'ajout dans la BD
		$req = "INSERT INTO `sae301_projets`(`id_projets`, `titre`, `img`, `description`, `lien_demo`, `lien_sources`, `id_contexte`, `id_categories`) VALUES (?,?,?,?,?,?,?,?)";
		$stmt = $this->_db->prepare($req);

		$req = $stmt->execute(array($projets->id_projets(), $projets->titre(), $projets->img(), $projets->description(), $projets->lien_demo(), $projets->lien_sources(), $projets->id_contexte(), $projets->id_categories()));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $req;
	}

	public function recherche($titre,$description) {
			$req = "SELECT id_projets, titre, description FROM sae301_projets";
			$cond = '';
		
			if ($titre <> "") {
				$cond = $cond . " titre LIKE '%" . $titre . "%'";
			}
		
			if ($description <> "") {
				if ($cond <> "") $cond .= " AND ";
				$cond = $cond . " description LIKE '%" . $description . "%'";
			}
		
			if ($cond <> "") {
				$req .= " WHERE " . $cond;
			}

		// execution de la requete				
		$stmt = $this->_db->prepare($req);
		$stmt->execute();
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		$pros = array();
		while ($donnees = $stmt->fetch())
		{
			$utilisateur = new Utilisateur($donnees);
			$pros[] = new Projets($donnees, $utilisateur);
		}
		return $pros;
	}
	public function getProjet($idprojet)
	{
		$pros = array();
		$req = "SELECT sae301_projets.id_projets,sae301_projets.titre,sae301_utilisateur.prenom,sae301_contexte.id,sae301_categories.categorie 
		FROM sae301_publie 
		JOIN sae301_projets ON sae301_publie.id_projets = sae301_projets.id_projets 
		JOIN sae301_utilisateur ON sae301_publie.id_utilisateur = sae301_utilisateur.id_utilisateur 
		JOIN sae301_contexte ON sae301_projets.id_contexte = sae301_contexte.id_contexte 
		JOIN sae301_categories ON sae301_projets.id_categories = sae301_categories.id_categories
		WHERE sae301_projets.id_projets= :id_projets";
		$stmt = $this->_db->prepare($req);
		$stmt->execute([':id_projets' => $idprojet]);

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
			$utilisateur = new Utilisateur($donnees);
			$pros[] = new Projets($donnees, $utilisateur);
		}
		return $pros;
	}
}
