<?php
session_start();

// Vérifiez si l'utilisateur est un administrateur
if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['pseudo'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Charger les livres en attente
$booksFile = '../books/books_pending.json';
$booksData = file_exists($booksFile) ? json_decode(file_get_contents($booksFile), true) : [];

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord administrateur</title>
</head>
<body>
    <h1>Livres soumis pour validation</h1>
    <table>
        <thead>
            <tr>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Maison d'édition</th>
                <th>Année de publication</th>
                <th>ISBN</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($booksData as $book): ?>
                <tr>
                    <td><?= htmlspecialchars($book['title']); ?></td>
                    <td><?= htmlspecialchars($book['writer']); ?></td>
                    <td><?= htmlspecialchars($book['editor']); ?></td>
                    <td><?= htmlspecialchars($book['year']); ?></td>
                    <td><?= htmlspecialchars($book['isbn']); ?></td>
                    <td>
                        <form action="validateBook.php" method="POST">
                            <input type="hidden" name="isbn" value="<?= htmlspecialchars($book['isbn']); ?>">
                            <button type="submit" name="action" value="approve">Approuver</button>
                            <button type="submit" name="action" value="reject">Rejeter</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
