<?php

//use Exception;

require_once('../../db/Database.php');
session_start();

$donneeConnexion = [];

if (filter_has_var(INPUT_POST, 'submit')) {
    $donneeConnexion['email'] = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $donneeConnexion['mdp'] = filter_input(INPUT_POST, 'mdp', FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^[A-Za-z0-9$!€£]{8,20}$/"]]);
} else {
    $_SESSION['message'] = "Les informations entrées ne sont pas conformes à la demande";
    header('Location: ../messages/errorMessage.php', true, 303);
    exit();
}

$required = ['email', 'mdp'];
foreach ($required as $champ) {
    if (empty($donneeConnexion[$champ])) {
        $_SESSION['message'] = "Tous les champs doivent être complétés ! ";
        header('Location: ../messages/errorMessage.php', true, 303);
        exit();
    }
}

$donneeConnexion['email'] = strtolower($donneeConnexion['email']);

// Accéder à la base de données
$db = new Database();
$donnesCompletesUtilisateur = $db->verifierAccesEtRecupererUtilisateur($donneeConnexion['email']);

if ($donnesCompletesUtilisateur !== null) {
    // Vérifiez le mot de passe
    if (password_verify($donneeConnexion['mdp'], $donnesCompletesUtilisateur['mdp'])) {
        // Mot de passe correct, établir la session
        $_SESSION['utilisateur'] = $donnesCompletesUtilisateur;
        header('Location: ../messages/errorMessage.php', true, 303);
        exit();
    } else {
        // Mot de passe incorrect
        $_SESSION['message'] = "Le mot de passe est incorrect";
        header('Location: ../messages/errorMessage.php', true, 303);
        exit();
    }
} else {
    // Utilisateur non trouvé
    $_SESSION['message'] = "Le compte avec cet identifiant n'existe pas";
    header('Location: ../messages/errorMessage.php', true, 303);
    exit();
}