<?php
session_start(); // Démarre la session
session_unset(); // Efface toutes les variables de session
session_destroy(); // Détruit la session
header('Location: index.php'); // Redirige vers la page d'accueil
exit();
