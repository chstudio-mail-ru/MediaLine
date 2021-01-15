<?php

namespace app\services;

use app\services\dto\RBCDto;

class RBCService
{
    private $rssUrl = "http://static.feed.rbc.ru/rbc/logical/footer/news.rss";
    private $bodyNewsFirstTag = '<div class="article__text article__text_free" itemprop="articleBody">';
    private $bodyNewsLastTag = '<div class="article__clear"></div>';

    private function loadNewsImages(RBCDto $newsRecordDTO)
    {

    }

    private function getNewsText(RBCDto $newsRecordDTO): ?string
    {
        $opts = [
            'http' => [
                'method'=>"GET",
                'header'=> "User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36\r\n"
            ],
        ];
        $context = stream_context_create($opts);
        $document = file_get_contents($newsRecordDTO->link, false, $context);
        $newsText = explode($this->bodyNewsFirstTag, $document);
        if (isset($newsText[1])) {
            $newsText = explode($this->bodyNewsLastTag, $newsText[1]);
        } else {
            return null;
        }

        return $newsText[0];
    }

    public function importNews($numNews = 15): array
    {
        $opts = [
            'http' => [
                'method'=>"GET",
                'header'=> "User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36\r\n"
            ],
        ];
        $context = stream_context_create($opts);
        $xmlString = file_get_contents("http://static.feed.rbc.ru/rbc/logical/footer/news.rss", false, $context);
        $xmlObject = simplexml_load_string($xmlString,'SimpleXMLElement', LIBXML_NOCDATA);
        $i = 0;
        $objects = [];
        foreach ($xmlObject->channel->item as $item) {
            if ($i <= $numNews) {
                $newsDTO = new RBCDto();
                $newsDTO->title = (string) $item->title;
                $newsDTO->link = (string) $item->link;
                $newsDTO->description = (string) $item->description;
                $newsDTO->author = (string) $item->author;
                $newsDTO->guid = (string) $item->guid;
                $newsDTO->pubDate = (string) $item->pubDate;
                if (count($item->enclosure) > 1) {
                    foreach ($item->enclosure as $enclosureItem) {
                        $newsDTO->enclosureUrl[] = (string) $enclosureItem->attributes()->url;
                        $newsDTO->enclosureType[] = (string) $enclosureItem->attributes()->type;
                        $newsDTO->enclosureLength[] = (string) $enclosureItem->attributes()->length;
                    }
                } else {
                    $newsDTO->enclosureUrl[] = (string) $item->enclosure->attributes()->url;
                    $newsDTO->enclosureType[] = (string) $item->enclosure->attributes()->type;
                    $newsDTO->enclosureLength[] = (string) $item->enclosure->attributes()->length;
                }
                $objects[] = $newsDTO;
                $i++;
            } else {
                break;
            }
        }

        return $objects;
    }
}