<?php
$dsn = 'sqlite:../../db/babel.sqlite'; 
$pdo = new PDO($dsn);

// Répertoire de téléchargement
$uploadDir = '../assets/uploads/covers';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Récupérez les livres sans couverture
$stmt = $pdo->query("SELECT id, ISBN, OLID, Title, Author FROM book WHERE cover_image_path IS NULL");
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($books as $book) {
    $bookId = $book['id'];
    $isbn = $book['ISBN'];
    $olid = $book['OLID'];

    // Définir l'URL de couverture
    $coverUrl = null;
    if (!empty($isbn)) {
        $coverUrl = "https://covers.openlibrary.org/b/isbn/$isbn-L.jpg";
    } elseif (!empty($olid)) {
        $coverUrl = "https://covers.openlibrary.org/b/olid/$olid-L.jpg";
    } else {
        // Recherche via l'API Open Library
        $title = urlencode($book['Title']);
        $author = urlencode($book['Author']);
        $response = file_get_contents("https://openlibrary.org/search.json?title=$title&author=$author");
        $data = json_decode($response, true);
        if (!empty($data['docs'][0]['key'])) {
            $olid = str_replace('/works/', '', $data['docs'][0]['key']);
            $coverUrl = "https://covers.openlibrary.org/b/olid/$olid-L.jpg";

            // Mettre à jour l'OLID dans la base
            $updateStmt = $pdo->prepare("UPDATE book SET OLID = :olid WHERE id = :id");
            $updateStmt->execute([':olid' => $olid, ':id' => $bookId]);
        }
    }

    // Téléchargez l'image si une URL est trouvée
    if ($coverUrl && @file_get_contents($coverUrl)) {
        $imagePath = $uploadDir . $bookId . '.jpg';
        file_put_contents($imagePath, file_get_contents($coverUrl));

        // Mettre à jour le chemin de l'image dans la base
        $updateStmt = $pdo->prepare("UPDATE book SET cover_image_path = :path WHERE id = :id");
        $updateStmt->execute([':path' => $imagePath, ':id' => $bookId]);

        echo "Couverture téléchargée pour le livre avec ID $bookId.\n";
    } else {
        echo "Pas de couverture disponible pour le livre avec ID $bookId.\n";
    }

    // Ajoutez un délai pour éviter un blocage par l'API
    sleep(1);
}
?>
