<?php
require_once('../../db/Database.php');
require_once('../../class/Book.php');

function initializeDatabase(): void
{
    // Accéder à la base de données
    $db = new Database();
    
    function populateDB($db): void
    {
        $db->fetchTopBooksFromOpenLibrary('../../db/exercise.json');
        $db->fetchTopBooksFromOpenLibrary('../../db/fantasy.json');
        $db->fetchTopBooksFromOpenLibrary('../../db/film.json');
        $db->fetchTopBooksFromOpenLibrary('../../db/horror.json');
        $db->fetchTopBooksFromOpenLibrary('../../db/love.json');
        $db->fetchTopBooksFromOpenLibrary('../../db/romance.json');
        $db->fetchTopBooksFromOpenLibrary('../../db/science.json');
    }

    populateDB($db);

    $db->addBookState("En cours");
    $db->addBookState("Terminé");
    $db->addBookState("A lire");
    $db->addBookState("Abandonné");
    $db->addBookState("Non sSpécifié");
}

// Call the function to initialize the database
initializeDatabase();