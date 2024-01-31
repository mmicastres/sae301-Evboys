<?php
/**
 * Définition de la classe Contexte
 */
class Contexte
{
    private int $_id_contexte;
    private string $_id;
    private int $_semestre;
    private string $_intitule;

    //Constructeur
    public function __construct(array $donnees)
    {
        //Initialisation d'un produit à partir d'un tableau de données
        if (isset($donnees['id_contexte'])) {
            $this->_id_contexte = $donnees['id_contexte'];
        }
        if (isset($donnees['id'])) {
            $this->_id = $donnees['id'];
        }
        if (isset($donnees['semestre'])) {
            $this->_semestre = $donnees['semestre'];
        }
        if (isset($donnees['intitule'])) {
            $this->_intitule = $donnees['intitule'];
        }

    }
    //Getters
    public function id_contexte()
    {
        return $this->_id_contexte;
    }
    public function id()
    {
        return $this->_id;
    }
    public function semestre()
    {
        return $this->_semestre;
    }
    public function intitule()
    {
        return $this->_intitule;
    }

    //Setters
    public function setId_contexte(int $id_contexte)
    {
        $this->_id_contexte = $id_contexte;
    }
    public function setId(string $id)
    {
        $this->_id = $id;
    }
    public function setSemestre(int $semestre)
    {
        $this->_semestre = $semestre;
    }
    public function setIntitule(string $intitule)
    {
        $this->_intitule = $intitule;
    }

}
?>