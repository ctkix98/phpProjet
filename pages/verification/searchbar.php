<?php
session_start();
require_once('../../db/Database.php');

$db = new Database();
if ($db->initialisation()) {
    echo "Initialisation réussie :-) <br>";
}


