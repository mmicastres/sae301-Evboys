<?php
/**
 * définition de la classe Projets
 */
class Publie
{
    private int $_id_projets;
    private int $_id_utilisateur;





    //Constructeur
    public function __construct(array $donnees)
    {
        // initialisation d'un produit à partir d'un tableau de données
        if (isset($donnees['id_projets'])) {
            $this->_id_projets = $donnees['id_projets'];
        }
        if (isset($donnees['id_utilisateur'])) {
            $this->_id_utilisateur = $donnees['id_utilisateur'];
        }

    }
    //Getters
    public function id_projets()
    {
        return $this->_id_projets;
    }
    public function id_utilisateur()
    {
        return $this->_id_utilisateur;
    }

    //Setters
    public function setId_projets(int $id_projets)
    {
        $this->_id_projets = $id_projets;
    }
    public function setId_utilisateur(int $id_utilisateur)
    {
        $this->_id_utilisateur = $id_utilisateur;
    }

}
?>