<?php
require_once 'Database.php';

$db = new Database();
$books = $db->fetchTopBooksFromOpenLibrary();
if ($db->addBooksToDatabase($books)) {
    echo "Les livres ont été ajoutés avec succès.";
} else {
    echo "Erreur lors de l'ajout des livres.";
}
