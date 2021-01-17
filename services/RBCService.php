<?php

namespace app\services;

use app\models\News;
use app\repositories\NewsRepository;
use app\services\dto\RBCDto;
use Yii;

class RBCService
{
    const RSS_URL = "http://static.feed.rbc.ru/rbc/logical/footer/news.rss";
    const BODY_NEWS_FIRST_TAG = '<div class="article__text article__text_free" itemprop="articleBody">';
    const BODY_NEWS_LAST_TAG = '<div class="article__clear"></div>';
    const BROWSER_OPTS = [
        'http' => [
            'method'=>"GET",
            'header'=> "User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36\r\n"
        ],
    ];
    const NUM_IMPORT_NEWS = 15;

    protected $newsRepository;

    public function __construct(NewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    private function loadNewsImages(RBCDto $newsRecordDTO)
    {
        $filePath = Yii::getAlias('@webroot/images/'.$newsRecordDTO->guid.'/');
        if (!file_exists($filePath)) {
            mkdir($filePath, 0777, true);
        }

        foreach ($newsRecordDTO->enclosureUrl as $key => $value) {
            if (isset($newsRecordDTO->enclosureType[$key]) && ($newsRecordDTO->enclosureType[$key] == "image/jpeg" || $newsRecordDTO->enclosureType[$key] == "image/png")) {
                $fileName = preg_replace("/^.*?\/([\w\d\.]+?)$/", "\\1", $value);
                if (!file_exists($filePath.$fileName)) {
                    $context = stream_context_create(self::BROWSER_OPTS);
                    $image = file_get_contents($value, false, $context);
                    $fp = fopen($filePath.$fileName,"w");
                    fwrite($fp, $image);
                    fclose($fp);
                }
            }
        }
    }

    private function getNewsText(RBCDto $newsRecordDTO): ?string
    {
        $context = stream_context_create(self::BROWSER_OPTS);
        $document = file_get_contents($newsRecordDTO->link, false, $context);
        $newsText = explode(self::BODY_NEWS_FIRST_TAG, $document);
        if (isset($newsText[1])) {
            $newsText = explode(self::BODY_NEWS_LAST_TAG, $newsText[1]);
        } else {
            return null;
        }
        preg_match_all("/<p>(.*?)<\/p>/i", $newsText[0], $matches);
        $newsText = "";
        foreach ($matches[1] as $partNews) {
            $newsText .= "<div>".strip_tags($partNews)."</div>";    //TODO: убрать <div>
        }

        return $newsText;
    }

    public function importNews($numNews = self::NUM_IMPORT_NEWS): array
    {
        $context = stream_context_create(self::BROWSER_OPTS);
        $xmlString = file_get_contents(self::RSS_URL, false, $context);
        $xmlObject = simplexml_load_string($xmlString,'SimpleXMLElement', LIBXML_NOCDATA);
        $count = 0;
        $objects = [];
        foreach ($xmlObject->channel->item as $item) {
            if ($count < $numNews) {
                $newsDTO = new RBCDto();
                $newsDTO->title = (string) $item->title;
                $newsDTO->link = (string) $item->link;
                $newsDTO->description = (string) $item->description;
                $newsDTO->author = (string) $item->author;
                $newsDTO->guid = (string) $item->guid;
                $newsDTO->pubDate = (string) $item->pubDate;
                foreach ($item->enclosure as $enclosureItem) {
                    $newsDTO->enclosureUrl[] = (string)$enclosureItem->attributes()->url;
                    $newsDTO->enclosureType[] = (string)$enclosureItem->attributes()->type;
                    $newsDTO->enclosureLength[] = (string)$enclosureItem->attributes()->length;
                }
                $newsDTO->text = $this->getNewsText($newsDTO);
                $this->newsRepository->addNews($newsDTO, self::class);
                $objects[] = $newsDTO;
                $this->loadNewsImages($newsDTO);
                $count++;
            } else {
                break;
            }
        }

        return $objects;
    }

    private function getDTO(?News $model): ?RBCDto
    {
        if (!$model) {
            return null;
        }
        $dtoEntity = new RBCDto();
        $dtoEntity->title = $model->title;
        $dtoEntity->text = $model->text;
        $dtoEntity->link = $model->link;
        $dtoEntity->description = $model->description;
        $dtoEntity->author = $model->author;
        $dtoEntity->guid = $model->guid;
        $dtoEntity->pubDate = $model->date_news;

        return $dtoEntity;
    }

    public function getNewsById($id): ?RBCDto
    {
        return $this->getDTO($this->newsRepository->getById($id));
    }

    public function getNewsByGuid($guid): ?RBCDto
    {
        return $this->getDTO($this->newsRepository->getByGuid($guid));
    }

    public function getAll($source = "app\services\RBCService"): array
    {
        $models = $this->newsRepository->getAll($source);
        $dtoArray = [];
        foreach ($models as $model) {
            $dtoArray[] = $this->getDTO($model);
        }

        return $dtoArray;
    }

    public function getLimit($source = "app\services\RBCService", $limit = 15): array
    {
        $models = $this->newsRepository->getLimit($source, $limit);
        $dtoArray = [];
        foreach ($models as $model) {
            $dtoArray[] = $this->getDTO($model);
        }

        return $dtoArray;
    }
}