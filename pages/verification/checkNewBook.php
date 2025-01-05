<?php

require_once('../../db/Database.php');
require_once('../../class/Book.php');

session_start();

$booksData = [];

if (filter_has_var(INPUT_POST, 'submit')) {
    // Validation des autres champs
    $booksData['title'] = filter_input(INPUT_POST, 'title', FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^.{1,100}$/"]]);
    $booksData['writer'] = filter_input(INPUT_POST, 'writer', FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^.{1,100}$/"]]);
    $booksData['theme'] = filter_input(INPUT_POST, 'theme', FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^.{1,100}$/"]]);
    $booksData['year'] = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^\d{4}$/"]]);
    $booksData['isbn'] = filter_input(INPUT_POST, 'isbn', FILTER_VALIDATE_REGEXP, [
        "options" => ["regexp" => "/^(?=(?:\D*\d){10}(?:(?:\D*\d){3})?$)[\d-]+$/"]
    ]);
} else {
    $_SESSION['message'] = "Les informations entrées ne sont pas conformes à la demande";
    header('Location: ../messages/message.php', true, 303);
    exit();
}

$required = ['title', 'writer', 'theme', 'year', 'isbn'];
foreach ($required as $champ) {
    if (empty($booksData[$champ]) || $booksData[$champ] === false) {
        $_SESSION['message'] = "Tous les champs doivent être complétés ! ";
        header('Location: ../messages/message.php', true, 303);
        exit();
    }
}

$db = new Database();

// Pour retirer les traits d'union et les espaces de l'isbn
$booksData['isbn'] = preg_replace('/[\s-]+/', '', $booksData['isbn']);

// Créer un nouveau livre
$book = new Book(
    $booksData['title'],
    $booksData['writer'],
    $booksData['theme'],
    $booksData['year'],
    $booksData['isbn']
);


if($db->addBookForValidation($book)){
    $_SESSION['message'] = "Le livre a été soumis à l'administrateur pour validation !";
}

header('Location: ../messages/message.php', true, 303);
exit();
