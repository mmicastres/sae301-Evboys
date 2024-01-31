<?php
/**
 * Définition de la classe Categories
 */
class Categories {
    private int $_id_categories;
    private string $_categorie;
        
    //Constructeur
    public function __construct(array $donnees){
    //Initialisation d'un produit à partir d'un tableau de données
        if(isset($donnees['id_categories'])){ $this->_id_categories =$donnees['id_categories'];}
        if(isset($donnees['categorie'])){ $this->_categorie =$donnees['categorie'];}
        
    }
    //Getters
    public function id_categories(){return $this -> _id_categories;}
    public function categorie() { return $this->_categorie;}
	
    //Setters
    public function setId_categories(int $id_categories){ $this->_id_categories= $id_categories;}
    public function setCategorie(string $categorie) { $this->_categorie= $categorie; }

}
?>