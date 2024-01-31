<?php
include "Modules/projets.php";
include "Modules/publie.php";
include "Modules/tag.php";
include "Modules/commentaires.php";
include "Models/projetsManager.php";
include "Models/publieManager.php";
include "Models/tagManager.php";
include "Models/commentairesManager.php";

/**
 * Définition d'une classe permettant de gérer les projets
 *   en relation avec la base de données	
 */


class ProjetsController
{

	private $proManager; // instance du manager
	private $publieManager;
	private $categorieManager;
	private $contexteManager;
	private $tagsManager;
	private $tagManager;
	private $commentairesManager;
	private $twig;

	/**
	 * Constructeur = initialisation de la connexion vers le SGBD
	 */
	public function __construct($db, $twig)
	{
		$this->proManager = new ProjetsManager($db);
		$this->publieManager = new PublieManager($db);
		$this->categorieManager = new CategorieManager($db);
		$this->contexteManager = new ContexteManager($db);
		$this->tagsManager = new TagsManager($db);
		$this->tagManager = new TagManager($db);
		$this->commentairesManager = new CommentairesManager($db);
		$this->twig = $twig;
	}

	/**
	 * liste de tous les projets
	 * @param aucun
	 * @return rien
	 */
	public function listeProjets()
	{
		$projets = $this->proManager->getList();
		echo $this->twig->render('projets_liste.html.twig', array('pros' => $projets, 'acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin']));
	}

	public function accueil()
	{
		$projets = $this->proManager->presentation();

		echo $this->twig->render('index.html.twig', array('pros' => $projets, 'acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin']));
	}


	public function details($idpro)
	{
		$projets = $this->publieManager->detail($idpro);
		$tags = $this->tagManager->tagPro($idpro);
		$coms = $this->commentairesManager->listeCommentairesProjets($idpro);

		echo $this->twig->render('projet_detail.html.twig', ['det' => $projets, 'acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'tags' => $tags, 'coms' => $coms,'id_utilisateur' => $_SESSION['id_utilisateur']]);
	}

	public function ajoutAvisProjet()
	{
		$commentaire = new Commentaires($_POST);
		$this->commentairesManager->ajoutCommentaire($commentaire);
		echo $this->twig->render('index.html.twig', array('acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'],'id_utilisateur' => $_SESSION['id_utilisateur']));

	}


	public function listeProjetsPerso($idperso)
	{
		$idperso = $_SESSION['id_utilisateur'];
		$projets = $this->publieManager->projetsUtilisateur($idperso);
		echo $this->twig->render('projets_perso.html.twig', array('acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'id_utilisateur' => $_SESSION['id_utilisateur'], 'pros' => $projets));
	}
	public function formModifProjet($idpro)
	{
		$projets = $this->publieManager->formPro($idpro);
		$categories = $this->categorieManager->getCategories();
		$contextes = $this->contexteManager->getContexte();
		$tags = $this->tagsManager->getTags();
		echo $this->twig->render('form_modif_projet.html.twig', array('acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'pros' => $projets, 'contextes' => $contextes, 'categories' => $categories, 'tags' => $tags, ));
	}
	public function modificationProjet()
	{
		$utilisateur = new Utilisateur(['id_utilisateur' => $_SESSION['id_utilisateur']]);
		$_POST['img'] = file_get_contents($_FILES['img']['tmp_name']);
		$projet = new Projets($_POST, $utilisateur);
		$idprojet = $projet->id_projets();

		$ok = $this->proManager->modifProjet($projet);


		//Supprimer tous les tags associés au projet
		$this->tagManager->supprimerTag($idprojet);

		// Ajouter les nouveaux tags sélectionnés
		foreach ($_POST['id_tags'] as $idtag) {
			$tag = new Tag(['id_projets' => $idprojet, 'id_tags' => $idtag]);
			$this->tagManager->ajoutTag($tag);
		}

		$message = $ok ? "Projet modifié" : "Erreur";
		echo $this->twig->render('projets_liste.html.twig', array('acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'message' => $message, 'id_utilisateur' => $_SESSION['id_utilisateur']));
	}


	public function formAjoutProjet()
	{
		$categories = $this->categorieManager->getCategories();
		$contextes = $this->contexteManager->getContexte();
		$tags = $this->tagsManager->getTags();
		echo $this->twig->render('form_ajout_projet.html.twig', array('tags' => $tags, 'contextes' => $contextes, 'categories' => $categories, 'acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'id_utilisateur' => $_SESSION['id_utilisateur']));
	}
	public function ajoutDuProjet()
	{
		//Récupere l'utilisateur connecté et récupère son ID
		$utilisateur = new Utilisateur(['id_utilisateur' => $_SESSION['id_utilisateur']]);
		$idutilisateur = $_SESSION['id_utilisateur'];

		//Mise en place de l'image dans le bon format
		$_POST['img'] = file_get_contents($_FILES['img']['tmp_name']);

		//Créer le projet en récupérant les données saisies dans le formulaire d'ajout et l'insert dans la base de données
		$projet = new Projets($_POST, $utilisateur);
		$this->proManager->ajoutProjet($projet);
		//Récupère l'ID du projet créer 
		$idprojet = $projet->id_projets();



		//Création de Publie en récupérant l'id du projet et de l'utilisateur créer plus haut pour les ajouter dans la TABLE sae301_publie
		$publie = new Publie(['id_projets' => $idprojet, 'id_utilisateur' => $idutilisateur]);
		$this->publieManager->ajoutPublie($publie);

		//id du projet et de les tags sélectionner pour les ajouter dans la TABLE sae301_tag
		foreach ($_POST['id_tags'] as $idtag) {
			$tag = new Tag(['id_projets' => $idprojet, 'id_tags' => $idtag]);
			$this->tagManager->ajoutTag($tag);
		}

		echo $this->twig->render('projets_liste.html.twig', array('acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'id_utilisateur' => $_SESSION['id_utilisateur']));
	}

	public function listeProjetsAdmin()
	{
		$projets = $this->publieManager->projetsAdmin();
		echo $this->twig->render('projets_perso.html.twig', array('acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'id_utilisateur' => $_SESSION['id_utilisateur'], 'pros' => $projets));
	}

	public function supprimerProjet($projets)
	{
		$this->publieManager->supprimerPublie($projets);
		$this->tagManager->supprimerTag($projets);
		$this->proManager->supprimerProjet($projets);
		echo $this->twig->render('projets_perso.html.twig', array('acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'id_utilisateur' => $_SESSION['id_utilisateur'], 'pros' => $projets));
	}

	public function formRecherche()
	{
		echo $this->twig->render('recherche.html.twig', array('acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin']));
	}

	public function recherchePro($titre, $description)
	{
		$pros = $this->proManager->recherche($titre, $description);
		// Obtenez les détails des projets pour affichage
		$projetsDetails = array();
		foreach ($pros as $projet) {
			$projetDetails = $this->proManager->getProjet($projet->id_projets());
			$projetsDetails[] = $projetDetails[0];
		}

		echo $this->twig->render(
			'projets_liste.html.twig',
			array(
				'acces' => $_SESSION['acces'],
				'admin' => $_SESSION['admin'],
				'id_utilisateur' => $_SESSION['id_utilisateur'],
				'pros' => $projetsDetails,
			)
		);
	}


}

?>