<?php

//use Exception;

require_once('../../db/Database.php');
session_start();

$donneeConnexion = [];

if (filter_has_var(INPUT_POST, 'submit')) {
    $donneeConnexion['pseudo'] = filter_input(INPUT_POST, 'pseudo', FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^.{4,100}$/"]]);
    $donneeConnexion['password'] = filter_input(INPUT_POST, 'password', FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^.{8,100}$/"]]);
}else {
    $_SESSION['message'] = "Les informations entrées ne sont pas conformes à la demande";
    header('Location: ../messages/message.php', true, 303);
    exit();
}


$required = ['pseudo', 'password'];
foreach ($required as $champ) {
    if (empty($donneeConnexion[$champ])) {
        $_SESSION['message'] = "Tous les champs doivent être complétés ! ";
        header('Location: ../messages/message.php', true, 303);
        exit();
    }
}


// Accéder à la base de données
$db = new Database();
$donnesCompletesUtilisateur = $db->verifierAccesEtRecupererUtilisateur($donneeConnexion['pseudo']);

if ($donnesCompletesUtilisateur !== null) {
    //Récupérer l'id 
    $utilisateurId = $donnesCompletesUtilisateur['id'];
    // Vérifiez le mot de passe
    if (password_verify($donneeConnexion['password'], $donnesCompletesUtilisateur['password'])) {
        // Mot de passe correct, établir la session
        $_SESSION['utilisateur'] = $donnesCompletesUtilisateur;
        $_SESSION['message'] = "Tu es connecté !";
        header('Location: ../messages/message.php', true, 303);
        exit();
    } else {
        // Mot de passe incorrect
        $_SESSION['message'] = "Le mot de passe est incorrect";
        header('Location: ../messages/message.php', true, 303);
        exit();
    }
} else {
    // Utilisateur non trouvé
    $_SESSION['message'] = "Le compte avec ce pseudo n'existe pas";
    header('Location: ../messages/message.php', true, 303);
    exit();
}