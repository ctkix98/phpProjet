<?php
require_once('../../db/Database.php');
require_once('../../class/Book.php');

// Accéder à la base de données
$db = new Database();

$db->fetchTopBooksFromOpenLibrary('../../db/fantasy.json');
$db->fetchTopBooksFromOpenLibrary('../../db/love.json');
