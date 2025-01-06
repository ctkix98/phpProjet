<?php
// Vérifie si la page actuelle n'est pas "index.php"
if (basename($_SERVER['PHP_SELF']) !== 'index.php') {
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>header</title>
    <link rel="stylesheet" href="../assets/css/header.css">
</head>
<header>
    <div class="header-container">
    <!-- Logo -->
    <div class="logo">
    <li><a href="index.php">Babel</a></li>
       <!--  <a href="index.php">
            <img src="../assets/images/logo-babel.png" alt="Logo Babel">
        </a>-->
    </div>

        <!-- Barre de recherche -->
        <div class="search-bar">
            <form action="search.php" method="get">
                <input type="text" name="q" placeholder="Rechercher un livre, un auteur..." />
                <button type="submit">🔍</button>
            </form>
        </div>

        <!-- Liens et icônes -->
        <div class="header-links">
            <a href="libraryUser.php" class="library-button">📚 Ma bibliothèque</a>
            <a href="dashboardUser.php" class="account-icon">👤</a>
        </div>
    </div>
</header>
<?php
}
?>
