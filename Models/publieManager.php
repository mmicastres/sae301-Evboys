<?php
class PublieManager
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
   * @return Publie[]
   */

  public function ajoutPublie(Publie $publie)
  {
    // requete d'ajout dans la BD
    $req = "INSERT INTO `sae301_publie`(`id_projets`,`id_utilisateur`) VALUES (?,?)";
    $stmt = $this->_db->prepare($req);
    $req = $stmt->execute(array($publie->id_projets(), $publie->id_utilisateur()));
    // pour debuguer les requêtes SQL
    $errorInfo = $stmt->errorInfo();
    if ($errorInfo[0] != 0) {
      print_r($errorInfo);
    }
    return $req;
  }
  public function supprimerPublie($idProjet)
{
    $req = "DELETE FROM sae301_publie WHERE id_projets = :id_projets";
    $stmt = $this->_db->prepare($req);
    $stmt->execute([':id_projets' => $idProjet]);
    $errorInfo = $stmt->errorInfo();

    if ($errorInfo[0] != 0) {
        print_r($errorInfo);
        return false; // Échec
    }

    return true; // Réussite
}
  //Fonction du Formulaire préremplie avec les informations du projets sélectionné pour la modif
  public function formPro($idpro)
  {
    $req = "SELECT id_projets,titre,img,description,lien_demo,lien_sources,validation,sae301_projets.id_contexte,sae301_projets.id_categories,sae301_contexte.id,sae301_categories.categorie
		FROM sae301_projets
		JOIN sae301_contexte ON sae301_projets.id_contexte = sae301_contexte.id_contexte 
		JOIN sae301_categories ON sae301_projets.id_categories = sae301_categories.id_categories
		WHERE id_projets=?";
    $stmt = $this->_db->prepare($req);
    $stmt->execute([$idpro]);

    $errorInfo = $stmt->errorInfo();
    if ($errorInfo[0] != 0) {
      print_r($errorInfo);
    }

    $pros = [];
    while ($donnees = $stmt->fetch((PDO::FETCH_ASSOC))) {
      $donnees['img'] = base64_encode($donnees['img']);
      $donnees = array_map(function ($value) {
        return is_string($value) ? utf8_encode($value) : $value;
      }, $donnees);
      $utilisateur = new Utilisateur($donnees);
      $pro = new Projets($donnees, $utilisateur);
      $pros[] = $pro;
    }
    return $pros;
  }
  // Récupérer les projets au quel l'utilisateur connecté au quel il participe
  public function projetsUtilisateur($idperso)
  {
    $pros = [];
    $req = "SELECT sae301_projets.id_projets, sae301_projets.titre,sae301_projets.description
		FROM sae301_publie
		JOIN sae301_projets ON sae301_publie.id_projets = sae301_projets.id_projets
		WHERE sae301_publie.id_utilisateur = :idperso;";
    $stmt = $this->_db->prepare($req);
    $stmt->execute(array(":idperso" => $idperso));

    $errorInfo = $stmt->errorInfo();
    if ($errorInfo[0] != 0) {
      print_r($errorInfo);
    }

    while ($donnees = $stmt->fetch()) {
      $donnees = array_map(function ($value) {
        return is_string($value) ? utf8_encode($value) : $value;
      }, $donnees);
      $utilisateur = new Utilisateur($donnees);
      $pros[] = new Projets($donnees, $utilisateur);
    }
    return $pros;
  }

  public function detail($idpro)
  {
    $req = "SELECT sae301_projets.id_projets,sae301_projets.titre,sae301_projets.img,sae301_projets.description,sae301_projets.lien_demo,sae301_projets.lien_sources,sae301_utilisateur.prenom,sae301_utilisateur.nom,sae301_contexte.id,sae301_categories.categorie 
		FROM sae301_publie 
		JOIN sae301_projets ON sae301_publie.id_projets = sae301_projets.id_projets 
		JOIN sae301_utilisateur ON sae301_publie.id_utilisateur = sae301_utilisateur.id_utilisateur 
		JOIN sae301_contexte ON sae301_projets.id_contexte = sae301_contexte.id_contexte 
		JOIN sae301_categories ON sae301_projets.id_categories = sae301_categories.id_categories
		WHERE sae301_projets.id_projets=?";
    $stmt = $this->_db->prepare($req);
    $stmt->execute([$idpro]);

    $errorInfo = $stmt->errorInfo();
    if ($errorInfo[0] != 0) {
      print_r($errorInfo);
    }

    $det = [];
    while ($donnees = $stmt->fetch((PDO::FETCH_ASSOC))) {
      $donnees['img'] = base64_encode($donnees['img']);
      $donnees = array_map(function ($value) {
        return is_string($value) ? utf8_encode($value) : $value;
      }, $donnees);
      $utilisateur = new Utilisateur($donnees);
      $pro = new Projets($donnees, $utilisateur);
      $det[] = $pro;
    }
    return $det;
  }

  public function projetsAdmin()
  {
    $req = "SELECT id_projets,titre,img,description,lien_demo,lien_sources,validation,sae301_projets.id_contexte,sae301_projets.id_categories,sae301_contexte.id,sae301_categories.categorie
		FROM sae301_projets
		JOIN sae301_contexte ON sae301_projets.id_contexte = sae301_contexte.id_contexte 
		JOIN sae301_categories ON sae301_projets.id_categories = sae301_categories.id_categories";
    $stmt = $this->_db->prepare($req);
    $stmt->execute();

    $errorInfo = $stmt->errorInfo();
    if ($errorInfo[0] != 0) {
      print_r($errorInfo);
    }

    $pros = [];
    while ($donnees = $stmt->fetch((PDO::FETCH_ASSOC))) {
      $donnees['img'] = base64_encode($donnees['img']);
      $donnees = array_map(function ($value) {
        return is_string($value) ? utf8_encode($value) : $value;
      }, $donnees);
      $utilisateur = new Utilisateur($donnees);
      $pro = new Projets($donnees, $utilisateur);
      $pros[] = $pro;
    }
    return $pros;
  }
}
