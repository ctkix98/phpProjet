<?php
session_start();
require_once __DIR__ . '/../../db/Database.php';
require_once __DIR__ . '/updateBookState.php';


$userId = $_SESSION['utilisateur']['id'];
$bookId = $_GET['id'] ?? null;

// Récupérer l'état actuel du livre
$db = new Database();
$currentState = isset($_SESSION['book_states'][$bookId])
    ? $_SESSION['book_states'][$bookId]
    : getCurrentBookState($bookId, $userId, $db);

// Vérifiez si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sauvegardez l'état sélectionné dans une variable de session
    if (isset($_POST['book_state'])) {
        $_SESSION['book_states'][$bookId] = $_POST['book_state'];

        // Mettez à jour l'état du livre dans la base de données
        $bookState = $_POST['book_state'];
        $sql = "UPDATE lecture SET book_state_id = :book_state_id WHERE user_id = :user_id AND book_id = :book_id";
        $stmt = $db->getDb()->prepare($sql);
        $stmt->bindParam(':book_state_id', $bookState, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':book_id', $bookId, PDO::PARAM_INT);
        $stmt->execute();

        // Mettre à jour l'état actuel
        $currentState = $bookState;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book informations</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/bookinfo.css">
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
                    <li> <a href="adminPage.php">Compte admin</a></li>
                <?php else: ?>
                    <!-- Si l'utilisateur n'est pas admin, afficher son compte utilisateur -->
                    <li> <a href="libraryUser.php">Mes lectures</a></li>
                    <li> <a href="dashboardUser.php">Mon compte</a></li>
                <?php endif; ?>
                <li id="deconnexion"><a href="../deconnexion.php">Se déconnecter</a></li>
            <?php else: ?>
                <li id="connexion"><a href="../connexion.html">Se connecter</a></li>
                <li id="nouveauCompte"><a href="../inscription.html">Créer un compte</a></li>
            <?php endif; ?>
        </ul>
    </header>
    <main>
        <?php if (isset($_SESSION['utilisateur'])): ?>
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
                    <p class="theme"><strong>Thème :</strong> romance </p>
                    <p class="parution-date"><strong>Parution :</strong>19 novembre 2024</p>
                    <h4 class="isbn"><strong>ISBN :</strong>9782221243619</h4>

                    <!-- book state -->
                    <div>
                        <h2>Statut du livre</h2>
                        <form action="updateBookState.php" method="POST">
                            <select id="state-list" name="book_state">
                                <option value="not-in-library" <?php echo $currentState === 'not-in-library' ? 'selected' : ''; ?>>
                                    Pas encore dans votre librairie
                                </option>
                                <option value="read-want" <?php echo $currentState === 'read-want' ? 'selected' : ''; ?>>
                                    À lire
                                </option>
                                <option value="reading" <?php echo $currentState === 'reading' ? 'selected' : ''; ?>>
                                    En cours
                                </option>
                                <option value="read-done" <?php echo $currentState === 'read-done' ? 'selected' : ''; ?>>
                                    Terminé
                                </option>
                                <option value="read-dropped" <?php echo $currentState === 'read-dropped' ? 'selected' : ''; ?>>
                                    Abandonné
                                </option>
                            </select>
                            <input type="hidden" name="book_id" value="<?php echo $bookId; ?>">
                            <input type="submit" value="Valider">
                        </form>
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
        <?php else: ?>
            <?php
            $message = "Il faut être connecté pour accéder à cette page.<br>" . "<a href='../connexion.html'>Se connecter</a>";
            $_SESSION['message'] = $message;
            header('Location: ../messages/message.php', true, 303);
            ?>
        <?php endif; ?>
    </main>


</body>

</html>