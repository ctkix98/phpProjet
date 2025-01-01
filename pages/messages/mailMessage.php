<?php
// Connexion à la base de données et chargement des classes nécessaires
require_once __DIR__ . '/../../config/session.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/index.css">
    <title>Confirmation du mail</title>
</head>

<body>
    <main class="pages">
        <?php if (isset($_SESSION['message']) && $_SESSION['message'] === "ok"): ?>
            <div class="confirmation">
                <h1>Ton inscription a été confirmée avec succès !</h1>
                <div>
                    <a href="../../pages/connexion.html" id="idConnexion">Retour à la page de connexion</a>
                </div>
            </div>
        <?php else: ?>
            <h1><?php echo htmlspecialchars($_SESSION['message']); ?> !</h1>
        <?php endif; ?>
    </main>
</body>

</html>
