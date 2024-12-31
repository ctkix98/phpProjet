<?php
require_once __DIR__ . '/../../config/session.php';
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur dans la connexion du compte</title>
    <link rel="stylesheet" href="../../assets/css/index.css">

</head>

<body>
<header>
        <ul>
            <li><a href="../index.php">Babel</a></li>
            <li><a href="../about.php">Notre histoire</a></li>
            <li id="connexion"><a href="../connexion.html">Se connecter</a></li>
            <li id="nouveauCompte"><a href="../inscription.html">Créer un compte</a></li>
        </ul>
    </header>
    <main class="pages">
        <div class="erreur">
            <p>Il y a eu un souci :/ </p>
            <p><?php echo $_SESSION['message']; ?> </p>
        </div>

        <div>
            <a href="../index.php" id="accueil">Accueil</a>
        </div>

    </main>
    <footer>
        <p>made with &nbsp; &#9829;&nbsp; by Cédrine Tille </p>
    </footer>

</body>

</html>