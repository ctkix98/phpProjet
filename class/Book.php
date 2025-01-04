<?php

class Book
{
    public $title;
    public $author;
    public $theme;
    public $parution_date;
    public $isbn;
    public function __construct($title, $author, $theme, $parution_date, $isbn)
    {
        $this->title = $title;
        $this->author = $author;
        $this->theme = $theme;
        $this->parution_date = $parution_date;
        $this->isbn = $isbn;
    }
    public function getTitle()
    {
        return $this->title;
    }
    public function getAuthor()
    {
        return $this->author;
    }
    public function getEditor()
    {
        return $this->theme;
    }
    public function getParutionDate()
    {
        return $this->parution_date;
    }
    public function getISBN()
    {
        return $this->isbn;
    }
}
