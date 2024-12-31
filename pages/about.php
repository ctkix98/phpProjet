<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A propos</title>
    <link rel="stylesheet" href="../assets/css/index.css">
    <link rel="stylesheet" href="../assets/css/about.css">
</head>
<body>
    <header>
        <ul>
            <li><a href="index.php">Babel</a></li>
            <li><a href="about.php">A propos</a></li>
            <?php if (isset($_SESSION['utilisateur'])): ?>
                <li> <a href="libraryUser.php">Ma bibliothèque</a></li>
                <li id="deconnexion"><a href="deconnexion.php">Se déconnecter</a></li>
            <?php else: ?>
                <li id="connexion"><a href="connexion.html">Se connecter</a></li>
                <li id="nouveauCompte"><a href="inscription.html">Créer un compte</a></li>
            <?php endif; ?>
        </ul>
    </header>
    <main>
        <section class="about-content">
            <h1>À propos de Babel</h1>
            <div class="illustration">
                <img src="../assets/images/AdobeStock_483126043.jpeg" alt="Illustration de livre">
            </div>
            <article>
                <p>
                    Bienvenue sur notre plateforme dédiée aux passionnés de lecture ! 
                    Ici, vous pouvez suivre l'état de vos lectures, découvrir de nouvelles œuvres, 
                    et enrichir votre bibliothèque personnelle.
                </p>

                <h2>Notre mission</h2>
                <p>
                    Nous croyons que la lecture est un voyage qui doit être accessible à tous. 
                    Notre mission est de simplifier le suivi de vos lectures tout en vous inspirant à explorer de nouveaux horizons littéraires.
                </p>

                <h2>Équipe</h2>
                <p>
                    Notre équipe est composée de développeurs passionnés, travaillant main dans la main pour 
                    créer une expérience utilisateur unique et intuitive. Ensemble, nous partageons l'amour des livres et de la technologie.
                </p>
            </article>
        </section>
    </main>
    <footer>
        <p>© 2024 Babel. Projet scolaire Bachelor Ingenierie des médias.</p>
    </footer>
</body>
</html>
