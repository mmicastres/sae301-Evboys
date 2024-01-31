<?php

/**
 * Définition d'une classe permettant de gérer les projets 
 *   en relation avec la base de données	
 */
class TagsManager
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
     * @return Tags[]
     */

    public function getTags()
    {
        $tags = array();
        $req = "SELECT `id_tags`, `nom`
		FROM sae301_tags";
        $stmt = $this->_db->prepare($req);
        $stmt->execute();

        // pour debuguer les requêtes SQL
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        // récup des données
        while ($donnees = $stmt->fetch((PDO::FETCH_ASSOC))) {
            $tags[] = $donnees;
        }
        return $tags;
    }

    public function modifTags($tags)
    {
        $req = "SET NAMES utf8; UPDATE sae301_tags SET nom = :nom 
                WHERE id_tags = :id_tags";
        $stmt = $this->_db->prepare($req);
        $stmt->execute(
            array(
                ":nom" => $tags->nom(),
                ":id_tags" => $tags->id_tags()
            )
        );
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        return $stmt->rowCount();
    }
    public function ajoutTags(Tags $tags)
	{
		$stmt = $this->_db->prepare("SELECT max(id_tags) AS maximum 
		FROM sae301_tags");
		$stmt->execute();
		$tags->setId_tags($stmt->fetchColumn() + 1);

		// requete d'ajout dans la BD
		$req = "INSERT INTO `sae301_tags`(`id_tags`, `nom`) VALUES (?,?)";
		$stmt = $this->_db->prepare($req);

		$req = $stmt->execute(array($tags->id_tags(), $tags->nom()));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $req;
	}
    public function supprTags($tags)
    {
        $req = "DELETE FROM sae301_tags
                WHERE id_tags = :id_tags";
        $stmt = $this->_db->prepare($req);
        $stmt->execute([':id_tags'=>$tags]);
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        return $stmt;
    }

}
