<?php
require_once('../../db/Database.php');
require_once('../../class/Book.php');

// Accéder à la base de données
$db = new Database();
$db->fetchTopBooksFromOpenLibrary('../../db/exercise.json');
$db->fetchTopBooksFromOpenLibrary('../../db/fantasy.json');
$db->fetchTopBooksFromOpenLibrary('../../db/film.json');
$db->fetchTopBooksFromOpenLibrary('../../db/horror.json');
$db->fetchTopBooksFromOpenLibrary('../../db/love.json');
$db->fetchTopBooksFromOpenLibrary('../../db/romance.json');
$db->fetchTopBooksFromOpenLibrary('../../db/science.json');