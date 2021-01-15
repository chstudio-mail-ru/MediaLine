<?php

namespace app\services;

use app\services\dto\RBCDto;
use GuzzleHttp\Client;

class RBCService
{
    private $rssUrl = "static.feed.rbc.ru/rbc/logical/footer/news.rss";
    private $bodyNewsClass = "article__text article__text_free";

    public function importNews()
    {
        $result = true;

        return $result;
    }
}