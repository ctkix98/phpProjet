<?php
require_once('../../db/Database.php');
session_start();


$db = new Database();
if (!$db->initialistion()) {
  echo "Problème d'initialisation <br>";
}

$allBooks = $db->getAllBooks();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Bibliothèque</title>
</head>

<body>
  <header>
    <ul>
      <li><a href="../index.php">Babel</a></li>
      <li><a href="../about.php">A propos</a></li>
      <li> <a href="library.php">Bibliothèque</a></li>
      <?php if (isset($_SESSION['utilisateur'])): ?>
        <?php if ($_SESSION['utilisateur']['pseudo'] === "admin"): ?>
          <!-- Si l'utilisateur est admin, afficher le lien vers le tableau de bord admin -->
          <li> <a href="dashboardAdmin.php">Tableau de bord</a></li>
          <li> <a href="../adminPage.php">Compte admin</a></li>
        <?php else: ?>
          <!-- Si l'utilisateur n'est pas admin, afficher son compte utilisateur -->
          <li> <a href="../libraryUser.php">Mes lectures</a></li>
          <li> <a href="../dashboardUser.php">Mon compte</a></li>
        <?php endif; ?>
        <li id="deconnexion"><a href="../deconnexion.php">Se déconnecter</a></li>
      <?php else: ?>
        <li id="connexion"><a href="../connexion.html">Se connecter</a></li>
        <li id="nouveauCompte"><a href="../inscription.html">Créer un compte</a></li>
      <?php endif; ?>
    </ul>
  </header>
  <main>
    <div class="form-container">
      <form>
        <input type="text" id="search" />
        <input type="submit" value="Recherche" />
      </form>
    </div>
    <div class="books-container">
      <!-- Exemple de livres en cours -->
      <div class="book-item">
        <a href="bookinfo.php">
          <img
            src="../assets/images/placeholder-mylibrary.png"
            alt="book-cover" />
          <h3 class="book-title">Livre 1</h3>
        </a>
        <h4 class="author">Auteur 1</h4>
      </div>
    </div>
  </main>
</body>

</html>