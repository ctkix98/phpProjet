<?php

require_once('../../db/Database.php');
require_once('../../class/Book.php');

session_start();

$booksData = [];

// Vérifier si l'action est définie
if (!isset($_POST['action'])) {
    $_SESSION['message'] = "Action non définie.";
    header('Location: ../messages/message.php', true, 303);
    exit();
}
$action = $_POST['action'];

if ($action == 'submit' || $action == 'update') {
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
if ($action == 'submit') {
    // Créer un nouveau livre pour validation
    $book = new Book(
        $booksData['title'],
        $booksData['writer'],
        $booksData['theme'],
        $booksData['year'],
        $booksData['isbn']
    );

    if ($db->addBookForValidation($book)) {
        $_SESSION['message'] = "Le livre a été soumis à l'administrateur pour validation !";
    } else {
        $_SESSION['message'] = "Le livre n'a pas été soumis à l'administrateur pour validation.";
    }
} elseif ($action === 'update') {
    if (isset($_POST['book_id'])) {
        $bookId = $_POST['book_id'];
        $coverImagePath = null;

        // Vérifier si un fichier a été téléversé
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $fileExtension = strtolower(pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION));

            if (in_array($fileExtension, $allowedExtensions)) {
                $uploadsDir = '../../assets/images/covers/';
                $newFileName = uniqid() . '.' . $fileExtension;
                $destination = $uploadsDir . $newFileName;

                // Déplacer le fichier téléversé
                if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $destination)) {
                    $coverImagePath = str_replace('../../', '', $destination); // Chemin relatif pour la base
                } else {
                    $_SESSION['message'] = "Erreur lors du téléversement de l'image.";
                    header('Location: ../messages/message.php', true, 303);
                    exit();
                }
            } else {
                $_SESSION['message'] = "Extension de fichier non autorisée. Seuls JPG et PNG sont acceptés.";
                header('Location: ../messages/message.php', true, 303);
                exit();
            }
        }

        // Ajouter le chemin de l'image dans l'objet livre
        $book = new Book(
            $booksData['title'],
            $booksData['writer'],
            $booksData['theme'],
            $booksData['year'],
            $booksData['isbn'],
            $coverImagePath // Ajouter le chemin de l'image ici
        );

        if ($db->addBook($book)) {
            $db->updateBookValidationStatus($bookId, "approved");
            $_SESSION['message'] = "Le livre a été mis à jour avec succès !";
        } else {
            $_SESSION['message'] = "Erreur lors de la mise à jour du livre.";
        }
    }
}


header('Location: ../messages/message.php', true, 303);
exit();
