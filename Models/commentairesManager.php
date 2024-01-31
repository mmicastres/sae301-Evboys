<?php
class CommentairesManager
{

  private $_db; // Instance de PDO - objet de connexion au SGBD

  /**
   * Constructeur = initialisation de la connexion vers le SGBD
   */
  public function __construct($db)
  {
    $this->_db = $db;
  }


  public function listeCommentairesProjets($idpro)
  {
    $req = "SELECT * FROM `sae301_commentaires` WHERE id_projets = :idprojet ORDER BY id_commentaires";
    $stmt = $this->_db->prepare($req);
    $stmt->execute(["idprojet" => $idpro]);

    // Vérifier les erreurs
    $errorInfo = $stmt->errorInfo();
    if ($errorInfo[0] != 0) {
      print_r($errorInfo);
    }

    $coms = array();

    while ($donnees = $stmt->fetch()) {
      $donnees = array_map(function ($value) {
        return is_string($value) ? utf8_encode($value) : $value;
      }, $donnees);

      $coms[] = new Commentaires($donnees);
    }

    return $coms;
  }


  public function ajoutCommentaire(Commentaires $commentaire)
  {
    $stmt = $this->_db->prepare("SELECT max(id_commentaires) AS maximum 
		FROM sae301_commentaires");
    $stmt->execute();
    $commentaire->setId_commentaires($stmt->fetchColumn() + 1);
    // requete d'ajout dans la BD
    $req = "INSERT INTO `sae301_commentaires`(`commentaire`,`evaluation`,`id_projets`,`id_utilisateur`) VALUES (?,?,?,?)";
    $stmt = $this->_db->prepare($req);
    $req = $stmt->execute(array($commentaire->commentaire(), $commentaire->evaluation(), $commentaire->id_projets(), $commentaire->id_utilisateur()));

    // pour debuguer les requêtes SQL
    $errorInfo = $stmt->errorInfo();
    if ($errorInfo[0] != 0) {
      print_r($errorInfo);
    }
    return $req;
  }
}
