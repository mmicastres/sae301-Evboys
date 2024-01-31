<?php
class TagManager
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
   * @return Tag[]
   */

  public function getTags($idprojet)
  {
    $req = "SELECT * FROM sae301_tag WHERE id_projets = :idprojet";
    $stmt = $this->_db->prepare($req);
    $stmt->execute(array(':idprojet' => $idprojet));
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function ajoutTag(Tag $tag)
  {
    // requete d'ajout dans la BD
    $req = "INSERT INTO `sae301_tag`(`id_projets`,`id_tags`) VALUES (?,?)";
    $stmt = $this->_db->prepare($req);
    $req = $stmt->execute(array($tag->id_projets(), $tag->id_tags()));
    // pour debuguer les requêtes SQL
    $errorInfo = $stmt->errorInfo();
    if ($errorInfo[0] != 0) {
      print_r($errorInfo);
    }
    return $req;
  }

  public function supprimerTag($idprojet)
  {
    $req = "DELETE FROM sae301_tag WHERE id_projets = :idprojet";
    $stmt = $this->_db->prepare($req);
    $stmt->execute(array(':idprojet' => $idprojet));
  }

  public function tagPro($idprojet)
{
    $req = "SELECT * FROM sae301_tag
            JOIN sae301_projets  ON sae301_tag.id_projets = sae301_projets.id_projets
            JOIN sae301_tags ON sae301_tag.id_tags = sae301_tags.id_tags
            WHERE sae301_projets.id_projets = :id_projet";

    $stmt = $this->_db->prepare($req);
    $stmt->execute([':id_projet' => $idprojet]);

    // Récupérez les résultats sous forme d'un tableau associatif
    while ($donnees = $stmt->fetch()) {
			$donnees = array_map(function ($value) {
				return is_string($value) ? utf8_encode($value) : $value;
			}, $donnees);
			$tags[] = new Tags($donnees);
		}

    // Retournez les résultats
    return $tags;
}



}
