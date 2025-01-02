<?php
// Connexion à la base de données et chargement des classes nécessaires
require_once('../../db/Database.php');
session_start();

// Initialisation de la base de données
$db = new Database();
if (!$db->initialistion()) {
    $_SESSION['message'] = "Erreur lors de l'accès à la base de données.";
    header('Location: ../messages/message.php', true, 303);
    exit();
}

// Récupération du token depuis l'URL
$token = filter_input(INPUT_GET, 'token', FILTER_DEFAULT);

if ($token) {
    // Vérifier si une personne est associée au token
    $personne = $db->getUserByToken($token);
    if ($personne) {
        // Confirmer l'inscription
        if ($db->confirmeInscription($personne['id'])) {
            $message = "ok";
            $_SESSION['message'] = $message;
            header('Location: ../messages/mailMessage.php', true, 303);
            exit();
        } else {
            $message = "Une erreur est survenue lors de la confirmation. Veuillez réessayer plus tard".$_POST;
            $_SESSION['message'] = $message;
            header('Location: ../messages/mailMessage.php', true, 303);
            exit();
        }
    } else {
        $message = "Lien de confirmation invalide ou expiré".$_POST;
        $_SESSION['message'] = $message;
        header('Location: ../messages/mailMessage.php', true, 303);
        exit();
    }
} else {
    $message = "Aucun token fourni pour la confirmation".$_POST;
    $_SESSION['message'] = $message;
    header('Location: ../messages/mailMessage.php', true, 303);
    exit();
}
