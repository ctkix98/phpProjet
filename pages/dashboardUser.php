<?php
//session_start();
require_once __DIR__ . '/../config/session.php';

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil</title>
    <link rel="stylesheet" href="../assets/css/account.css">
    <script src="../assets/js/showPassword.js" defer></script>
    <script src="../assets/js/checkpassword.js" defer></script>


</head>

<body>
    <header>
        <ul>
            <li><a href="index.php">Babel</a></li>
            <li><a href="about.php">A propos</a></li>
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
        <div>
            <?php if (isset($_SESSION['utilisateur'])): ?>
                <div>
                    <h1>Bonjour <?php echo htmlspecialchars($pseudo); ?> !</h1>
                    <div id='donneesUtilisateur'>
                        <h2>Voici tes données </h2>
                        <p>Ton pseudo : <?php echo $pseudo; ?></p>
                        <p>Ton email : <?php echo $email; ?></p>
                    </div>

                    <h2>Que veux-tu faire ?</h2>
                </div>
                <div class="content">
                    <ul class="button-form">
                        <li><a href="#" onclick="toggleForm('formChangerMotDePasse')">Changer le mot de passe</a></li>
                        <li><a href="#" onclick="toggleForm('formSupprimerCompte')">Supprimer le compte</a></li>
                    </ul>
                    <!-- Formulaire pour changer le mot de passe -->
                    <form id="formChangerMotDePasse" method="POST" action="../pages/verification/updateAccount.php" style="display: none;">
                        <h2>Changer le mot de passe</h2>
                        <input type="hidden" name="action" value="changerMotDePasse">
                        <label>Ancien mot de passe :</label>
                        <input type="password" name="ancienMotDePasse" required><br>
                        <label>Nouveau mot de passe :</label>
                        <input type="password" name="nouveauMotDePasse" required><br>
                        <label>Confirmer le nouveau mot de passe :</label>
                        <input type="password" name="confirmerMotDePasse" required><br>
                        <p id="erreurMotDePasse" style="color: red; display: none;"></p>
                        <button type="submit" name="submit" class="button-soumission">Changer le mot de passe</button>
                    </form>
                    <!-- Formulaire pour supprimer le compte -->
                    <form id="formSupprimerCompte" method="POST" action="../pages/verification/updateAccount.php" style="display: none;">
                        <h2>Supprimer le compte</h2>
                        <input type="hidden" name="action" value="supprimerCompte">
                        <p>Es-tu sûr de vouloir supprimer ton compte ? Cette action est irréversible.</p>
                        <button type="submit" name="submit" class="button-soumission">Oui, supprimer mon compte</button>
                    </form>
                </div>
            <?php else: ?>
                <h1>Il semblerait que tu ne sois pas connecté :/</h1>
            <?php endif; ?>
        </div>
    </main>
    <footer>
        <p>© 2024 Babel. Projet scolaire Bachelor Ingenierie des médias.</p>
    </footer>
</body>

</html>