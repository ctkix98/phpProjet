<?php
require_once __DIR__ . '/../../config/session.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil</title>
    <link rel="stylesheet" href="../../assets/css/index.css">
</head>

<body>
    <header>
        <ul>
            <li><a href="../index.php">Babel</a></li>
            <li><a href="../about.php">A propos</a></li>
            <?php if (isset($_SESSION['utilisateur'])): ?>
                <li> <a href="../libraryUser.php">Ma bibliothèque</a></li>
                <li> <a href="../dashboardUser.php">Mon compte</a></li>
                <li id="deconnexion"><a href="../deconnexion.php">Se déconnecter</a></li>
            <?php else: ?>
                <li id="connexion"><a href="../connexion.html">Se connecter</a></li>
                <li id="nouveauCompte"><a href="../inscription.html">Créer un compte</a></li>
            <?php endif; ?>
        </ul>
    </header>
    <main class="pages">
        <div class="confirmation">
            <?php if (isset($_SESSION['message'])): ?>
                <h1><?php echo $_SESSION['message']; ?></h1>
                <a href="../index.php">Retour à la page d'accueil</a>
            <?php else: ?>
                <h1>Il y a un problème ! </h1>
                <a href="../index.php">Retour à la page d'accueil</a>
            <?php endif; ?>
        </div>

    </main>
    <footer>
        <p>© 2024 Babel. Projet scolaire Bachelor Ingenierie des médias.</p>
    </footer>
</body>

</html>