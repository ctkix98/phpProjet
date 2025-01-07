<?php

// Assure-toi d'avoir inclus la classe Book et la classe Database pour l'accès à la base de données
require_once __DIR__ . '/../../db/Database.php';
require_once __DIR__ . '/Book.php';

// Créer une instance de la base de données
$db = new Database();

// Tableau des livres à insérer
$books = [
    new Book("Daphné et le duc", "Anthony Julia Quinn", "Romance", "2021", "9782290254738", "/assets/images/covers/daphne_et_duc.jpeg"),
    new Book("Le cycle de Dune, Tome 1", "Frank Herbert", "Fantasy", "2021", "9782266320542", "/assets/images/covers/cycle_de_dune_tome1.jpeg"),
    new Book("Fascination", "Stephenie Meyer", "Romance", "2011", "9782013212113", "/assets/images/covers/fascination.jpeg"),
    new Book("L'Alchimiste", "Paulo Coelho", "Romance", "2021", "9782290258064", "/assets/images/covers/alchimiste.jpeg"),
    new Book("La panthère des neiges", "Sylvain Tesson", "Fantasy", "2021", "9782072936494", "/assets/images/covers/panthere_des_neiges.jpeg"),
    new Book("Les fiancés de l'hiver", "Christelle Dabos", "Fantasy", "2024", "9782075215466", "/assets/images/covers/fiances_de_lhiver.jpeg"),
    new Book("La vie secrète des écrivains", "Guillaume Musso", "Film", "2020", "9782253237631", "/assets/images/covers/vie_secrete_ecrivains.jpeg"),
    new Book("Les secrets de la femme de ménage", "Freida McFadden", "Film", "2024", "9782290391198", "/assets/images/covers/secret_femme_menage.jpeg"),
    new Book("Le bal des folles", "Victoria Mas", "Film", "2021", "9782253103622", "/assets/images/covers/bal_des_folles.jpeg"),
    new Book("Le silence du rossignol", "Lian Hearn", "Fantasy", "2021", "9782072934902", "/assets/images/covers/silence_rossignol.jpeg"),
    new Book("Le consentement", "Vanessa Springorg", "Science", "2021", "9782253101567", "/assets/images/covers/consentement.jpeg"),
    new Book("Le mage du Kremlin", "Guiliano da Empoli", "Horror", "2024", "9782073003911", "/assets/images/covers/mage_du_kremlin.jpeg"),
    new Book("Le Petit Prince", "Antoine de Saint-Exupéry", "Fantasy", "1999", "9782070408504", "/assets/images/covers/petit_prince.jpeg"),
    new Book("Le messie de Dune", "Frank Herbert", "Fantasy", "2021", "9782221255728", "/assets/images/covers/messie_dune.jpeg"),
    new Book("Les figurants", "Delphine de Vigan", "Science", "2024", "9782073083999", "/assets/images/covers/figurants.jpg"),
];

// Utiliser la méthode addBook() pour insérer chaque livre
foreach ($books as $book) {
    $db->addBook($book);
}
