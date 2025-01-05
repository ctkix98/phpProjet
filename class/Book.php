<?php

class Book
{
    public $title;
    public $author;
    public $theme;
    public $parution_date;
    public $isbn;
    public ?string $cover_image_path;
    public ?string $olid;

    // Constructeur
    public function __construct($title, $author, $theme, $parution_date, $isbn, ?string $cover_image_path = null, ?string $olid = null)
    {
        $this->title = $title;
        $this->author = $author;
        $this->theme = $theme;
        $this->parution_date = $parution_date;
        $this->isbn = $isbn;
        $this->cover_image_path = $cover_image_path;
        $this->olid = $olid;
        
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

    // Getter pour le chemin de l'image de couverture
    public function getCoverImagePath()
    {
        return $this->cover_image_path;
    }

    // Setter pour le chemin de l'image de couverture
    public function setCoverImagePath($cover_image_path)
    {
        $this->cover_image_path = $cover_image_path;
    }

    // Getter pour l'OLID
    public function getOlid()
    {
        return $this->olid;
    }

    // Setter pour l'OLID
    public function setOlid($olid)
    {
        $this->olid = $olid;
    }

    // Méthode pour afficher les informations du livre
    public function displayBookInfo()
    {
        return "Titre : " . $this->title . "<br>" .
            "Auteur : " . $this->author . "<br>" .
            "Thème : " . $this->theme . "<br>" .
            "Année de publication : " . $this->parution_date . "<br>" .
            "ISBN : " . $this->isbn . "<br>" .
            "Chemin de l'image de couverture : " . $this->cover_image_path . "<br>" .
            "OLID : " . $this->olid;
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
            'cover_image_path' => $this->cover_image_path,
            'olid' => $this->olid,
        ];
    }

    // Créer un objet Book à partir d'un tableau associatif (par exemple, lors de la récupération des données)
    public static function fromArray($data)
    {
        return new self(
            $data['title'], 
            $data['author'], 
            $data['theme'], 
            $data['parution_date'], 
            $data['isbn'],
            isset($data['cover_image_path']) ? $data['cover_image_path'] : null, 
            isset($data['olid']) ? $data['olid'] : null
        );
    }
}
