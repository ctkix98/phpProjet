<?php

class Book
{
    public $title;
    public $author;
    public $theme;
    public $parution_date;
    public $isbn;

    // Constructeur
    public function __construct($title, $author, $theme, $parution_date, $isbn)
    {
        $this->title = $title;
        $this->author = $author;
        $this->theme = $theme;
        $this->parution_date = $parution_date;
        $this->isbn = $isbn;
    }

    // Getter pour le titre
    public function getTitle()
    {
        return $this->title;
    }

    // Setter pour le titre
    public function setTitle($title)
    {
        $this->title = $title;
    }

    // Getter pour l'auteur
    public function getWriter()
    {
        return $this->author;
    }

    // Setter pour l'auteur
    public function setWriter($author)
    {
        $this->author = $author;
    }

    // Getter pour la maison d'édition
    public function getEditor()
    {
        return $this->theme;
    }

    // Setter pour la maison d'édition
    public function setEditor($editor)
    {
        $this->theme = $editor;
    }

    // Getter pour l'année de publication
    public function getYear()
    {
        return $this->parution_date;
    }

    // Setter pour l'année de publication
    public function setYear($parution_date)
    {
        $this->parution_date = $parution_date;
    }

    // Getter pour l'ISBN
    public function getIsbn()
    {
        return $this->isbn;
    }

    // Setter pour l'ISBN
    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;
    }

    // Méthode pour afficher les informations du livre
    public function displayBookInfo()
    {
        return "Titre : " . $this->title . "<br>" .
            "Auteur : " . $this->author . "<br>" .
            "Thème : " . $this->theme . "<br>" .
            "Année de publication : " . $this->parution_date . "<br>" .
            "ISBN : " . $this->isbn;
    }

    // Méthode pour valider un livre (peut-être stocker dans un fichier ou base de données)
    public function approve()
    {
        // Implémentation pour approuver le livre (par exemple, l'ajouter dans un fichier "livres validés")
        return "Le livre '$this->title' a été approuvé.";
    }

    // Méthode pour rejeter un livre (peut-être stocker dans un fichier ou base de données)
    public function reject()
    {
        // Implémentation pour rejeter le livre (par exemple, l'ajouter dans un fichier "livres rejetés")
        return "Le livre '$this->title' a été rejeté.";
    }

    // Convertir un objet Book en tableau associatif pour le stockage (JSON, base de données, etc.)
    public function toArray()
    {
        return [
            'title' => $this->title,
            'author' => $this->author,
            'editor' => $this->theme,
            'parution_date' => $this->parution_date,
            'isbn' => $this->isbn,
        ];
    }

    // Créer un objet Book à partir d'un tableau associatif (par exemple, lors de la récupération des données)
    public static function fromArray($data)
    {
        return new self($data['title'], $data['author'], $data['theme'], $data['parution_date'], $data['isbn']);
    }
}
