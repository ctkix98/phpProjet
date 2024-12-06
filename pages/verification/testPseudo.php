<?php
require_once('../../db/Database.php');
session_start();

$donneeUtilisateur = [];
$message = "";

//Recevoir les données, et vérifier si c'est juste
if (filter_has_var(INPUT_POST, 'submit')) { //ok ça fonctionne
    $donneeUtilisateur['pseudo'] = filter_input(INPUT_POST, 'pseudo');
    $donneeUtilisateur['email'] = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $donneeUtilisateur['password'] = filter_input(INPUT_POST, 'password', FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^[A-Za-z0-9$!€£]{8,20}$/"]]);

    foreach($donneeUtilisateur as $champ){
        echo $champ;
        echo '<br>';
    }
}