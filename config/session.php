<?php
session_start();
$isConnected = false;
if (isset($_SESSION['utilisateur'])) {
    $id = $_SESSION['utilisateur']['id'];
    $pseudo = $_SESSION['utilisateur']['pseudo'];
    $email = $_SESSION['utilisateur']['email'];
    $isConnected = true;   
}

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    //echo $_SESSION['message'];
}