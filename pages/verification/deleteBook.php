<?php
require_once('/MAMP/htdocs/phpProjet/db/Database.php');
session_start();





// Vérifier que l'ID du livre a été passé
if (filter_has_var(INPUT_POST, 'action')) {
    $bookId = filter_input(INPUT_POST, 'book_id');

    // Créer une instance de la base de données
    $db = new Database();

    // Appeler la méthode pour supprimer le livre
    if ($db->deleteBook($bookId)) {
        // Rediriger vers la page d'administration avec un message de succès
        $_SESSION['message'] = "Le livre a été supprimé avec succès.";
        header('Location: ../messages/message.php');
    } else {
        // Rediriger avec un message d'erreur
        $_SESSION['message'] = "Erreur lors de la suppression du livre.";
        header('Location: ../messages/message.php');
    }
} else {
    // Rediriger avec un message d'erreur si l'ID du livre est manquant
    $_SESSION['message'] = "Aucun livre sélectionné pour suppression.";
    header('Location: ../messages/message.php');
}
exit();
?>
