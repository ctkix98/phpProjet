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
 
// Récupérer les informations du livre
 
var_dump($_GET);
$book = $db->getBooksById($bookId);
 
$bookId = $_GET['id'] ?? null;
 
if ($bookId) {
    $db = new Database();
    $book = $db->getBooksById($bookId);
    var_dump($book);
 
    if ($book) {
        $coverPath = !empty($book['cover_image_path'])
            ? '../../' . htmlspecialchars($book['cover_image_path'])
            : '../../assets/images/covers/placeholder-mylibrary.jpg';
    } else {
        echo "Livre non trouvé.";
        exit();
    }
} else {
    echo "ID du livre manquant.";
    exit();
}
 
// Traitement des commentaires et avis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $comment = trim($_POST['comment']); // Nettoyer le commentaire
    $rating = (int)($_POST['rating'] ?? 0); // Convertir la note en entier
 
    // Vérifications des données
    if (empty($comment)) {
        echo "<p style='color: red;'>Le commentaire ne peut pas être vide.</p>";
    } else {
        try {
            // Insertion du commentaire dans la table `comment`
            $sqlComment = "INSERT INTO comment (user_id, book_id, content, date) VALUES (:user_id, :book_id, :content, NOW())";
            $stmtComment = $db->getDb()->prepare($sqlComment);
            $stmtComment->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmtComment->bindParam(':book_id', $bookId, PDO::PARAM_INT);
            $stmtComment->bindParam(':content', $comment, PDO::PARAM_STR);
            $stmtComment->execute();
 
            // Insertion de la note dans la table `grade`
            $sqlRating = "INSERT INTO grade (user_id, book_id, grade) VALUES (:user_id, :book_id, :grade)";
            $stmtRating = $db->getDb()->prepare($sqlRating);
            $stmtRating->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmtRating->bindParam(':book_id', $bookId, PDO::PARAM_INT);
            $stmtRating->bindParam(':grade', $rating, PDO::PARAM_INT);
            $stmtRating->execute();
 
            // Redirection pour éviter la resoumission du formulaire
            header("Location: bookinfo.php?id=$bookId");
            exit;
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Une erreur s'est produite lors de l'enregistrement de votre avis : " . htmlspecialchars($e->getMessage()) . "</p>";
        }
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
            <li><a href="../verification/homepage.php">Babel</a></li>
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
                    <img src="<?php echo $coverPath; ?>" alt="Couverture du livre">
                </div>
                <div class="book-container">
                    <!-- book related -->
                    <h1 class="title"><strong><?php echo htmlspecialchars($book['Title']); ?></strong></h1>
                    <h2 class="author"><?php echo htmlspecialchars($book['Author']); ?></h2>
                    <p class="total-rating"><strong>Note :</strong> ★★★★☆ (avis: 174)</p>
                    <p class="theme"><strong>Thème :</strong> <?php echo htmlspecialchars($book['Theme']); ?></p>
                    <p class="parution-date"><strong>Parution :</strong> <?php echo htmlspecialchars($book['Parution_date']); ?></p>
                    <h4 class="isbn"><strong>ISBN :</strong> <?php echo htmlspecialchars($book['ISBN']); ?></h4>
 
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
                            <input type="submit" name="submit" value="Valider">
                        </form>
                    </div>
                </div>
            </section>
            <!-- comment and rating -->
            <section class="comment-section">
                <h3>Donne ton avis sur ce livre</h3>
                <form action="bookinfo.php?id=<?php echo $bookId; ?>" method="post">
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
                    <input type="submit" name="submit_review" value="Envoyer">
                </form>
                <h3>Avis des autres lecteurs</h3>
                <div class="comment-container">
                    <h3 class="username">Leila01</h3>
                    <div class="score">
                        <p class="user-rating">★★★★☆</p>
                    </div>
                    <p class="comment-text">Marc Levy, c'est vraiment top</p>
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