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
                        <img src="../assets/images/book-cover-most" alt="Cover of the book Le Petit Prince">
                    </li>
                    <li>
                        <img src="../assets/images/book-cover-reading/Les-Misérables–Victor-Hugo.jpeg" alt="Cover of the book Les-Misérables">
                    </li>
                    <li>
                        <img src="../assets/images/book-cover-reading/Dune – Frank Herbert.jpeg" alt="Cover of the book Dune">
                    </li>
                    <li>
                    <img src="../assets/images/book-cover-reading/La Passe-Miroir (Tome 1 - Les Fiancés de l'Hiver) .jpeg" alt="Cover of the book La passe miroir">
                    <li>
                    <img src="../assets/images/book-cover-reading/Le Clan des Otori (Tome 1 - Le Silence du Rossignol) – Lian Hearn.jpeg" alt="Cover of the book Clan des Otori - Le silence du rossignol">
                    </li>
                    <li>
                    <img src="../assets/images/book-cover-reading/Les-Misérables–Victor-Hugo.jpeg" alt="Cover of the book Les-Misérables">
                    </li>
                    <li>
                    <img src="../assets/images/book-cover-reading/Dune – Frank Herbert.jpeg" alt="Cover of the book Dune">
                    </li>
                    <li>
                    <img src="../assets/images/book-cover-reading/L'Alchimiste – Paulo Coelho.jpeg" alt="Cover of the book L'Alchimiste">
                    <li>
                    <img src="../assets/images/book-cover-reading/Mille Soleils Splendides – Khaled Hosseini.jpeg" alt="Cover of the book Mille Soleils Splendides">
                    </li>
                    <li>
                    <img src="../assets/images/book-cover-reading/Mille Soleils Splendides – Khaled Hosseini.jpeg" alt="Cover of the book Mille Soleils Splendides">
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