<?php
session_start();
require_once('../../db/Database.php');
require_once('../../class/Book.php');

// Instancier la base de données

$db = new Database();
if (!$db->initialisation()) {
    echo "Erreur lors de l'accès à la base de données.";
    exit();
}

// var_dump($_SESSION['search']);

// foreach ($_SESSION['search'] as $id) {
//     $books[] = $book;
// }
$books = $db->getBooksById($_SESSION['search']);

var_dump($books);
