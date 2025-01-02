<?php
require_once('../../config/session.php');
require_once('../../db/Database.php');

$message = '';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur'])) {
    header('Location: ../connexion.html');
    exit();
}

// Récupérer l'utilisateur connecté
$utilisateurId = $_SESSION['utilisateur']['id'];

// Instancier la base de données
$db = new Database();
if (!$db->initialistion()) {
    $message = "Erreur lors de l'accès à la base de données.";
    $_SESSION['message'] = $message;
    header('Location: ../messages/message.php');
    exit();
}

// Vérification de l'action
if (filter_has_var(INPUT_POST, 'submit')) {
    $action = filter_input(INPUT_POST, 'action', FILTER_DEFAULT);
    if (!$action) {
        $message = "Action manquante.";
    }
    if ($action === 'changerMotDePasse') {
        // Vérification des champs pour le changement de mot de passe
        $ancienMotDePasse = filter_input(INPUT_POST, 'ancienMotDePasse', FILTER_DEFAULT);
        $nouveauMotDePasse = filter_input(INPUT_POST, 'nouveauMotDePasse', FILTER_DEFAULT);
        $confirmerMotDePasse = filter_input(INPUT_POST, 'confirmerMotDePasse', FILTER_DEFAULT);


        if (empty($ancienMotDePasse) || empty($nouveauMotDePasse) || empty($confirmerMotDePasse)) {
            $message = "Tous les champs sont requis pour changer le mot de passe.";
        } elseif ($nouveauMotDePasse !== $confirmerMotDePasse) {
            $message = "Le nouveau mot de passe et sa confirmation ne correspondent pas.";
        } else {
            // Récupérer le mot de passe actuel de l'utilisateur
            $utilisateur = $db->getUserById($utilisateurId);

            if (!$utilisateur || !password_verify($ancienMotDePasse, $utilisateur['password'])) {
                $message = "L'ancien mot de passe est incorrect.";
            } else {
                // Mettre à jour le mot de passe dans la base de données
                $nouveauMotDePasseHashe = password_hash($nouveauMotDePasse, PASSWORD_BCRYPT);

                if ($db->updatePassword($utilisateurId, $nouveauMotDePasseHashe)) {
                    $message = "Le mot de passe a été changé avec succès.";
                } else {
                    $message = "Erreur lors de la mise à jour du mot de passe.";
                }
            }
        }
    } elseif ($action === 'supprimerCompte') {
        // Supprimer le compte de l'utilisateur
        if ($db->deleteUser($utilisateurId)) {
            // Déconnecter l'utilisateur après la suppression
            session_destroy();
            header('Location: ../messages/deleteAccount.php?');
            exit();
        } else {
            $message = "Erreur lors de la suppression du compte.";
        }
    } else {
        $message = "Action non reconnue.";
    }
} else {
    $message = "Requête invalide.";
}

// Rediriger avec un message approprié
$_SESSION['message'] = $message;
header('Location: ../messages/message.php');
exit();
