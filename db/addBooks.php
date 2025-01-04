<?php
require_once('../db/Database.php');

$db = new Database();
$books = $db->fetchTopBooksFromOpenLibrary();

// Ajout des livres à la base de données
function addingBooks(Database $db = $db, array $books = $books): void
{
    if ($db->addBooksToDatabase($books)) {
        echo "Les livres ont été ajoutés avec succès.";
    } else {
        echo "Erreur lors de l'ajout des livres.";
    }
}
