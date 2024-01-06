<?php
// utilisation des sessions
session_start();

include "moteurtemplate.php";
include "connect.php";
include "Controllers/utilisateurController.php";
include "Controllers/projetsController.php";

$utiController = new UtilisateurController($bdd, $twig);
$proController = new ProjetsController($bdd, $twig);


// texte du message
$message = "";

// ============================== connexion / deconnexion - sessions ==================

// si la variable de session n'existe pas, on la crée
if (!isset($_SESSION['acces'])) {
  $_SESSION['acces'] = "non";
}
// click sur le bouton connexion
if (isset($_POST["connexion"])) {
  $message = $utiController->utilisateurConnexion($_POST);
}

// deconnexion : click sur le bouton deconnexion
if (isset($_GET["action"]) && $_GET['action'] == "logout") {
  $message = $utiController->utilisateurDeconnexion();
}

// formulaire de connexion

if (isset($_GET["action"]) && $_GET["action"] == "login") {
  $utiController->utilisateurFormulaire();
}

// ============================== Inscription ==================

// click sur le bouton inscription

if (isset($_POST["inscription"])) {
  $utiController->ajoutUtilisateur($_POST);
}

// formulaire d'inscription

if (isset($_GET["action"]) && $_GET["action"] == "register") {
  $utiController->inscriptionFormulaire();
}

// ============================== page d'accueil ==================

// cas par défaut = page d'accueil
if (!isset($_GET["action"]) && empty($_POST)) {
  echo $twig->render('index.html.twig', array('acces' => $_SESSION['acces']));
}
// ============================== gestion des projets ==================

// liste des projets dans un tableau HTML
//  https://.../index/php?action=liste
if (isset($_GET["action"]) && $_GET["action"] == "liste") {
  $proController->listeProjets();
}
// liste de mes projets dans un tableau HTML
// if (isset($_GET["action"]) && $_GET["action"]=="mesitis") { 
//   $proController->listeMesProjets($_SESSION['id_utilisateur']);
// }

?>