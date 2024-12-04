<?php
require_once __DIR__ . '/../../config/session.php';
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur dans la connexion du compte</title>
    <link rel="stylesheet" href="../stylesheet.css">

</head>

<body>
    <header>
        <ul>
            <li><a href="../index.php">Verdo Sàrl</a></li>
            <li><a href="histoire.php">Notre histoire</a></li>
            <li><a href="monCompte.php">Mon compte</a></li>
        </ul>
    </header>
    <main class="pages">
        <div class="erreur">
            <p>Il y a eu un souci :/ </p>
            <p><?php echo $messageErreur; ?> </p>
        </div>

        <div>
            <a href="connexion.php" id="idConnexion">Essayer à nouveau</a>
        </div>

    </main>
    <footer>
        <p>made with &nbsp; &#9829;&nbsp; by Cédrine Tille </p>
    </footer>

</body>

</html>