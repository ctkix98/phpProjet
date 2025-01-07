<?php
require_once('../../db/Database.php');
require_once('../../class/Book.php');

function initializeDatabase(): void
{
    // Accéder à la base de données
    $db = new Database();

    $db->addBookState("En cours");
    $db->addBookState("Terminé");
    $db->addBookState("A lire");
    $db->addBookState("Abandonné");
    $db->addBookState("Non spécifié");
}

// Call the function to initialize the database
initializeDatabase();
