<?php
session_start();

// Vérifiez si l'utilisateur est un administrateur
if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['pseudo'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Charger les livres en attente
$booksPendingFile = '../books/books_pending.json';
$booksPendingData = file_exists($booksPendingFile) ? json_decode(file_get_contents($booksPendingFile), true) : [];

// Charger les livres validés
$booksApprovedFile = '../books/books_approved.json';
$booksApprovedData = file_exists($booksApprovedFile) ? json_decode(file_get_contents($booksApprovedFile), true) : [];

// Charger les livres rejetés
$booksRejectedFile = '../books/books_rejected.json';
$booksRejectedData = file_exists($booksRejectedFile) ? json_decode(file_get_contents($booksRejectedFile), true) : [];
?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord administrateur</title>
    <link rel="stylesheet" href="../assets/css/dashboard-admin.css">
    <script src="../assets/js/checkisbn.js" defer></script>
    <script>
        function toggleSection(sectionId) {
            var section = document.getElementById(sectionId);
            if (section.style.display === "none" || section.style.display === "") {
                section.style.display = "block"; // Affiche la section
            } else {
                section.style.display = "none"; // Cache la section
            }
        }
    </script>
</head>

<body>
    <header>
        <ul>
            <li><a href="index.php">Babel</a></li>
            <li><a href="about.php">A propos</a></li>
            <?php if (isset($_SESSION['utilisateur'])): ?>
                <?php if ($_SESSION['utilisateur']['pseudo'] === "admin"): ?>
                    <li><a href="dashboardAdmin.php">Compte admin</a></li>
                <?php else: ?>
                    <li><a href="libraryUser.php">Ma bibliothèque</a></li>
                    <li><a href="dashboardUser.php">Mon compte</a></li>
                <?php endif; ?>
                <li id="deconnexion"><a href="deconnexion.php">Se déconnecter</a></li>
            <?php else: ?>
                <li id="connexion"><a href="connexion.html">Se connecter</a></li>
                <li id="nouveauCompte"><a href="inscription.html">Créer un compte</a></li>
            <?php endif; ?>
        </ul>
    </header>
    <main>
        <h1>Tableau de bord</h1>

        <!-- Section des livres en attente de validation (toujours visible) -->
        <div id="pendingBooks">
            <h2>Livres en attente de validation</h2>
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Auteur</th>
                        <th>Maison d'édition</th>
                        <th>Année de publication</th>
                        <th>ISBN</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($booksPendingData as $book): ?>
                        <tr>
                            <td><?= htmlspecialchars($book['title']); ?></td>
                            <td><?= htmlspecialchars($book['writer']); ?></td>
                            <td><?= htmlspecialchars($book['editor']); ?></td>
                            <td><?= htmlspecialchars($book['year']); ?></td>
                            <td><?= htmlspecialchars($book['isbn']); ?></td>
                            <td>
                                <form action="validateBook.php" method="POST">
                                    <input type="hidden" name="isbn" value="<?= htmlspecialchars($book['isbn']); ?>">
                                    <button type="submit" name="action" value="approve">Approuver</button>
                                    <button type="submit" name="action" value="reject">Rejeter</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Boutons pour afficher ou cacher les livres validés et rejetés -->
        <button onclick="toggleSection('approvedBooks')">Livres validés</button>
        <button onclick="toggleSection('rejectedBooks')">Livres rejetés</button>

        <!-- Section des livres validés (cachée par défaut) -->
        <div id="approvedBooks" style="display:none;">
            <h2>Livres validés</h2>
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Auteur</th>
                        <th>Maison d'édition</th>
                        <th>Année de publication</th>
                        <th>ISBN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($booksApprovedData as $book): ?>
                        <tr>
                            <td><?= htmlspecialchars($book['title']); ?></td>
                            <td><?= htmlspecialchars($book['writer']); ?></td>
                            <td><?= htmlspecialchars($book['editor']); ?></td>
                            <td><?= htmlspecialchars($book['year']); ?></td>
                            <td><?= htmlspecialchars($book['isbn']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Section des livres rejetés (cachée par défaut) -->
        <div id="rejectedBooks" style="display:none;">
            <h2>Livres rejetés</h2>
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Auteur</th>
                        <th>Maison d'édition</th>
                        <th>Année de publication</th>
                        <th>ISBN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($booksRejectedData as $book): ?>
                        <tr>
                            <td><?= htmlspecialchars($book['title']); ?></td>
                            <td><?= htmlspecialchars($book['writer']); ?></td>
                            <td><?= htmlspecialchars($book['editor']); ?></td>
                            <td><?= htmlspecialchars($book['year']); ?></td>
                            <td><?= htmlspecialchars($book['isbn']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
    <footer>
        <p>© 2024 Babel. Projet scolaire Bachelor Ingenierie des médias.</p>
    </footer>

</body>

</html>