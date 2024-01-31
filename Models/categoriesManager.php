<?php

/**
 * Définition d'une classe permettant de gérer les projets 
 *   en relation avec la base de données	
 */
class CategorieManager
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
     * @return Categorie[]
     */

    public function getCategories()
    {
        $cats = array();
        $req = "SELECT `id_categories`, `categorie`
		FROM sae301_categories";
        $stmt = $this->_db->prepare($req);
        $stmt->execute();

        // pour debuguer les requêtes SQL
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        // récup des données
        while ($donnees = $stmt->fetch((PDO::FETCH_ASSOC))) {
            $cats[] = $donnees;
        }
        return $cats;
    }

    public function modifCategorie($categorie)
    {
        $req = "SET NAMES utf8; UPDATE sae301_categories SET categorie = :categorie 
                WHERE id_categories = :id_categories";
        $stmt = $this->_db->prepare($req);
        $stmt->execute(
            array(
                ":categorie" => $categorie->categorie(),
                ":id_categories" => $categorie->id_categories()
            )
        );
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        return $stmt->rowCount();
    }
    public function ajoutCategorie(Categories $categories)
	{
		$stmt = $this->_db->prepare("SELECT max(id_categories) AS maximum 
		FROM sae301_categories");
		$stmt->execute();
		$categories->setId_categories($stmt->fetchColumn() + 1);

		// requete d'ajout dans la BD
		$req = "INSERT INTO `sae301_categories`(`id_categories`, `categorie`) VALUES (?,?)";
		$stmt = $this->_db->prepare($req);

		$req = $stmt->execute(array($categories->id_categories(), $categories->categorie()));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $req;
	}
    public function supprCategorie($categorie)
    {
        $req = "DELETE FROM sae301_categories
                WHERE id_categories = :id_categories";
        $stmt = $this->_db->prepare($req);
        $stmt->execute([':id_categories'=>$categorie]);
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        return $stmt;
    }

}
