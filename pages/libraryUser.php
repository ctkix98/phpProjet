<?php
//session_start();
require_once __DIR__ . '/../config/session.php';

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma bibliothèque</title>
    <link rel="stylesheet" href="../assets/css/mylibrary.css">
    <link rel="stylesheet" href="../assets/css/index.css">
    <link rel="stylesheet" href="../assets/css/normalize.css">
</head>

<body>
<header>
        <ul>
            <li><a href="index.php">Babel</a></li>
            <li><a href="about.php">A propos</a></li>
            <?php if (isset($_SESSION['utilisateur'])): ?>
                <li> <a href="libraryUser.php">Ma bibliothèque</a></li>
                <li> <a href="dashboardUser.php">Mon compte</a></li>
                <li id="deconnexion"><a href="deconnexion.php">Se déconnecter</a></li>
            <?php else: ?>
                <!-- <li id="connexion"><a href="connexion.html">Se connecter</a></li>
                <li id="nouveauCompte"><a href="inscription.html">Créer un compte</a></li> -->
            <?php endif; ?>
        </ul>
    </header>
    <main>
        <section class="my-library">
            <h1>Ma bibliothèque</h1>
            <div class="container-all">
                <div class="container">
                    <details open>
                        <summary class="reading">
                            <h2>En cours</h2>
                        </summary>
                        <div class="details-container">
                                <div class="book-item">
                                    <img src="../assets/images/placeholder-mylibrary.png" alt="book-cover">
                                    <h3 class="book-title">Livre 1</h3>
                                </div>
                                <div class="book-item">
                                    <img src="../assets/images/placeholder-mylibrary.png" alt="book-cover">
                                    <h3 class="book-title">Livre 2</h3>
                                </div>
                                <div class="book-item">
                                    <img src="../assets/images/placeholder-mylibrary.png" alt="book-cover">
                                    <h3 class="book-title">Livre 3</h3>
                                </div>
                                <div class="book-item">
                                    <img src="../assets/images/placeholder-mylibrary.png" alt="book-cover">
                                    <h3 class="book-title">Livre 4</h3>
                                </div>
                                <div class="book-item">
                                    <img src="../assets/images/placeholder-mylibrary.png" alt="book-cover">
                                    <h3 class="book-title">Livre 5</h3>
                                </div>
                                <div class="book-item">
                                    <img src="../assets/images/placeholder-mylibrary.png" alt="book-cover">
                                    <h3 class="book-title">Livre 6</h3>
                                </div>
                        </div>
                    </details>
                </div>
                <div class="container">
                    <details>
                        <summary class="read-want">
                            <h2>À lire</h2>
                        </summary>
                        <div class="details-container">
                        <div class="book-item">
                                    <img src="../assets/images/placeholder-mylibrary.png" alt="book-cover">
                                    <h3 class="book-title"></h3>
                                </div>
                        </div>
                    </details>
                </div>
                <div class="container">
                    <details>
                        <summary class="read-done">
                            <h2>Terminé</h2>
                        </summary>
                        <div class="book-item">
                                    <img src="../assets/images/placeholder-mylibrary.png" alt="book-cover">
                                    <h3 class="book-title"></h3>
                                </div>
                    </details>
                </div>
                <div class="container">
                    <details>
                        <summary class="read-dropped">
                            <h2>Abandonné</h2>
                        </summary>
                        <div class="book-item">
                                    <img src="../assets/images/placeholder-mylibrary.png" alt="book-cover">
                                    <h3 class="book-title"></h3>
                                </div>
                    </details>
                </div>
            </div>
        </section>
    </main>
    <footer>
    <div>
        <p>© 2024 Babel. Projet scolaire Bachelor Ingenierie des médias.</p>
    </div>
</footer>
    <script src=""></script>
</body>

</html>