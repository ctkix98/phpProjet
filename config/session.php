<?php
session_start();
$isConnected = false;
if (isset($_SESSION['utilisateur'])) {
    $pseudo = $_SESSION['utilisateur']['pseudo'];
    $email = $_SESSION['utilisateur']['email'];
    $isConnected = true;
    
} else {
    //echo "Pas connecté";
}

if (!isset($_SESSION['message'])) {
    echo "Pas connecté";
} else {
    $messageErreur = $_SESSION['message'];
}