<?php
session_start();
$livres = [
    ["titre" => "Changer l'eau des fleurs", "image" => "../assets/images/book-cover-most-read/Changer l'eau des fleurs -Valérie Perrin.jpeg"],
    ["titre" => "Chien blanc", "image" => "../assets/images/book-cover-most-read/Chien blanc - Romain Gary.jpeg"],
    ["titre" => "L'affaire Alaska Sanders", "image" => "../assets/images/book-cover-most-read/L'affaire Alaska Sanders -Joël Dicker.jpeg"],
    ["titre" => "La panthère des neiges", "image" => "../assets/images/book-cover-most-read/La panthère des neiges - Sylvain Tesson.jpeg"],
    ["titre" => "La vie secrète des écrivains", "image" => "../assets/images/book-cover-most-read/La vie secrète des écrivains - Guillaume Musso.jpeg"],
    ["titre" => "Le bal des folles", "image" => "../assets/images/book-cover-most-read/Le bal des folles-Victoria Mas.jpeg"],
    ["titre" => "Le consentement", "image" => "../assets/images/book-cover-most-read/Le consentement - Vanessa Springora.jpeg"],
    ["titre" => "Le Mage du Kremlin", "image" => "../assets/images/book-cover-most-read/Le Mage du Kremlin - Giuliano da Empoli"],
    ["titre" => "Les Aérostats", "image" => "../assets/images/book-cover-most-read/Les Aérostats-Amélie Nothomb.jpeg"],
    ["titre" => "un animal sauvage", "image" => "../assets/images/book-cover-most-read/un animal sauvage - joel dicker.jpeg"],
];

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
        <div class="content">
            <?php if (isset($_SESSION['utilisateur'])): ?>
                <h1>Bienvenue <?php echo htmlspecialchars($_SESSION['utilisateur']['pseudo']); ?> !</h1>
                <div class="container">
                    <!-- Section des livres les plus lus -->
                    <div class="most-read-books">
                        <h2>Les 10 livres les plus lus</h2>
                        <ul>
                            <?php foreach ($livres as $livre): ?>
                                <li>
                                    <img src="<?php echo htmlspecialchars($livre['image']); ?>" alt="Couverture du livre <?php echo htmlspecialchars($livre['titre']); ?>">
                                    <p><?php echo htmlspecialchars($livre['titre']); ?></p>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                
            <?php else: ?>
                <h1>Bienvenue !</h1>
            <?php endif; ?>
        </div>
    </main>
    <footer>
        <p>© 2024 Babel. Projet scolaire Bachelor Ingenierie des médias.</p>
    </footer>
</body>

</html>