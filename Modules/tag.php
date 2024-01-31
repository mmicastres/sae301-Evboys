<?php
/**
 * Définition de la classe Tags
 */
class Tag
{
    private int $_id_projets;
    private int $_id_tags;

    //Constructeur
    public function __construct(array $donnees)
    {
        //Initialisation d'un produit à partir d'un tableau de données
        if (isset($donnees['id_projets'])) {
            $this->_id_projets = $donnees['id_projets'];
        }
        if (isset($donnees['id_tags'])) {
            $this->_id_tags = $donnees['id_tags'];
        }

    }
    //Getters
    public function id_projets()
    {
        return $this->_id_projets;
    }
    public function id_tags()
    {
        return $this->_id_tags;
    }

    //Setters
    public function setId_projets(int $id_projets)
    {
        $this->_id_projets = $id_projets;
    }
    public function setId_tags(int $id_tags)
    {
        $this->_id_tags = $id_tags;
    }


}
?>