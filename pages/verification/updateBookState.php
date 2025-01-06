<?php
require_once __DIR__ . '/../../db/Database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookId = $_POST['book_id'];
    $bookState = $_POST['book_state'];
    $userId = $_SESSION['utilisateur']['id'];

    $db = new Database();
    $sql = "INSERT INTO lecture (book_id, user_id, book_state_id) VALUES (:book_id, :user_id, :book_state_id)
            ON CONFLICT(book_id, user_id) DO UPDATE SET book_state_id = :book_state_id";
    $stmt = $db->getDb()->prepare($sql);
    $stmt->bindParam(':book_id', $bookId, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':book_state_id', $bookState, PDO::PARAM_STR);

    if ($stmt->execute()) {
        $_SESSION['message'] = "L'état du livre a été mis à jour.";
    } else {
        $_SESSION['message'] = "Erreur lors de la mise à jour de l'état du livre.";
    }

    header('Location: bookinfo.php?id=' . $bookId);
    exit();
}

// Fonction pour récupérer l'état actuel du livre
function getCurrentBookState($bookId, $userId, $db) {
    $sql = "SELECT book_state_id FROM lecture WHERE book_id = :book_id AND user_id = :user_id";
    $stmt = $db->getDb()->prepare($sql);
    $stmt->bindParam(':book_id', $bookId, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['book_state_id'] : 'not-in-library';
}
?>