<?php
namespace App;

use PHPHtmlParser\Dom;

class ForumoduaParser implements Parser
{
    public function parse(array $topics)
    {
        $parse_result = null;
    }

    private function fetchPage()
    {
        $html = null;

        return $html;
    }

    private function parsePage(): array {
        $messages = [];
//        $dom = new Dom;
//        $dom->load('<div class="all"><p>Hey bro, <a href="google.com">click here</a><br /> :)</p></div>');
//        $a = $dom->find('a')[0];
//        echo $a->text; // "click here"
        return $messages;
    }

    private function parseMessage(): Message {
        $topic = '';
        $author = '';
        $date = '';
        $text = '';
        return new Message($topic, $author, $date, $text);
    }

    private function send(array $message)
    {

    }
}