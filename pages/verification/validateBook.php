<?php
require_once('../../db/Database.php');
session_start();

$message = '';

// Instancier la base de données
$db = new Database();
if (!$db->initialistion()) {
    $message = "Erreur lors de l'accès à la base de données.";
    $_SESSION['message'] = $message;
    header('Location: ../messages/message.php');
    exit();
}

// Vérification de l'action
if (filter_has_var(INPUT_POST, 'action')) {
    $action = filter_input(INPUT_POST, 'action', FILTER_DEFAULT);
    $bookId = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);

    if (!$action || !$bookId) {
        $message = "Action ou identifiant du livre manquant.";
        $_SESSION['message'] = $message;
        header('Location: ../messages/message.php');
        exit();
    }

    // Préparer la requête selon l'action
    if ($action === 'reject') {
        // Mettre à jour le statut du livre en 'rejected'
        $sqlUpdateStatus = "UPDATE book_validation SET validation_status = 'rejected' WHERE id = :bookId";
        $stmtUpdateStatus = $db->getDb()->prepare($sqlUpdateStatus);
        $stmtUpdateStatus->bindParam(':bookId', $bookId, PDO::PARAM_INT);
        $stmtUpdateStatus->execute();

        $_SESSION['message'] = "Le livre a été rejeté.";
        header('Location: dashboardAdmin.php');
        exit();
    } else if ($action === 'update') {
        header('Location: editBook.php?id=' . $bookId);
        exit();
    } else {
        $_SESSION['message'] = "Action non valide.";
        header('Location: ../messages/message.php');
        exit();
    }
} else {
    $_SESSION['message'] = "Aucune action soumise.";
    header('Location: ../messages/message.php');
    exit();
}
