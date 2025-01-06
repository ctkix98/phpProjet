<?php
//session_start(); // Démarrer la session
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
            <li><a href="library.php">Bibliothèque</a></li>
            <?php if (isset($_SESSION['utilisateur'])): ?>
                <?php if ($_SESSION['utilisateur']['pseudo'] === "admin"): ?>
                    <!-- Si l'utilisateur est admin, afficher le lien vers le tableau de bord admin -->
                    <li> <a href="dashboardAdmin.php">Compte admin</a></li>
                <?php else: ?>
                    <!-- Si l'utilisateur n'est pas admin, afficher son compte utilisateur -->
                    <li> <a href="libraryUser.php">Ma bibliothèque</a></li>
                    <li> <a href="dashboardUser.php">Mon compte</a></li>
                <?php endif; ?>
                <li id="deconnexion"><a href="deconnexion.php">Se déconnecter</a></li>
            <?php else: ?>
                <li id="connexion"><a href="connexion.html">Se connecter</a></li>
                <li id="nouveauCompte"><a href="inscription.html">Créer un compte</a></li>
            <?php endif; ?>
        </ul>
    </header>
    <main>
        <?php if (isset($_SESSION['utilisateur'])): ?>
            <section class="my-library">
                <h1>Ma bibliothèque</h1>
                <div class="container-all">
                    <div class="container">
                        <details open>
                            <summary class="reading">
                                <h2>En cours</h2>
                            </summary>
                            <div class="details-container">
                                <!-- Exemple de livres en cours -->
                                <div class="book-item">
                                    <a href="bookinfo.php">
                                        <img src="../assets/images/placeholder-mylibrary.png" alt="book-cover">
                                        <h3 class="book-title">Livre 1</h3>
                                    </a>
                                    <h4 class="author">Auteur 1</h4>
                                </div>
                                <div class="book-item">
                                    <img src="../assets/images/placeholder-mylibrary.png" alt="book-cover">
                                    <h3 class="book-title">Livre 2</h3>
                                    <h4 class="author">Auteur 2</h4>
                                </div>
                                <div class="book-item">
                                    <img src="../assets/images/placeholder-mylibrary.png" alt="book-cover">
                                    <h3 class="book-title">Livre 3</h3>
                                    <h4 class="author">Auteur 3</h4>
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
                                    <h3 class="book-title">Livre à lire</h3>
                                    <h4 class="author">Auteur inconnu</h4>
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
                                <h3 class="book-title">Livre terminé</h3>
                                <h4 class="author">Auteur terminé</h4>
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
                                <h3 class="book-title">Livre abandonné</h3>
                                <h4 class="author">Auteur abandonné</h4>
                            </div>
                        </details>
                    </div>
                </div>
            </section>
        <?php else: ?>
            <h1>Il semblerait que tu ne sois pas connecté :/</h1>
        <?php endif; ?>
    </main>
    <footer>
        <div>
            <p>© 2024 Babel. Projet scolaire Bachelor Ingenierie des médias.</p>
        </div>
    </footer>
</body>

</html>
