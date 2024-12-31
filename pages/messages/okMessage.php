<?php
require_once __DIR__ . '/../../config/session.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connecté</title>
    <link rel="stylesheet" href="../../assets/css/index.css">
</head>

<body>
    <header>
        <ul>
            <li><a href="../index.php">Babel</a></li>
            <li><a href="../about.php">Notre histoire</a></li>
            <li><a href="../dashboardUser.html">Mon compte</a></li>
            <li> <a href="../libraryUser.php">Ma bibliothèque</a></li>
            <li id="deconnexion"><a href="../deconnexion.php">Se déconnecter</a></li>
        </ul>
    </header>
    <main class="pages">
        <div class="confirmation">
            <h1>Salut <?php echo $pseudo; ?> ! </h1>
            <p>Félicitation tu es connecté.e !</p>
        </div>

        <div id='donneesUtilisateur'>
            <h2>Voici tes données </h2>
            <p><?php echo $pseudo; ?></p>
            <p><?php echo $email; ?></p>
        </div>

    </main>
    <footer>
        <p>made with &nbsp; &#9829;&nbsp; by Cédrine Tille </p>
    </footer>

</body>

</html>