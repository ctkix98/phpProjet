<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book informations</title>
    
    <link rel="stylesheet" href="../assets/css/bookinfo.css">
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
        <section class="book-container">
            <!-- Colonne gauche : Image -->
            <div class="book-image-container">
                <img src="../assets/images/ldli_cromalin2-scaled.jpg" alt="La librairie des livres interdits">
            </div>
            <div class="book-container">
                <!-- book related -->
                <h1 class="title"><strong>La librairie des livres interdits</strong></h1>
                <h2 class="author">Marc Levy</h2>
                <p class="total-rating"><strong>Note :</strong> ★★★★☆ (avis: 174)</p>
                <p class="editor"><strong>Editeur :</strong> Robert Laffont </p>
                <p class="parution-date"><strong>Parution :</strong>19 novembre 2024</p>
                <h4 class="isbn"><strong>ISBN :</strong>9782221243619</h4>
                
                <!-- book state -->
                <div>
                    <h2>Statut du livre</h2>
                    <select id="state-list">
                        <option value="read-want">À lire</option>
                        <option value="reading">En cours</option>
                        <option value="read-done">Terminé</option>
                        <option value="read-dropped">Abandonné</option>
                    </select>
                </div>
                
            </div>
        </section>
            <!-- comment and rating -->
        <section class="comment-section">
            <h3>Donne ton avis sur ce livre</h3>
            <form action="" method="post">
                <label for="form-rating">Avis</label>
                <div class="rating">
                    <input type="radio" name="rating" value="5" id="star5"><label for="star5">★</label>
                    <input type="radio" name="rating" value="4" id="star4"><label for="star4">★</label>
                    <input type="radio" name="rating" value="3" id="star3"><label for="star3">★</label>
                    <input type="radio" name="rating" value="2" id="star2"><label for="star2">★</label>
                    <input type="radio" name="rating" value="1" id="star1"><label for="star1">★</label>
                </div>
                <label for="comment">Commentaire</label>
                <textarea name="comment" rows="5" placeholder="Laisse un commentaire ici..."></textarea>
                <input type="submit" value="Envoyer">
            </form>
            <h3>Avis des autres lecteurs</h3>
            <div class="comment-container">
                <h3 class="username">Leila01</h3>
                <div class="score">
                    <p class="user-rating">★★★★☆</p>
                </div>
                <p class="comment-text">Ce livre est vraiment agréable à lire. Marc Levy reste pour moi une valeur sure.</p>
            </div>
        </section>

    </main>
    
</body>

</html>