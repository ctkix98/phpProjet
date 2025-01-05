<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil</title>
    <link rel="stylesheet" href="../assets/css/index.css">
</head>

<body>
    <header>
        <ul>
            <li><a href="index.php">Babel</a></li>
            <li><a href="about.php">À propos</a></li>
            <?php if (isset($_SESSION['utilisateur'])): ?>
                <li> <a href="libraryUser.php">Ma bibliothèque</a></li>
                <li> <a href="dashboardUser.php">Mon compte</a></li>
                <li id="deconnexion"><a href="deconnexion.php">Se déconnecter</a></li>
            <?php else: ?>
                <li id="connexion"><a href="connexion.html">Se connecter</a></li>
                <li id="nouveauCompte"><a href="inscription.html">Créer un compte</a></li>
            <?php endif; ?>
        </ul>
    </header>
    <main>
    <div class="container">
            <!-- Section des livres les plus lus -->
            <div class="most-read-books">
                <h2>Les livres les plus lus</h2>
                <ul>
                    <li>
                        <img src="../assets/images/book-cover-most-read/Changer l'eau des fleurs -Valérie Perrin.jpeg" alt="cover of the book Changer l'eau des fleurs">
                    </li>
                    <li>
                        <img src="../assets/images/book-cover-most-read/Chien blanc - Romain Gary.jpeg" alt="Cover of the book Chien blanc">
                    </li>
                    <li>
                        <img src="../assets/images/book-cover-most-read/L'affaire Alaska Sanders -Joël Dicker.jpeg" alt="Cover of the book L'affaire Alaska Sanders">
                    </li>
                    <li>
                    <img src="../assets/images/book-cover-most-read/La panthère des neiges - Sylvain Tesson.jpeg" alt="Cover of the book La panthère des neiges">
                    <li>
                    <img src="../assets/images/book-cover-most-read/La vie secrète des écrivains - Guillaume Musso.jpeg" alt="Cover of the book La vie secrète des écrivains">
                    </li>
                    <li>
                    <img src="../assets/images/book-cover-most-read/Le bal des folles-Victoria Mas.jpeg" alt="Cover of the book Le bal des folles">
                    </li>
                    <li>
                    <img src="../assets/images/book-cover-most-read/Le consentement - Vanessa Springora.jpeg" alt="Cover of the book Le consentement">
                    </li>
                    <li>
                    <img src="../assets/images/book-cover-most-read/Le Mage du Kremlin - Giuliano da Empoli.jpeg" alt="Cover of the book Le mage du Kremlin">
                    <li>
                    <img src="../assets/images/book-cover-most-read/Les Aérostats-Amélie Nothomb.jpeg" alt="Cover of the book Les Aérostats">
                    </li>
                    <li>
                    <img src="../assets/images/book-cover-most-read/un animal sauvage - joel dicker.jpeg" alt="Cover of the book Un animal sauvage">
                    </li>
                        </ul>
                    </div>
    </div>
        <!-- Contenu principal --> 
        <div class="content">
            <?php if (isset($_SESSION['utilisateur'])): ?>
                <h1>Bienvenue <?php echo htmlspecialchars($_SESSION['utilisateur']['pseudo']); ?> !</h1>
            <?php else: ?>
                <h1>Bienvenue ! </h1>
            <?php endif; ?>
        </div>
    </main>
    <footer>
        <p>© 2024 Babel. Projet scolaire Bachelor Ingenierie des médias.</p>
    </footer>
</body>

</html>