<?php
require_once __DIR__ . '/../../db/Database.php';
session_start();

if (filter_has_var(INPUT_POST, 'submit')) {
    $bookId = filter_input(INPUT_POST, 'book_id', FILTER_DEFAULT);
    $book_state = filter_input(INPUT_POST, 'book_state', FILTER_DEFAULT);
    $userId = $_SESSION['utilisateur']['id'];
    $db = new Database();
    $db->createLecture($bookId, $book_state, $userId);
    $_SESSION['book_states'][$bookId] = $book_state;

     header('Location: bookinfo.php?id=' . $bookId);
    exit();
}

// Fonction pour récupérer l'état actuel du livre
function getCurrentBookState($bookId, $userId, $db)
{
    $sql = "SELECT book_state_id FROM lecture WHERE book_id = :book_id AND user_id = :user_id";
    $stmt = $db->getDb()->prepare($sql);
    $stmt->bindParam(':book_id', $bookId, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['book_state_id'] : 'not-in-library';
}
