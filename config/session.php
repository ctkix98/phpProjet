<?php
session_start();
$isConnected = false;
if (isset($_SESSION['utilisateur'])) {
    //$idUser = $_SESSION['utilisateur']['id'];
    $pseudo = $_SESSION['utilisateur']['pseudo'];
    $email = $_SESSION['utilisateur']['email'];
    $isConnected = true;   
}else{
    echo "Problème ici";
}

if (isset($_SESSION['message'])) {
    echo $_SESSION['message'];
}