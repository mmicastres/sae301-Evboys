<?php
/**
 * Définition de la classe Tags
 */
class Tags
{
    private int $_id_tags;
    private string $_nom;

    //Constructeur
    public function __construct(array $donnees)
    {
        //Initialisation d'un produit à partir d'un tableau de données
        if (isset($donnees['id_tags'])) {
            $this->_id_tags = $donnees['id_tags'];
        }
        if (isset($donnees['nom'])) {
            $this->_nom = $donnees['nom'];
        }
    }
    //Getters
    public function id_tags()
    {
        return $this->_id_tags;
    }
    public function nom()
    {
        return $this->_nom;
    }
    //Setters
    public function setId_tags(int $id_tags)
    {
        $this->_id_tags = $id_tags;
    }
    public function setNom(string $nom)
    {
        $this->_nom = $nom;
    }

}
?>