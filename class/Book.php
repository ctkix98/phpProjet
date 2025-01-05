<?php

class Book {
    private $title;
    private $writer;
    private $theme;
    private $year;
    private $isbn;

    // Constructeur
    public function __construct($title, $writer, $theme, $year, $isbn) {
        $this->title = $title;
        $this->writer = $writer;
        $this->theme = $theme;
        $this->year = $year;
        $this->isbn = $isbn;
    }

    // Getter pour le titre
    public function getTitle() {
        return $this->title;
    }

    // Setter pour le titre
    public function setTitle($title) {
        $this->title = $title;
    }

    // Getter pour l'auteur
    public function getWriter() {
        return $this->writer;
    }

    // Setter pour l'auteur
    public function setWriter($writer) {
        $this->writer = $writer;
    }

    // Getter pour la maison d'édition
    public function getEditor() {
        return $this->theme;
    }

    // Setter pour la maison d'édition
    public function setEditor($editor) {
        $this->theme = $editor;
    }

    // Getter pour l'année de publication
    public function getYear() {
        return $this->year;
    }

    // Setter pour l'année de publication
    public function setYear($year) {
        $this->year = $year;
    }

    // Getter pour l'ISBN
    public function getIsbn() {
        return $this->isbn;
    }

    // Setter pour l'ISBN
    public function setIsbn($isbn) {
        $this->isbn = $isbn;
    }

    // Méthode pour afficher les informations du livre
    public function displayBookInfo() {
        return "Titre : " . $this->title . "<br>" .
               "Auteur : " . $this->writer . "<br>" .
               "Thème : " . $this->theme . "<br>" .
               "Année de publication : " . $this->year . "<br>" .
               "ISBN : " . $this->isbn;
    }

    // Méthode pour valider un livre (peut-être stocker dans un fichier ou base de données)
    public function approve() {
        // Implémentation pour approuver le livre (par exemple, l'ajouter dans un fichier "livres validés")
        return "Le livre '$this->title' a été approuvé.";
    }

    // Méthode pour rejeter un livre (peut-être stocker dans un fichier ou base de données)
    public function reject() {
        // Implémentation pour rejeter le livre (par exemple, l'ajouter dans un fichier "livres rejetés")
        return "Le livre '$this->title' a été rejeté.";
    }

    // Convertir un objet Book en tableau associatif pour le stockage (JSON, base de données, etc.)
    public function toArray() {
        return [
            'title' => $this->title,
            'writer' => $this->writer,
            'editor' => $this->theme,
            'year' => $this->year,
            'isbn' => $this->isbn,
        ];
    }

    // Créer un objet Book à partir d'un tableau associatif (par exemple, lors de la récupération des données)
    public static function fromArray($data) {
        return new self($data['title'], $data['writer'], $data['theme'], $data['year'], $data['isbn']);
    }
}

?>
