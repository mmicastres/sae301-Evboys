<?php
/**
 * définition de la classe Projets
 */
class Projets
{
    private int $_id_projets;
    private string $_titre;
    private string $_img;
    private string $_description;
    private string $_lien_demo;
    private string $_lien_sources;
    private bool $_validation;
    private int $_id_contexte;
    private int $_id_categories;

    private $_utilisateur;

    private string $_categorie;
    private string $_id;





    //Constructeur
    public function __construct(array $donnees, Utilisateur $utilisateur)
    {
        // initialisation d'un produit à partir d'un tableau de données
        if (isset($donnees['id_projets'])) {
            $this->_id_projets = $donnees['id_projets'];
        }
        if (isset($donnees['titre'])) {
            $this->_titre = $donnees['titre'];
        }
        if (isset($donnees['img'])) {
            $this->_img = $donnees['img'];
        }
        if (isset($donnees['description'])) {
            $this->_description = $donnees['description'];
        }
        if (isset($donnees['lien_demo'])) {
            $this->_lien_demo = $donnees['lien_demo'];
        }
        if (isset($donnees['lien_sources'])) {
            $this->_lien_sources = $donnees['lien_sources'];
        }
        if (isset($donnees['validation'])) {
            $this->_validation = $donnees['validation'];
        }
        if (isset($donnees['id_contexte'])) {
            $this->_id_contexte = $donnees['id_contexte'];
        }
        if (isset($donnees['id_categories'])) {
            $this->_id_categories = $donnees['id_categories'];
        }
        if (isset($donnees['categorie'])) {
            $this->_categorie = $donnees['categorie'];
        }
        if (isset($donnees['id'])) {
            $this->_id = $donnees['id'];
        }
        $this->_utilisateur = $utilisateur;
    }
    //Getters
    public function id_projets()
    {
        return $this->_id_projets;
    }
    public function titre()
    {
        return $this->_titre;
    }
    public function img()
    {
        return $this->_img;
    }
    public function description()
    {
        return $this->_description;
    }
    public function lien_demo()
    {
        return $this->_lien_demo;
    }
    public function lien_sources()
    {
        return $this->_lien_sources;
    }
    public function validation()
    {
        return $this->_validation;
    }
    public function id_contexte()
    {
        return $this->_id_contexte;
    }
    public function id_categories()
    {
        return $this->_id_categories;
    }
    public function id()
    {
        return $this->_id;
    }
    public function categorie()
    {
        return $this->_categorie;
    }

    public function getUtilisateur()
    {
        return $this->_utilisateur;
    }

    //Setters
    public function setId_projets(int $id_projets)
    {
        $this->_id_projets = $id_projets;
    }
    public function setTitre(string $titre)
    {
        $this->_titre = $titre;
    }
    public function setImg(string $img)
    {
        $this->_img = $img;
    }
    public function setDescription(string $description)
    {
        $this->_description = $description;
    }
    public function setLien_demo(string $lien_demo)
    {
        $this->_lien_demo = $lien_demo;
    }
    public function setLien_sources(string $lien_sources)
    {
        $this->_lien_sources = $lien_sources;
    }
    public function setValidation(bool $validation)
    {
        $this->_validation = $validation;
    }
    public function setId_contexte(int $id_contexte)
    {
        $this->_id_contexte = $id_contexte;
    }
    public function setId_categories(int $id_categories)
    {
        $this->_id_categories = $id_categories;
    }
    public function setId(string $id)
    {
        $this->_id = $id;
    }
    public function setCategorie(string $categorie)
    {
        $this->_categorie = $categorie;
    }


}
?>