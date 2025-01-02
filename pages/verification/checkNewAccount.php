<?php
require_once('../../db/Database.php');
require_once('../../lib/vendor/autoload.php');
session_start();

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

$donneeUtilisateur = [];
$message = "";
// Recevoir les données et vérifier si elles sont valides
if (filter_has_var(INPUT_POST, 'submit1')) {
    $donneeUtilisateur['pseudo'] = filter_input(INPUT_POST, 'pseudo');
    $donneeUtilisateur['email'] = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $donneeUtilisateur['password'] = filter_input(INPUT_POST, 'password', FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^[A-Za-z0-9$!€£]{8,20}$/"]]);
    foreach ($donneeUtilisateur as $champ) {
        echo $champ;
        echo '<br>';
    }
} else {
    $message = "Les informations entrées ne sont pas conformes à la demande".$_POST;
    $_SESSION['message'] = $message;
    header('Location: ../messages/message.php', true, 303);
    exit();
}

// Vérifier si tous les champs sont remplis
$required = [
    'pseudo',
    'email',
    'password',
];
foreach ($required as $champ) {
    if (empty($donneeUtilisateur[$champ])) {
        $message = "Le champ " . $champ . "est vide";
        $_SESSION['message'] = $message;
        header('Location: ../messages/message.php', true, 303);
        exit();
    }
}

// Traitement des données
$donneeUtilisateur['email'] = strtolower($donneeUtilisateur['email']);

// Changer le mot de passe pour un hash
$donneeUtilisateur['password'] = password_hash($donneeUtilisateur['password'], PASSWORD_DEFAULT);

// Appel de la DB
$db = new Database();
if ($db->initialistion()) {
    echo "Initialisation réussie :-) <br>";
}

// Création d'une nouvelle personne avec les données enregistrées
$personne = new Personne(
    $donneeUtilisateur['pseudo'],
    $donneeUtilisateur['email'],
    $donneeUtilisateur['password']
);

// Génération du token de confirmation
$token = $personne->rendToken();
$id = $db->ajouterPersonne($personne);
if ($id > 0) {

    // Envoi du mail de confirmation
    $confirmationLink = "http://localhost/phpProjet/pages/verification/confirmationEmail.php?token=" . urlencode($token);

    try {
        // Configuration de l'envoi du mail via Symfony Mailer
        $transport = Transport::fromDsn('smtp://localhost:1025'); // Remplacez avec votre configuration SMTP
        $mailer = new Mailer($transport);

        $message = (new Email())
            ->from('support@babel.com')
            ->to($donneeUtilisateur['email'])
            ->subject('Confirmation de votre inscription')
            ->html("
                <p>Bonjour " . $donneeUtilisateur['pseudo'] . ",</p>
                <p>Merci de vous être inscrit ! Veuillez confirmer votre inscription en cliquant sur le lien ci-dessous :</p>
                <p><a href='$confirmationLink'>Confirmer mon inscription</a></p>
            ");

        $mailer->send($message);        
        // Si l'e-mail a été envoyé, redirection vers une page de succès
        $message = "Bravo, tu as réussi ton inscription ! Un mail de confirmation a été envoyé à ton adresse";
        $_SESSION['message'] = $message;
        header('Location: ../messages/message.php', true, 303);
        exit();

    } catch (Exception $e) {
        $message = "Une erreur est survenue lors de l'envoi du mail de confirmation.";
        $_SESSION['message'] = $message;
        header('Location: ../messages/message.php', true, 303);
        exit();
    }
} else {
    $message = "Le compte n'a pas pu être créé, car l'email est déjà utilisé";
    $_SESSION['message'] = $message;
    header('Location: ../messages/message.php', true, 303);
    exit();
}
?>
