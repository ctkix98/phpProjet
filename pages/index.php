<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil</title>
    <link rel="stylesheet" href="../assets/css/index.css">
    <link rel="stylesheet" href="../assets/css/form-add-book.css">
    <script src="../assets/js/checkisbn.js" defer></script>
</head>

<body>
    <header>
        <ul>
            <li><a href="index.php">Babel</a></li>
            <li><a href="about.php">A propos</a></li>
            <?php if (isset($_SESSION['utilisateur'])): ?>
                <?php if ($_SESSION['utilisateur']['pseudo'] === "admin"): ?>
                    <!-- Si l'utilisateur est admin, afficher le lien vers le tableau de bord admin -->
                    <li> <a href="dashboardAdmin.php">Compte admin</a></li>
                <?php else: ?>
                    <!-- Si l'utilisateur n'est pas admin, afficher son compte utilisateur -->
                    <li> <a href="libraryUser.php">Ma bibliothèque</a></li>
                    <li> <a href="dashboardUser.php">Mon compte</a></li>
                <?php endif; ?>
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
                <div>
                    <form action="../pages/verification/checkNewBook.php" method="POST">
                        <h2>Ajouter un livre</h2>

                        <div class="form-group">
                            <label for="title">Titre</label>
                            <input type="text" id="title" name="title" placeholder="Le Petit Prince" required>
                        </div>

                        <div class="form-group">
                            <label for="writer">Auteur</label>
                            <input type="text" id="writer" name="writer" placeholder="Antoine de Saint-Exupéry" required>
                        </div>

                        <div class="form-group">
                            <label for="editor">Maison d'édition </label>
                            <input type="text" id="editor" name="editor" placeholder="Gallimard" required>
                        </div>

                        <div class="form-group">
                            <label for="year">Année de publication </label>
                            <input type="number" id="year" name="year" min="1000" max="9999" placeholder="1943" required>
                        </div>

                        <div class="form-group">
                            <label for="isbn">ISBN</label>
                            <input type="text" id="isbn" name="isbn" placeholder="978-3-16-148410-0" required>
                            <div id="isbn-error" style="color: red; font-size: 12px; display: none;"></div> <!-- Message d'erreur -->
                        </div>


                        <button type="submit" name="submit" class="button-soumission">Soumettre l'ouvrage</button>
                    </form>
                </div>
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