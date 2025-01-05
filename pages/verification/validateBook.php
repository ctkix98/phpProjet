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
    if ($action === 'approve') {
        // Récupérer les détails du livre depuis la table book_validation
        $sqlGetBook = "SELECT * FROM book_validation WHERE id = :bookId AND validation_status = 'pending'";
        $stmtGetBook = $db->getDb()->prepare($sqlGetBook);
        $stmtGetBook->bindParam(':bookId', $bookId, PDO::PARAM_INT);
        $stmtGetBook->execute();
        $book = $stmtGetBook->fetch(PDO::FETCH_ASSOC);

        if ($book) {
            // Si le livre existe, l'ajouter à la table 'book'
            $sqlInsertBook = "INSERT INTO book (Title, Author, Theme, Parution_date, ISBN) VALUES (:title, :author, :theme, :parution_date, :isbn)";
            $stmtInsertBook = $db->getDb()->prepare($sqlInsertBook);
            $stmtInsertBook->bindParam(':title', $book['Title'], PDO::PARAM_STR);
            $stmtInsertBook->bindParam(':author', $book['Author'], PDO::PARAM_STR);
            $stmtInsertBook->bindParam(':theme', $book['Theme'], PDO::PARAM_STR);
            $stmtInsertBook->bindParam(':parution_date', $book['Parution_date'], PDO::PARAM_STR);
            $stmtInsertBook->bindParam(':isbn', $book['ISBN'], PDO::PARAM_STR);
            $stmtInsertBook->execute();

            // Mettre à jour le statut du livre dans la table 'book_validation'
            $sqlUpdateStatus = "UPDATE book_validation SET validation_status = 'approved' WHERE id = :bookId";
            $stmtUpdateStatus = $db->getDb()->prepare($sqlUpdateStatus);
            $stmtUpdateStatus->bindParam(':bookId', $bookId, PDO::PARAM_INT);
            $stmtUpdateStatus->execute();

            $_SESSION['message'] = "Le livre a été approuvé et ajouté à la bibliothèque.";
            header('Location: dashboardAdmin.php');
            exit();
        } else {
            $_SESSION['message'] = "Le livre n'a pas été trouvé ou il est déjà validé/rejeté.";
            header('Location: ../messages/message.php');
            exit();
        }

    } else if ($action === 'reject') {
        // Mettre à jour le statut du livre en 'rejected'
        $sqlUpdateStatus = "UPDATE book_validation SET validation_status = 'rejected' WHERE id = :bookId";
        $stmtUpdateStatus = $db->getDb()->prepare($sqlUpdateStatus);
        $stmtUpdateStatus->bindParam(':bookId', $bookId, PDO::PARAM_INT);
        $stmtUpdateStatus->execute();

        $_SESSION['message'] = "Le livre a été rejeté.";
        header('Location: dashboardAdmin.php');
        exit();

    } else if ($action === 'update') {
        // Si vous avez une logique de modification, vous pouvez l'ajouter ici
        // Cela dépend de ce que vous voulez permettre à l'administrateur de modifier
        header('Location: ../changeBook.php');
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
?>
