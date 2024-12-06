<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <header>
        <ul>
            <li><a href="index.php">Babel</a></li>
            <li><a href="about.html">A propos</a></li>
            <?php if (isset($_SESSION['utilisateur'])): ?>
                <li id="deconnexion"><a href="deconnexion.php">Se déconnecter</a></li>
            <?php else: ?>
                <li id="connexion"><a href="connexion.html">Se connecter</a></li>
                <li id="nouveauCompte"><a href="inscription.html">Créer un compte</a></li>
            <?php endif; ?>
        </ul>
    </header>

    <div class="content">
        <?php if (isset($_SESSION['utilisateur'])): ?>
            <h1>Bienvenue <?php echo htmlspecialchars($_SESSION['utilisateur']['prenom']); ?> !</h1>
        <?php else: ?>
            <h1>Bienvenue ! </h1>
        <?php endif; ?>
    </div>

    <footer>
        <p>made with &nbsp; &#9829;&nbsp; by Cédrine Tille </p>
    </footer>
</body>

</html>