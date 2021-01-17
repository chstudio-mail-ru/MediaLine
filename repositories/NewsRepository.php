<?php

namespace app\repositories;

use app\models\News;
use app\models\NewsParagraph;

class NewsRepository
{
    protected $newsModel;

    public function __construct(News $news)
    {
        $this->newsModel = $news;
    }

    public function getById($id): ?News
    {
        return $this->newsModel->getById($id);
    }

    public function getByGuid($guid): ?News
    {
        return $this->newsModel->getByGuid($guid);
    }

    public function getId(): int
    {
        return $this->newsModel->getId();
    }

    public function addNews($newsDTO, $source): bool
    {
        switch ($source) {
            case 'app\services\RBCService':
                $objNews = new News();
                $objNews->title = $newsDTO->title;
                $objNews->link = $newsDTO->link;
                $objNews->description = $newsDTO->description;
                $objNews->author = $newsDTO->author;
                $objNews->text = $newsDTO->text;
                $objNews->guid = $newsDTO->guid;
                $objNews->source = $source;
                $objNews->date_add = time();
                $objNews->date_news = date("Y-m-d H:i:s", strtotime($newsDTO->pubDate));
                $newsRepository = new self($objNews);
                $existsModel = $newsRepository->newsModel->getByGuid($newsDTO->guid);
                if (!$existsModel) {
                    $result = $newsRepository->newsModel->save();
                    if ($result) {
                        foreach ($newsDTO->newsParagraphs as $newsParagraph) {
                            $objNewsParagraph = new NewsParagraph();
                            $objNewsParagraph->news_id = $newsRepository->newsModel->getId();
                            $objNewsParagraph->text = $newsParagraph;
                            $objNewsParagraph->date_add = time();
                            $objNewsParagraph->save();
                        }
                    }
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

    public function getAll($source = ""): array
    {
        return $this->newsModel->getAll($source);
    }

    public function getLimit($source = "", $limit = 15): array
    {
        return $this->newsModel->getLimit($source, $limit);
    }
}