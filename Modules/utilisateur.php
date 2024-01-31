<?php
/**
 * Définition de la classe Utilisateur
 */
class Utilisateur
{
    private int $_id_utilisateur;
    private string $_nom;
    private string $_prenom;
    private string $_id_iut;
    private string $_email;
    private string $_mdp;
    private bool $_admin;

    //Constructeur
    public function __construct(array $donnees)
    {
        //Initialisation d'un produit à partir d'un tableau de données
        if (isset($donnees['id_utilisateur'])) {
            $this->_id_utilisateur = $donnees['id_utilisateur'];
        }
        if (isset($donnees['nom'])) {
            $this->_nom = $donnees['nom'];
        }
        if (isset($donnees['prenom'])) {
            $this->_prenom = $donnees['prenom'];
        }
        if (isset($donnees['id_iut'])) {
            $this->_id_iut = $donnees['id_iut'];
        }
        if (isset($donnees['email'])) {
            $this->_email = $donnees['email'];
        }
        if (isset($donnees['mdp'])) {
            $this->_mdp = $donnees['mdp'];
        }
        if (isset($donnees['admin'])) {
            $this->_admin = $donnees['admin'];
        }
    }
    //Getters
    public function id_utilisateur()
    {
        return $this->_id_utilisateur;
    }
    public function nom()
    {
        return $this->_nom;
    }
    public function prenom()
    {
        return $this->_prenom;
    }
    public function id_iut()
    {
        return $this->_id_iut;
    }
    public function email()
    {
        return $this->_email;
    }
    public function mdp()
    {
        return $this->_mdp;
    }
    public function admin()
    {
        return $this->_admin;
    }
    //Setters
    public function setId_utilisateur(int $id_utilisateur)
    {
        $this->_id_utilisateur = $id_utilisateur;
    }
    public function setNom(string $nom)
    {
        $this->_nom = $nom;
    }
    public function setPrenom(string $prenom)
    {
        $this->_prenom = $prenom;
    }
    public function setId_iut(string $id_iut)
    {
        $this->_id_iut = $id_iut;
    }
    public function setEmail(string $email)
    {
        $this->_email = $email;
    }
    public function setMdp(string $mdp)
    {
        $this->_mdp = $mdp;
    }
    public function setAdmin(bool $admin)
    {
        $this->_admin = $admin;
    }
}
?>