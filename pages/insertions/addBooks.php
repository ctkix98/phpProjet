<?php

require_once('../../db/Database.php');
session_start();



// Accéder à la base de données
$db = new Database();

echo "coucours";

$books =
    [
        "title" => "Harry Potter",
        "author" => "Harry Potter",
        "editor" => "Gallimard",
        "parution_date" => "2000",
        "isbn" => "97822660357"
    ];




$db->addBooksToDatabase($books);
