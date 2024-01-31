<?php

/**
 * Définition d'une classe permettant de gérer les projets 
 *   en relation avec la base de données	
 */
class ContexteManager
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
     * @return Contexte[]
     */

    public function getContexte()
    {
        $contextes = array();
        $req = "SELECT `id_contexte`, `id`, `semestre`, `intitule`
		FROM sae301_contexte";
        $stmt = $this->_db->prepare($req);
        $stmt->execute();

        // pour debuguer les requêtes SQL
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        // récup des données
        while ($donnees = $stmt->fetch((PDO::FETCH_ASSOC))) {
            $contextes[] = $donnees;
        }
        return $contextes;
    }

    public function modifContexte($contexte)
    {
        $req = "SET NAMES utf8; UPDATE sae301_contexte SET id = :id, "
            . "semestre = :semestre,"
            . "intitule = :intitule"
            . " WHERE id_contexte = :id_contexte";
        $stmt = $this->_db->prepare($req);
        $stmt->execute(
            array(
                ":id" => $contexte->id(),
                ":semestre" => $contexte->semestre(),
                ":intitule" => $contexte->intitule(),
                ":id_contexte" => $contexte->id_contexte()
            )
        );
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        return $stmt->rowCount();
    }

    public function ajoutContexte(Contexte $contexte)
	{
		$stmt = $this->_db->prepare("SELECT max(id_contexte) AS maximum 
		FROM sae301_contexte");
		$stmt->execute();
		$contexte->setId_contexte($stmt->fetchColumn() + 1);

		// requete d'ajout dans la BD
		$req = "INSERT INTO `sae301_contexte`(`id_contexte`, `id`,`semestre`,`intitule`) VALUES (?,?,?,?)";
		$stmt = $this->_db->prepare($req);

		$req = $stmt->execute(array($contexte->id_contexte(), $contexte->id(), $contexte->semestre(), $contexte->intitule()));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $req;
	}
    public function supprContexte($contexte)
    {
        $req = "DELETE FROM sae301_contexte
                WHERE id_contexte = :id_contexte";
        $stmt = $this->_db->prepare($req);
        $stmt->execute([':id_contexte'=>$contexte]);
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        return $stmt;
    }
}
