<?php

class Book
{
    public $title;
    public $author;
    public $editor;
    public $parution_date;
    public $isbn;
    public function __construct($title, $author, $editor, $parution_date, $isbn)
    {
        $this->title = $title;
        $this->author = $author;
        $this->editor = $editor;
        $this->parution_date = $parution_date;
        $this->isbn = $isbn;
    }
}
