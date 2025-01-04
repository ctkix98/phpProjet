<?php

require_once('../../db/Database.php');
session_start();

$booksData = [];

if (filter_has_var(INPUT_POST, 'submit')) {
    // Validation des autres champs
    $booksData['title'] = filter_input(INPUT_POST, 'title', FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^.{1,100}$/"]]);
    $booksData['writer'] = filter_input(INPUT_POST, 'writer', FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^.{1,100}$/"]]);
    $booksData['editor'] = filter_input(INPUT_POST, 'editor', FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^.{1,100}$/"]]);
    $booksData['year'] = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^\d{4}$/"]]);
    $booksData['isbn'] = filter_input(INPUT_POST, 'isbn', FILTER_VALIDATE_REGEXP, [
        "options" => ["regexp" => "/^(?=(?:\D*\d){10}(?:(?:\D*\d){3})?$)[\d-]+$/"]
    ]);
    
} else {
    $_SESSION['message'] = "Les informations entrées ne sont pas conformes à la demande";
    header('Location: ../messages/message.php', true, 303);
    exit();
}

$required = ['title', 'writer', 'editor', 'year', 'isbn'];
foreach ($required as $champ) {
    if (empty($booksData[$champ]) || $booksData[$champ] === false) {
        $_SESSION['message'] = "Tous les champs doivent être complétés ! ";
        header('Location: ../messages/message.php', true, 303);
        exit();
    } else {
        // Ajout du livre dans le fichier JSON (en attente de validation)
        $booksFile = '../../books/books_pending.json';
        $existingBooks = file_exists($booksFile) ? json_decode(file_get_contents($booksFile), true) : [];
        $booksData['status'] = 'pending'; // Marquer le livre comme en attente
        $existingBooks[] = $booksData;

        // Sauvegarder les données mises à jour dans le fichier JSON
        file_put_contents($booksFile, json_encode($existingBooks, JSON_PRETTY_PRINT));

        // Message de succès
        $_SESSION['message'] = "Le livre a été soumis à l'administrateur pour validation !";
        header('Location: ../messages/message.php', true, 303);
        exit();
    }
}
