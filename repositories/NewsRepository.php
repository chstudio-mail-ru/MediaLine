<?php

namespace app\repositories;

use app\models\News;

class NewsRepository
{
    protected $newsModel;

    public function __construct(News $news)
    {
        $this->newsModel = $news;
    }

    public function addNews($newsDTO, $text, $source): bool
    {
        switch ($source) {
            case 'app\services\RBCService':
                $objNews = new News();
                $objNews->title = $newsDTO->title;
                $objNews->link = $newsDTO->link;
                $objNews->text = $text;
                $objNews->guid = $newsDTO->guid;
                $objNews->source = $source;
                $objNews->date_add = time();
                $objNews->date_news = date("Y-m-d H:i:s", strtotime($newsDTO->pubDate));
                $newsRepository = new self($objNews);
                $exists = $newsRepository->newsModel->getByGuid($newsDTO->guid);
                if (!$exists) {
                    $result = $newsRepository->newsModel->save();
                } else {
                    $result = false;
                }
                break;
            default:
                $result = false;
                break;
        }

        return $result;
    }
}