<?php

require_once('../../db/Database.php');
require_once('../../class/Book.php');
session_start();



// Accéder à la base de données
$db = new Database();
$book = new Book(
    "Harry Potter",
    "J.K. Rowling",
    "GallimardO",
    "2000",
    "9782266077357l"
);

$db->addBooksToDatabase($book);
