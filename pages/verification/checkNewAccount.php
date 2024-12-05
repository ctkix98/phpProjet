<?php
require_once('../../db/Database.php');
session_start();

$donneeUtilisateur = [];
$message = "";

//Recevoir les données, et vérifier si c'est juste
if (filter_has_var(INPUT_POST, 'submit')) {
    $donneeUtilisateur['pseudo'] = filter_input(INPUT_POST, 'pseudo');
    $donneeUtilisateur['email'] = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $donneeUtilisateur['password'] = filter_input(INPUT_POST, 'password', FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^[A-Za-z0-9$!€£]{8,20}$/"]]);

    echo'<br>';
    foreach($donneeUtilisateur as $champ){
        echo $champ;
        echo '<br>';
    }
} else {
    $message = "Les informations entrées ne sont pas conformes à la demande";
    $_SESSION['message'] = $message;
    header('Location: ../messages/errorMessage.php', true, 303);
    exit();
}

//Vérifier si tous les champs sont remplis
$required = [
    'pseudo',
    'email',
    'password',
];
foreach ($required as $champ) {
    if (empty($donneeUtilisateur[$champ])) {
        $message = "Le champ ".$champ ."est vide";
        $_SESSION['message'] = $message;
        header('Location: ../messages/errorMessage.php', true, 303);
        exit();
    }
}

//Traitement des données
$donneeUtilisateur['email'] = strtolower($donneeUtilisateur['email']);

//changer password pour un hash
$donneeUtilisateur['password'] = password_hash($donneeUtilisateur['password'], PASSWORD_DEFAULT);


//Appel de la DB
$db = new Database();
if ($db->creerTablePersonnes()) {
    echo "Création de la table 'personnes' réussie :-) <br>";
}


//Création d'une nouvelle personne avec les données enregistrées
$personne = new Personne(
    $donneeUtilisateur['pseudo'],
    $donneeUtilisateur['email'],
    $donneeUtilisateur['password']
);
$id = $db->ajouterPersonne($personne);
if ($id > 0) {
    //Redirection sur la page de confirmation de création de compte
    header('Location: ../pages/confirmationCreationCompte.php', true, 303);
    exit();
} else {
    $message = "Le compte n'a pas pu être créé, car le numéro de téléphone ou l'email est déjà utilisé";
    $_SESSION['message'] = $message;
    header('Location: ../messages/errorMessage.php', true, 303);
    exit();
}