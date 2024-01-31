<?php
include "Modules/utilisateur.php";
include "Models/utilisateurManager.php";
/**
 * Définition d'une classe permettant de gérer les membres 
 *   en relation avec la base de données	
 */
class UtilisateurController
{
	private $utilisateurManager; // instance du manager
	private $twig;

	/**
	 * Constructeur = initialisation de la connexion vers le SGBD
	 */
	public function __construct($db, $twig)
	{
		$this->utilisateurManager = new UtilisateurManager($db);
		$this->twig = $twig;
	}

	/**
	 * connexion
	 * @param aucun
	 * @return rien
	 */
	function utilisateurConnexion($data)
	{
		// verif du login et mot de passe
		$utilisateur = $this->utilisateurManager->verif_identification($_POST['login'], $_POST['mdp']);
		if ($utilisateur != false) { // acces autorisé : variable de session acces = oui
			$_SESSION['acces'] = "oui";
			$_SESSION['id_utilisateur'] = $utilisateur->id_utilisateur();
			$_SESSION['admin'] = $utilisateur->admin();
		} else { // acces non autorisé : variable de session acces = non
			$_SESSION['acces'] = "non";
			echo $this->twig->render('index.html.twig', array('acces' => $_SESSION['acces']));
		}
	}

	/**
	 * deconnexion
	 * @param aucun
	 * @return rien
	 */
	function utilisateurDeconnexion()
	{
		$_SESSION['acces'] = "non"; // acces non autorisé
		$_SESSION['admin'] = array();
		$_SESSION['id_utilisateur'] = array();
	}

	/**
	 * formulaire de connexion
	 * @param aucun
	 * @return rien
	 */
	function utilisateurFormulaire()
	{
		echo $this->twig->render('utilisateur_connexion.html.twig', array('acces' => $_SESSION['acces']));
	}

	/**
	 * formulaire ajout
	 * @param aucun
	 * @return rien
	 */
	public function inscriptionFormulaire()
	{

		echo $this->twig->render('utilisateur_inscription.html.twig');
	}

	/**
	 * ajout dans la BD d'un iti à partir du form
	 * @param aucun
	 * @return rien
	 */
	public function inscriptionUtilisateur()
	{
		function mdpsecu($mdp)
		{
			// Longueur minimale de 8 caractères
			if (strlen($mdp) < 6) {
				echo "Le mot de passe est trop court. Le mot de passe doit contenir 6 caractères";


				return false;
			}

			// Utilisation de caractères spéciaux, chiffres, et mélange de majuscules/minuscules
			if (!preg_match('/[!@#$%^&*(),.?":{}|<>0-9A-Za-z]/', $mdp)) {
				echo "Le mot de passe doit contenir au moins un caractère spécial, un chiffre, et un mélange de majuscules et de minuscules.";
				return false;
			}

			// Éviter les mots evidents
			$mdpCommun = ['motdepasse', '123456', 'azerty', 'admin'];
			if (in_array(strtolower($mdp), array_map('strtolower', $mdpCommun))) {
				echo "Le mot de passe est trop commun.";
				return false;
			}

			// Le mot de passe est considéré comme sécurisé normalement
			echo "Le mot de passe est sécurisé.";
			return true;
		}

		$email = isset($_POST['email']) ? trim($_POST['email']) : '';

		function endsWith($finEmail, $besoin)
		{
			return substr($finEmail, -strlen($besoin)) === $besoin;
		}


		if (mdpsecu($_POST['mdp'])) {
			if (endsWith($email, "@etu.iut-tlse3.fr")) {
				$utilisateur = new Utilisateur($_POST);
				$ok = $this->utilisateurManager->add($utilisateur);
				$message = $ok ? "Votre demande d'inscription à bien été prise en compte" : "L'Identifiant de l'IUT saisie existe déjà si vous ne connaissez plus votre mot de passe contactez un administrateur";
				echo $this->twig->render('utilisateur_inscription.html.twig', array('message' => $message));

			} else {
				// Gérer le cas où l'adresse e-mail ne se termine pas par "@etu.iut-tlse3.fr"
				echo "L'adresse e-mail doit se terminer par @etu.iut-tlse3.fr.";
			}
		} else {
			echo "Le mot de passe ne respecte pas les règles de sécurité.";
		}
	}

	public function informationPerso($idperso)
	{
		$idperso = $_SESSION['id_utilisateur'];
		$utilisateur = $this->utilisateurManager->perso($idperso);
		echo $this->twig->render(
			'espace_utilisateur.html.twig',
			array(
				'acces' => $_SESSION['acces'],
				'admin' => $_SESSION['admin'],
				'id_utilisateur' => $_SESSION['id_utilisateur'],
				'utilisateur' => $utilisateur['utilisateur'],
				'projets' => $utilisateur['projets']
			)
		);
	}
	public function formModifUtilisateur()
	{
		$utilisateur = $this->utilisateurManager->getUtilisateurs();
		echo $this->twig->render('utilisateur.html.twig',array('acces' => $_SESSION['acces'],'admin' => $_SESSION['admin'],'utilisateurs' => $utilisateur,));
	}
	public function modifUtilisateur()
    {
        $utilisateur = new Utilisateur($_POST);
        $this->utilisateurManager->modifUtilisateur($utilisateur);
        echo $this->twig->render('index.html.twig', array('acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin']));
    }
    public function ajoutUtilisateur()
    {
        $utilisateur = new Utilisateur($_POST);
        $this->utilisateurManager->ajoutUtilisateur($utilisateur);
        echo $this->twig->render('index.html.twig', array('acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin']));
    }
    public function supprUtilisateur()
    {
        if (isset($_POST['id_utilisateur'])) {
            $idutilisateur = $_POST['id_utilisateur'];
            $this->utilisateurManager->supprUtilisateur($idutilisateur);
            echo $this->twig->render('index.html.twig', array('acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin']));
        } else {
            echo $this->twig->render('index.html.twig', array('acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin']));
        }
    }
}