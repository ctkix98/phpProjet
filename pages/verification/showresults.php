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