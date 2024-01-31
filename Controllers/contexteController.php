<?php
include "Modules/contexte.php";
include "Models/contexteManager.php";
/**
 * Définition d'une classe permettant de gérer les membres 
 *   en relation avec la base de données	
 */
class ContexteController
{
    private $contexteManager; // instance du manager
    private $twig;

    /**
     * Constructeur = initialisation de la connexion vers le SGBD
     */
    public function __construct($db, $twig)
    {
        $this->contexteManager = new ContexteManager($db);
        $this->twig = $twig;
    }

    /**
     * connexion
     * @param aucun
     * @return rien
     */
    public function formModifContexte()
    {
        $contextes = $this->contexteManager->getContexte();
		echo $this->twig->render('contexte.html.twig', array('acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'contextes' => $contextes));
    }
    public function modifDeContexte()
    {
        $contexte = new Contexte($_POST);
        $this->contexteManager->modifContexte($contexte);
		echo $this->twig->render('index.html.twig', array('acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin']));
    }
    public function ajoutDeContexte()
	{
		$contexte = new Contexte($_POST);
		$this->contexteManager->ajoutContexte($contexte);
		echo $this->twig->render('index.html.twig', array('acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'],));


	}
    public function supprDeContexte()
	{
        if (isset($_POST['id_contexte'])) {
            $idContexte = $_POST['id_contexte'];
            $this->contexteManager->supprCategorie($idContexte);
            echo $this->twig->render('index.html.twig', array('acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin']));
        } else {
            echo $this->twig->render('index.html.twig', array('acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin']));
        }
	}
}