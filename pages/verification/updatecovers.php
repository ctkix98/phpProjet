<?php
require_once('../../db/Database.php');
require_once('../../class/Book.php');

$db = new Database();

// Vérifiez si PDO fonctionne correctement
$books = $db->query();
var_dump($books);
foreach ($books as $book) {
    echo "Traitement du livre avec ID : " . $book['id'] . "\n";
    // D'autres traitements possibles ici
}
echo("Re coucou");
?>

