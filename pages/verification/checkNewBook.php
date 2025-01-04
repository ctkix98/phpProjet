<?php

require_once('../../db/Database.php');
session_start();

$booksData = [];

if (filter_has_var(INPUT_POST, 'submit')) {
    $booksData['title'] = filter_input(INPUT_POST, 'title', FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^.{1,100}$/"]]);
    $booksData['writer'] = filter_input(INPUT_POST, 'writer', FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^.{1,100}$/"]]);
    $booksData['editor'] = filter_input(INPUT_POST, 'editor', FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^.{1,100}$/"]]);
    $booksData['year'] = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^\d{4}$/"]]);
    $booksData['isbn'] = filter_input(INPUT_POST, 'isbn', FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^(97[89])?(\d{1,5})[- ]?(\d{1,7})[- ]?(\d{1,6})[- ]?(\d{1})$/"]]);

}else {
    $_SESSION['message'] = "Les informations entrées ne sont pas conformes à la demande";
    header('Location: ../messages/message.php', true, 303);
    exit();
}


$required = ['title', 'writer', 'editor', 'year', 'isbn'];
foreach ($required as $champ) {
    if (empty($booksData[$champ])) {
        $_SESSION['message'] = "Tous les champs doivent être complétés ! ";
        header('Location: ../messages/message.php', true, 303);
        exit();
    }else {
        $_SESSION['message'] = "Le livre enregistré a été transmis à l'administrateur pour validation !";
        header('Location: ../messages/message.php', true, 303);
        exit();
    }
}