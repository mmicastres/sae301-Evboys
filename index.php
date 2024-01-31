<?php
// utilisation des sessions
session_start();

include "moteurtemplate.php";
include "connect.php";
include "Controllers/utilisateurController.php";
include "Controllers/projetsController.php";
include "Controllers/categoriesController.php";
include "Controllers/contexteController.php";
include "Controllers/tagsController.php";


$utiController = new UtilisateurController($bdd, $twig);
$proController = new ProjetsController($bdd, $twig);
$categorieController = new CategorieController($bdd, $twig);
$contexteController = new ContexteController($bdd, $twig);
$tagsController = new TagsController($bdd, $twig);


// texte du message
$message = "";

// ============================== connexion / deconnexion - sessions ==================

// si la variable de session n'existe pas, on la crée
if (!isset($_SESSION['acces'])) {
  $_SESSION['acces'] = "non";
}
if (!isset($_SESSION["admin"])) {
  $_SESSION["admin"] = "";
}
if (!isset($_SESSION['id_utilisateur'])) {

}
// click sur le bouton connexion
if (isset($_POST["connexion"])) {
  $utiController->utilisateurConnexion($_POST);
  $proController->accueil() . $_SESSION['acces'];
}

// deconnexion : click sur le bouton deconnexion
if (isset($_GET["action"]) && $_GET['action'] == "logout") {
  $utiController->utilisateurDeconnexion();
  $proController->accueil() . $_SESSION['acces'];
}

// formulaire de connexion

if (isset($_GET["action"]) && $_GET["action"] == "login") {
  $utiController->utilisateurFormulaire();
}

// ============================== Inscription ==================

// click sur le bouton inscription

if (isset($_POST["inscription"])) {
  $utiController->inscriptionUtilisateur($_POST);
}

// formulaire d'inscription

if (isset($_GET["action"]) && $_GET["action"] == "register") {
  $utiController->inscriptionFormulaire();
}

// ============================== page d'accueil ==================

// cas par défaut = page d'accueil
if (!isset($_GET["action"]) && empty($_POST)) {
  $proController->accueil();
}
// ============================== gestion des projets ==================

// liste des projets dans un tableau HTML
//  https://.../index/php?action=liste
if (isset($_GET["action"]) && $_GET["action"] == "liste") {

  $proController->listeProjets();
}

//detail d'un projet
if (isset($_GET['action']) && $_GET['action'] == 'detail' && isset($_GET['id'])) {
  $idpro = $_GET["id"];
  $proController->details($idpro);
}


//Espace Utilisateur
if (isset($_GET["action"]) && $_GET["action"] == "espace") {
  $idperso = $_SESSION["id_utilisateur"];
  $utiController->informationPerso($idperso);
}

//Projets perso de l'utilisateur

if (isset($_GET['action']) && $_GET['action'] == 'mespros') {
  $idperso = $_SESSION["id_utilisateur"];
  $proController->listeProjetsPerso($idperso);
}

//Modifier un projet 
if (isset($_POST["modifier"]) && $_GET["action"] && $_GET["action"] == "modif") {
  $idpro = $_POST['modifier'];
  $proController->formModifProjet($idpro);
}

//Modification du projet dans la bdd
if (isset($_POST['modification'])) {
  $proController->modificationProjet($_POST);
}

//Formulaire d'ajoute d'un projet 
if (isset($_GET["action"]) && $_GET["action"] == "ajout") {
  $proController->formAjoutProjet();
}
if (isset($_POST["ajout"])) {
  $proController->ajoutDuProjet($_POST);
}

//Formulaire de Gestion des catégories
if (isset($_GET["action"]) && $_GET["action"] == "categorie") {
  $categorieController->formModifCategories();
}
if (isset($_POST["ajoutCat"])) {
  $categorieController->ajoutDeCategorie($_POST);
}
if (isset($_POST["modifCat"])) {
  $categorieController->modifDeCategorie($_POST);
}
if (isset($_POST["supprCat"])) {
  $categorieController->supprDeCategorie($_POST);
}

//Formulaire de Gestion des contexte
if (isset($_GET["action"]) && $_GET["action"] == "contexte") {
  $contexteController->formModifContexte();
}
if (isset($_POST["ajoutCont"])) {
  $contexteController->ajoutDeContexte($_POST);
}
if (isset($_POST["modifCont"])) {
  $contexteController->modifDeContexte($_POST);
}
if (isset($_POST["supprCont"])) {
  $contexteController->supprDeContexte($_POST);
}

//Formulaire de Gestion des tags
if (isset($_GET["action"]) && $_GET["action"] == "tags") {
  $tagsController->formModifTags();
}
if (isset($_POST["ajoutTags"])) {
  $tagsController->ajoutDeTags($_POST);
}
if (isset($_POST["modifTags"])) {
  $tagsController->modifDeTags($_POST);
}
if (isset($_POST["supprTags"])) {
  $tagsController->supprDeTags($_POST);
}

//Formulaire de Gestion des Utilisateur
if (isset($_GET["action"]) && $_GET["action"] == "utilisateur") {
  $utiController->formModifUtilisateur();
}
if (isset($_POST["ajoutUtilisateur"])) {
  $utiController->ajoutUtilisateur($_POST);
}
if (isset($_POST["modifUtilisateur"])) {
  $utiController->modifUtilisateur($_POST);
}
if (isset($_POST["supprUtilisateur"])) {
  $utiController->supprUtilisateur($_POST);
}

if (isset($_GET['action']) && $_GET['action'] == 'adminprojets') {
  $proController->listeProjetsAdmin();
}

if (isset($_POST['supprimer'])) {
  $idpro=$_POST['supprimer'];
  $proController->supprimerProjet($idpro);
}

if (isset($_GET["action"]) && $_GET["action"] == "recherche") {
  $proController->formRecherche();
}
if (isset($_POST["recherche"])) {
  $titre = isset($_POST['titre']) ? $_POST['titre'] : "";
  $description = isset($_POST['description']) ? $_POST['description'] : "";
  $proController->recherchePro($titre,$description);
}
if (isset($_POST["soumettre"])){
  $proController->ajoutAvisProjet($_POST);
}

?>