<?php
session_start();
$isConnected = false;
if (isset($_SESSION['utilisateur'])) {
    $prenom = $_SESSION['utilisateur']['prenom'];
    $nom = $_SESSION['utilisateur']['nom'];
    $email = $_SESSION['utilisateur']['email'];
    $tel = $_SESSION['utilisateur']['noTel'];
    $isConnected = true;
    
} else {
    //echo "Pas connecté";
}

if (!isset($_SESSION['message'])) {
    echo "Pas connecté";
} else {
    $messageErreur = $_SESSION['message'];
}