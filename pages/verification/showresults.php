<?php
session_start();
require_once('../../db/Database.php');
require_once('../../class/Book.php');

// Instancier la base de données

$db = new Database();
if (!$db->initialisation()) {
    echo "Erreur lors de l'accès à la base de données.";
    exit();
}

$ids = array_column($_SESSION['search'], 'id');
$books = [];
foreach ($ids as $id) {
    $book = $db->getBooksById($id);
    $tempo = new Book(
        $book['Title'],
        $book['Author'],
        $book['theme'],
        $book['Parution_date'],
        $book['ISBN'],
        $book['cover_image_path'],
        $book['id']
    );
    array_push($books, $tempo);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bibliothèque</title>
    <link rel="stylesheet" href="../../assets/css/index.css">
    <link rel="stylesheet" href="../../assets/css/library.css">
    <link rel="stylesheet" href="../../assets/css/normalize.css">
</head>

<body>
    <header>
        <ul>
            <li><a href="../index.php">Babel</a></li>
            <li><a href="../about.php">A propos</a></li>
            <li><a href="library.php">Bibliothèque</a></li>
            <?php if (isset($_SESSION['utilisateur'])): ?>
                <?php if ($_SESSION['utilisateur']['pseudo'] === "admin"): ?>
                    <li><a href="dashboardAdmin.php">Tableau de bord</a></li>
                    <li><a href="../adminPage.php">Compte admin</a></li>
                <?php else: ?>
                    <li><a href="libraryUser.php">Mes lectures</a></li>
                    <li><a href="../dashboardUser.php">Mon compte</a></li>
                <?php endif; ?>
                <li id="deconnexion"><a href="../deconnexion.php">Se déconnecter</a></li>
            <?php else: ?>
                <li id="connexion"><a href="../connexion.html">Se connecter</a></li>
                <li id="nouveauCompte"><a href="../inscription.html">Créer un compte</a></li>
            <?php endif; ?>
        </ul>
    </header>
    <main>
        <div class="form-container">
            <form action="searchbar.php" method="POST">
                <input type="text" id="search" name="search" placeholder="Recherche par titre ou auteur" />
                <input type="submit" name="submit" value="Recherche" />
            </form>
        </div>
        <div class="books-container">
            <?php if (!empty($books)): ?>
                <?php foreach ($books as $book): ?>
                    <div class="book-item">
                        <a href="bookinfo.php?id=<?= htmlspecialchars($book->getId()) ?>">
                            <?php
                            // Vérifier si le champ cover_image_path est vide ou null
                            $coverPath = !empty($book->getCoverImagePath()) ? '../../assets/images/' . $book->getCoverImagePath() : '../../assets/images/covers/placeholder-mylibrary.jpg';
                            ?>
                            <img src="<?= htmlspecialchars($coverPath) ?>" alt="book-cover" />
                            <h3 class="book-title"><?= htmlspecialchars($book->getTitle()) ?></h3>
                        </a>
                        <h4 class="author"><?= htmlspecialchars($book->getAuthor()) ?></h4>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun livre disponible.</p>
            <?php endif; ?>
        </div>
    </main>
    <footer>
        <p>© 2024 Babel. Projet scolaire Bachelor Ingenierie des médias.</p>
    </footer>
</body>

</html>