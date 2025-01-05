<?php
require_once('/MAMP/htdocs/phpProjet/db/Database.php');
session_start();

// Vérifiez si l'utilisateur est un administrateur
if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['pseudo'] !== 'admin') {
    header('Location: index.php');
    exit();
}

$db = new Database();
if (!$db->initialistion()) {
    $_SESSION['message'] = "Erreur lors de l'accès à la base de données.";
    header('Location: ../messages/message.php', true, 303);
    exit();
}

// Récupérer les livres en attente
$sqlPending = "SELECT * FROM book_validation WHERE validation_status = 'pending'";
$stmtPending = $db->getDb()->prepare($sqlPending);
$stmtPending->execute();
$booksPending = $stmtPending->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les livres validés
$sqlApproved = "SELECT * FROM book_validation WHERE validation_status = 'approved'";
$stmtApproved = $db->getDb()->prepare($sqlApproved);
$stmtApproved->execute();
$booksApproved = $stmtApproved->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les livres rejetés
$sqlRejected = "SELECT * FROM book_validation WHERE validation_status = 'rejected'";
$stmtRejected = $db->getDb()->prepare($sqlRejected);
$stmtRejected->execute();
$booksRejected = $stmtRejected->fetchAll(PDO::FETCH_ASSOC);

//Get all books registered in database
$allBooks = $db->getAllBooks();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord administrateur</title>
    <link rel="stylesheet" href="../../assets/css/dashboard-admin.css">
    <script>
        // Fonction pour afficher une section spécifique
        function showSection(sectionId) {
            const sections = document.querySelectorAll('main > div');
            sections.forEach(section => section.style.display = 'none');
            document.getElementById(sectionId).style.display = 'block';
        }

        // Initialiser avec les livres en attente affichés
        window.onload = function() {
            showSection('pendingBooks');
        };
    </script>
</head>

<body>
    <header>
        <ul>
            <li><a href="../index.php">Babel</a></li>
            <li><a href="../about.php">À propos</a></li>
            <?php if (isset($_SESSION['utilisateur'])): ?>
                <?php if ($_SESSION['utilisateur']['pseudo'] === "admin"): ?>
                    <li><a href="dashboardAdmin.php">Tableau de bord</a></li>
                    <li> <a href="../adminPage.php">Compte admin</a></li>
                <?php else: ?>
                    <li><a href="../libraryUser.php">Ma bibliothèque</a></li>
                    <li><a href="../dashboardUser.php">Mon compte</a></li>
                <?php endif; ?>
                <li id="deconnexion"><a href="../deconnexion.php">Se déconnecter</a></li>
            <?php else: ?>
                <li id="connexion"><a href="connexion.html">Se connecter</a></li>
                <li id="nouveauCompte"><a href="inscription.html">Créer un compte</a></li>
            <?php endif; ?>
        </ul>
    </header>

    <main>
        <h1>Tableau de bord</h1>

        <!-- Boutons de navigation -->
        <nav>
            <button onclick="showSection('pendingBooks')">Livres en attente</button>
            <button onclick="showSection('approvedBooks')">Livres validés</button>
            <button onclick="showSection('rejectedBooks')">Livres rejetés</button>
            <button onclick="showSection('allBooks')">Tous les livres enregistrés</button>
        </nav>

        <!-- Section des livres en attente -->
        <div id="pendingBooks">
            <h2>Livres en attente de validation</h2>
            <?php if (empty($booksPending)): ?>
                <p>Aucun livre en attente de validation.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titre</th>
                            <th>Auteur</th>
                            <th>Thème</th>
                            <th>Année de publication</th>
                            <th>ISBN</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($booksPending as $book): ?>
                            <tr>
                                <td><?= htmlspecialchars($book['id']); ?></td>
                                <td><?= htmlspecialchars($book['Title']); ?></td>
                                <td><?= htmlspecialchars($book['Author']); ?></td>
                                <td><?= htmlspecialchars($book['Theme']); ?></td>
                                <td><?= htmlspecialchars($book['Parution_date']); ?></td>
                                <td><?= htmlspecialchars($book['ISBN']); ?></td>
                                <td>
                                    <form action="validateBook.php" method="POST">
                                        <input type="hidden" name="book_id" value="<?= htmlspecialchars($book['id']); ?>">
                                        <button type="submit" name="action" value="approve">Approuver</button>
                                        <button type="submit" name="action" value="update">Modifier</button>
                                        <button type="submit" name="action" value="reject">Rejeter</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <!-- Section des livres validés -->
        <div id="approvedBooks" style="display:none;">
            <h2>Livres validés</h2>
            <?php if (empty($booksApproved)): ?>
                <p>Aucun livre validé à afficher.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Auteur</th>
                            <th>Thème</th>
                            <th>Année de publication</th>
                            <th>ISBN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($booksApproved as $book): ?>
                            <tr>
                                <td><?= htmlspecialchars($book['Title']); ?></td>
                                <td><?= htmlspecialchars($book['Author']); ?></td>
                                <td><?= htmlspecialchars($book['Theme']); ?></td>
                                <td><?= htmlspecialchars($book['Parution_date']); ?></td>
                                <td><?= htmlspecialchars($book['ISBN']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <!-- Section des livres rejetés -->
        <div id="rejectedBooks" style="display:none;">
            <h2>Livres rejetés</h2>
            <?php if (empty($booksRejected)): ?>
                <p>Aucun livre rejeté à afficher.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Auteur</th>
                            <th>Thème</th>
                            <th>Année de publication</th>
                            <th>ISBN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($booksRejected as $book): ?>
                            <tr>
                                <td><?= htmlspecialchars($book['Title']); ?></td>
                                <td><?= htmlspecialchars($book['Author']); ?></td>
                                <td><?= htmlspecialchars($book['Theme']); ?></td>
                                <td><?= htmlspecialchars($book['Parution_date']); ?></td>
                                <td><?= htmlspecialchars($book['ISBN']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        <!-- Section de tous les livres de la base de données -->
        <div id="allBooks" style="display:none;">
            <h2>Tous les livres de la base de données</h2>
            <?php if (empty($booksRejected)): ?>
                <p>Aucun livre rejeté à afficher.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Auteur</th>
                            <th>Thème</th>
                            <th>Année de publication</th>
                            <th>ISBN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allBooks as $book): ?>
                            <tr>
                                <td><?= htmlspecialchars($book['Title']); ?></td>
                                <td><?= htmlspecialchars($book['Author']); ?></td>
                                <td><?= htmlspecialchars($book['Theme']); ?></td>
                                <td><?= htmlspecialchars($book['Parution_date']); ?></td>
                                <td><?= htmlspecialchars($book['ISBN']); ?></td>
                                <td>
                                    <form action="deleteBook.php" method="POST">
                                        <input type="hidden" name="book_id" value="<?= htmlspecialchars($book['id']); ?>">
                                        <button type="submit" name="action" value="delete">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>© 2024 Babel. Projet scolaire Bachelor Ingenierie des médias.</p>
    </footer>
</body>

</html>