<?php
require_once('../../db/Database.php');
session_start();

// Instancier la base de données
$db = new Database();
if (!$db->initialistion()) {
    echo "Erreur lors de l'accès à la base de données.";
    exit();
}

// Vérifier si un ID de livre est passé dans l'URL
if (isset($_GET['id'])) {
    $bookId = $_GET['id'];

    // Récupérer les détails du livre depuis la table book_validation
    $sql = "SELECT * FROM book_validation WHERE id = :bookId";
    $stmt = $db->getDb()->prepare($sql);
    $stmt->bindParam(':bookId', $bookId, PDO::PARAM_INT);
    $stmt->execute();
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$book) {
        echo "Livre non trouvé.";
        exit();
    }
} else {
    echo "ID du livre manquant.";
    exit();
}

// Fonction pour formater l'ISBN avec des tirets
function formatISBN($isbn)
{
    $isbn = preg_replace('/\D/', '', $isbn);

    if (strlen($isbn) == 13) {
        return substr($isbn, 0, 3) . '-' . substr($isbn, 3, 1) . '-' . substr($isbn, 4, 3) . '-' . substr($isbn, 7, 3) . '-' . substr($isbn, 10, 3);
    }
    return $isbn; // Retourner sans formatage si l'ISBN est incorrect
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le livre</title>
    <link rel="stylesheet" href="../../assets/css/index.css">
    <link rel="stylesheet" href="../../assets/css/form-add-book.css">


</head>

<body>

    <body>
        <header>
            <ul>
                <li><a href="../index.php">Babel</a></li>
                <li><a href="../about.php">À propos</a></li>
                <?php if (isset($_SESSION['utilisateur'])): ?>
                    <?php if ($_SESSION['utilisateur']['pseudo'] === "admin"): ?>
                        <li><a href="dashboardAdmin.php">Compte admin</a></li>
                    <?php else: ?>
                        <li><a href="../libraryUser.php">Ma bibliothèque</a></li>
                        <li><a href="../dashboardUser.php">Mon compte</a></li>
                    <?php endif; ?>
                    <li id="deconnexion"><a href="../deconnexion.php">Se déconnecter</a></li>
                <?php else: ?>
                    <li id="connexion"><a href="connexion.html">Se connecter</a></li>
                    <li id="nouveauCompte"><a href="inscription.html">Créer un compte</a></li>
                <?php endif; ?>
            </ul>
        </header>

        <main>
            <form action="processUpdate.php" method="POST">
                <h1>Modifier le livre</h1>
                <div>
                    <label for="title">Titre:</label>
                    <input type="text" name="title" id="title" value="<?= htmlspecialchars($book['Title']) ?>" required>
                </div>

                <div>
                    <label for="author">Auteur:</label>
                    <input type="text" name="author" id="author" value="<?= htmlspecialchars($book['Author']) ?>" required>
                </div>

                <div>
                    <label for="theme">Thème:</label>
                    <input type="text" name="theme" id="theme" value="<?= htmlspecialchars($book['Theme']) ?>" required>
                </div>

                <div>
                    <label for="publication_year">Année de publication:</label>
                    <input type="number" name="publication_year" id="publication_year" value="<?= htmlspecialchars($book['Parution_date']) ?>" required>
                </div>

                <div>
                    <label for="isbn">ISBN:</label>
                    <input type="text" name="isbn" id="isbn" value="<?= formatISBN($book['ISBN']) ?>" required>
                </div>

                <!-- Champ caché pour passer l'ID du livre -->
                <input type="hidden" name="book_id" value="<?= htmlspecialchars($book['id']) ?>">

                <div>
                    <button type="submit" class="button-soumission">Mettre à jour le livre</button>
                </div>
            </form>
        </main>

        <footer>
            <p>© 2024 Babel. Projet scolaire Bachelor Ingenierie des médias.</p>
        </footer>
    </body>

</html>