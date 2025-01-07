<?php
require_once('../../db/Database.php');
session_start();

$db = new Database();


// Récupération de 10 livres aléatoires
$tenBooks = $db->getRandomBooks();

// Fonction pour afficher les livres
function displayBooks($books)
{
    if (empty($books)) {
        echo '<p>Aucun livre disponible pour le moment.</p>';
        return;
    }
    foreach ($books as $book) {
        // Vérification du chemin de l'image de couverture
        $coverPath = !empty($book['cover_image_path'])
            ? '../../' . htmlspecialchars($book['cover_image_path'])
            : '../../assets/images/covers/placeholder-mylibrary.jpg';

        echo '<div class="book-item">
                <a href="bookinfo.php?id=' . htmlspecialchars($book['id']) . '">
                    <img src="' . $coverPath . '" alt="book-cover" />
                    <h3 class="book-title">' . htmlspecialchars($book['Title']) . '</h3>
                </a>
                <h4 class="author">' . htmlspecialchars($book['Author']) . '</h4>
              </div>';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil</title>
    <link rel="stylesheet" href="../../assets/css/index.css">
    <link rel="stylesheet" href="../../assets/css/form-add-book.css">
    <link rel="stylesheet" href="../../assets/css/library.css">
    <link rel="stylesheet" href="../../assets/css/normalize.css">
    <script src="../../assets/js/checkisbn.js" defer></script>
</head>

<body>
    <header>
        <ul>
            <li><a href="homepage.php">Babel</a></li>
            <li><a href="../about.php">A propos</a></li>
            <li><a href="library.php">Bibliothèque</a></li>

            <?php if (isset($_SESSION['utilisateur'])): ?>
                <?php if ($_SESSION['utilisateur']['pseudo'] === "admin"): ?>
                    <!-- Lien admin -->
                    <li><a href="dashboardAdmin.php">Tableau de bord</a></li>
                    <li><a href="../adminPage.php">Compte admin</a></li>
                <?php else: ?>
                    <!-- Lien utilisateur -->
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
        <div class="content">
            <?php if (isset($_SESSION['utilisateur'])): ?>
                <h1>Bienvenue <?php echo htmlspecialchars($_SESSION['utilisateur']['pseudo']); ?> !</h1>
            <?php else: ?>
                <h1>Bienvenue !</h1>
            <?php endif; ?>

            <div class="books-container">
                <!-- Affichage des livres -->
                <?php displayBooks($tenBooks); ?>
            </div>

            <?php if (isset($_SESSION['utilisateur'])): ?>
                <div class="add-book-form">
                    <form action="../verification/checkNewBook.php" method="POST">
                        <h2>Ajouter un livre</h2>

                        <div class="form-group">
                            <label for="title">Titre</label>
                            <input type="text" id="title" name="title" placeholder="Le Petit Prince" required>
                        </div>

                        <div class="form-group">
                            <label for="writer">Auteur</label>
                            <input type="text" id="writer" name="writer" placeholder="Antoine de Saint-Exupéry" required>
                        </div>

                        <div class="form-group">
                            <label for="theme">Thème</label>
                            <input type="text" id="theme" name="theme" placeholder="Romance" required>
                        </div>

                        <div class="form-group">
                            <label for="year">Année de publication</label>
                            <input type="number" id="year" name="year" min="1000" max="9999" placeholder="1943" required>
                        </div>

                        <div class="form-group">
                            <label for="isbn">ISBN</label>
                            <input type="text" id="isbn" name="isbn" placeholder="978-3-16-148410-0" required>
                            <div id="isbn-error" style="color: red; font-size: 12px; display: none;"></div>
                        </div>
                        <input type="hidden" name="action" value="submit">
                        <button type="submit" name="submit" class="button-soumission">Soumettre l'ouvrage</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </main>
    <footer>
    <p>© 2024 Babel. Projet scolaire Bachelor Ingenierie des médias.</p>
  </footer>
</body>

</html>