<?php
require_once('../../Database.php');
require_once('../../class/Book.php');

// Accéder à la base de données
$db = new Database();
$book = new Book(
    "Harry Potter",
    "J.K. Rowling",
    "GallimardO",
    "2000",
    "9782266077asfsf357l"
);

// $db->addBooksToDatabase($book);
$db->fetchTopBooksFromOpenLibrary();
