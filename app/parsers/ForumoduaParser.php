<?php
namespace App;

use PHPHtmlParser\Dom;

class ForumoduaParser implements Parser
{
    public function parse(array $topics)
    {
        foreach ($topics as $topic) {
            $pages_urls = $this->getPagesUrls($topic);
            $this->parsePages($pages_urls);
        }
    }

    private function fetchPage($page_url)
    {
        $ch = curl_init($page_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36",
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    private function parsePages($pages_urls)
    {
        if( count($pages_urls) === 0 ) {
            die('Nothing to parse!');
        }

        foreach ($pages_urls as $page_url) {
            $source = $this->fetchPage($page_url);
            $source = mb_convert_encoding($source, 'UTF-8', 'windows-1251');
            $parsed_html = $this->parsePage($source);
            $this->parseMessages($parsed_html);
        }
    }

    private function parsePage($source): array
    {
        $dom = new Dom;
        try {
            $dom->setOptions([
                'enforceEncoding' => true,
            ]);
            $dom->load($source);

            $topic = $dom->find('.threadtitle > a')[0]->text;
            $messages = $dom->find('#posts > li');

        } catch (\Exception $e) {
            die("Cannot parse page");
        }

        return [
            'topic' => $topic,
            'messages' => $messages
        ];
    }

    private function parseMessages($parsed_html)
    {
        if( count($parsed_html['messages']) === 0 ) {
            die('Page have no messages!');
        }

        foreach ($parsed_html['messages'] as $message_html) {
            $message = $this->parseMessage($message_html);
            if( !empty($message) ) {
                // TODO: add Producer & Cunsumer interfaces, create classes
                // send Messages via RabbitMQ
                var_dump(new Message($parsed_html['topic'], $message['author'], $message['date'], $message['text']));
            }
        }
    }

    private function parseMessage($message_html): array
    {
        if( is_null($message_html->getAttribute('id')) ) {
            return [];
        }

        return [
            'author' => $message_html->find('.username strong')->text,
            'date' => str_replace('&nbsp;', '', $message_html->find('.posthead .date')->text),
            'text' => trim($message_html->find('.postcontent')->text),
        ];
    }

    private function getPagesUrls($topic)
    {
        $pages_urls = [];
        $topic['pages'] = $this->normalizePagesNums($topic['pages']);

        for( $i = $topic['pages'][0]; $i <= $topic['pages'][1]; $i++ ) {
            $pages_urls[] = $topic['url'] . "&page={$i}";
        }

        return $pages_urls;
    }

    private function normalizePagesNums($pages_nums)
    {
        if( is_array($pages_nums) && count($pages_nums) > 0 ) {
            if( count($pages_nums) === 1 ) {
                $pages_nums[] = $pages_nums[0];
            }
        }

        return $pages_nums;
    }
}