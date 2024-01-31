<?php
include "Modules/tags.php";
include "Models/tagsManager.php";
/**
 * Définition d'une classe permettant de gérer les membres 
 *   en relation avec la base de données	
 */
class TagsController
{
    private $tagsManager; // instance du manager
    private $twig;

    /**
     * Constructeur = initialisation de la connexion vers le SGBD
     */
    public function __construct($db, $twig)
    {
        $this->tagsManager = new TagsManager($db);
        $this->twig = $twig;
    }

    /**
     * connexion
     * @param aucun
     * @return rien
     */
    public function formModifTags()
    {
        $tags = $this->tagsManager->getTags();
        echo $this->twig->render('tags.html.twig', array('acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'tags' => $tags));
    }
    public function modifDeTags()
    {
        $tags = new Tags($_POST);
        $this->tagsManager->modifTags($tags);
        echo $this->twig->render('index.html.twig', array('acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin']));
    }
    public function ajoutDeTags()
    {
        $tags = new Tags($_POST);
        $this->tagsManager->ajoutTags($tags);
        echo $this->twig->render('index.html.twig', array('acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], ));
    }
    public function supprDeTags()
    {
        if (isset($_POST['id_tags'])) {
            $idTags = $_POST['id_tags'];
            $this->tagsManager->supprTags($idTags);
            echo $this->twig->render('index.html.twig', array('acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin']));
        } else {
            echo $this->twig->render('index.html.twig', array('acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin']));
        }
    }
}