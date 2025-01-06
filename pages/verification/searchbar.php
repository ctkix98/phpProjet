<?php
session_start();
require_once('../../db/Database.php');

$db = new Database();
if (!$db->initialisation()) {
    $_SESSION['message'] = "Erreur lors de l'accès à la base de données.";
    header('Location: ../messages/message.php', true, 303);
    exit();
}

if (filter_has_var(INPUT_POST, 'submit')) {
    $search = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_STRING);
    echo $search."<br>";
    $results = $db->searchBooks($search);
    var_dump($results);
    if (empty($results)) {
        $_SESSION['message'] = "Aucun résultat trouvé.";
        header('Location: ../messages/message.php', true, 303);
        exit();
    }
    echo "on est ici ";
    header('Location: showresults.php', true, 303);
    echo "on est là";
    exit();
}

