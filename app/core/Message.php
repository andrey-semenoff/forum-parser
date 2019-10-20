<?php
namespace Core;

class Message
{
    public $topic;
    public $author;
    public $date;
    public $text;

    public function __construct(string $topic, string $author, string $date, string $text)
    {
        $this->topic = $topic;
        $this->author = $author;
        $this->date = $date;
        $this->text = $text;
    }
}