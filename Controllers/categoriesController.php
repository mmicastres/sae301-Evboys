<?php
include "Modules/categories.php";
include "Models/categoriesManager.php";
/**
 * Définition d'une classe permettant de gérer les membres 
 *   en relation avec la base de données	
 */
class CategorieController
{
    private $categorieManager; // instance du manager
    private $twig;

    /**
     * Constructeur = initialisation de la connexion vers le SGBD
     */
    public function __construct($db, $twig)
    {
        $this->categorieManager = new CategorieManager($db);
        $this->twig = $twig;
    }

    /**
     * connexion
     * @param aucun
     * @return rien
     */
    public function formModifCategories()
    {
        $categories = $this->categorieManager->getCategories();
		echo $this->twig->render('categorie.html.twig', array('acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'categories' => $categories));
    }
    public function modifDeCategorie()
    {
        $categorie = new Categories($_POST);
        $this->categorieManager->modifCategorie($categorie);
		echo $this->twig->render('index.html.twig', array('acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin']));
    }
    public function ajoutDeCategorie()
	{
		$categorie = new Categories($_POST);
		$this->categorieManager->ajoutCategorie($categorie);
		echo $this->twig->render('index.html.twig', array('acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'],));


	}
    public function supprDeCategorie()
	{
        if (isset($_POST['id_categories'])) {
            $idCategorie = $_POST['id_categories'];
            $this->categorieManager->supprCategorie($idCategorie);
            echo $this->twig->render('index.html.twig', array('acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin']));
        } else {
            echo $this->twig->render('index.html.twig', array('acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin']));
        }
	}
}