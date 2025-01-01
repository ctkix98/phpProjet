<?php
// Connexion à la base de données et chargement des classes nécessaires
require_once('../../db/Database.php');
//session_start();

// Initialisation de la base de données
$db = new Database();
//if (!$db->initialistion()) {
//    $_SESSION['message'] = "Erreur lors de l'accès à la base de données.";
//    header('Location: ../messages/errorMessage.php', true, 303);
//    exit();
//}

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
        <div class="confirmation">
            <?php
            // Récupération du token depuis l'URL
            $token = filter_input(INPUT_GET, 'token', FILTER_DEFAULT);

            if ($token) {
                // Vérifier si une personne est associée au token
                $personne = $db->getUserByToken($token); // Méthode à implémenter dans ta classe `Database`
                if ($personne) {
                    // Confirmer l'inscription
                    if ($db->confirmeInscription($personne['id'])) { // Méthode à implémenter pour marquer l'inscription comme confirmée
                        echo "<h1 class='message-confirmation success'>Votre inscription a été confirmée avec succès !</h1>";
                    } else {
                        echo "<h1 class='message-confirmation error'>Une erreur est survenue lors de la confirmation. Veuillez réessayer plus tard.</h1>";
                    }
                } else {
                    echo "<h1 class='message-confirmation error'>Lien de confirmation invalide ou expiré.</h1>";
                }
            } else {
                echo "<h1 class='message-confirmation error'>Aucun token fourni pour la confirmation.</h1>";
            }
            ?>

            <!-- Bouton de retour à la page de connexion -->
            <div>
                <a href="../../pages/connexion.html" id="idConnexion">Retour à la page de connexion</a>
            </div>
        </div>
    </main>
</body>

</html>