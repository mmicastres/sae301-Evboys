<?php
/**
 * Définition de la classe Commentaires
 */
class Commentaires
{
    private int $_id_commentaires;
    private string $_commentaire;
    private int $_evaluation;
    private int $_id_projets;
    private int $_id_utilisateur;

    //Constructeur
    public function __construct(array $donnees)
    {
        //Initialisation d'un produit à partir d'un tableau de données

        if (isset($donnees['id_commentaires'])) {
            $this->_id_commentaires = $donnees['id_commentaires'];
        }
        if (isset($donnees['commentaire'])) {
            $this->_commentaire = $donnees['commentaire'];
        }
        if (isset($donnees['evaluation'])) {
            $this->_evaluation = $donnees['evaluation'];
        }
        if (isset($donnees['id_projets'])) {
            $this->_id_projets = $donnees['id_projets'];
        }
        if (isset($donnees['id_utilisateur'])) {
            $this->_id_utilisateur = $donnees['id_utilisateur'];
        }
    }
    //Getters
    public function id_commentaires()
    {
        return $this->_id_commentaires;
    }
    public function commentaire()
    {
        return $this->_commentaire;
    }
    public function evaluation()
    {
        return $this->_evaluation;
    }
    public function id_projets()
    {
        return $this->_id_projets;
    }
    public function id_utilisateur()
    {
        return $this->_id_utilisateur;
    }

    //Setters
    public function setId_commentaires(int $id_commentaires)
    {
        $this->_id_commentaires = $id_commentaires;
    }
    public function setCommentaire(string $commentaire)
    {
        $this->_commentaire = $commentaire;
    }
    public function setEvaluation(int $evaluation)
    {
        $this->_evaluation = $evaluation;
    }
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