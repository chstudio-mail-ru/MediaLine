<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "news".
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property string $link
 * @property string $description
 * @property string $source
 * @property string $author
 * @property string $guid
 * @property integer $date_add
 * @property integer $date_update
 * @property string $date_news
 */
class News extends ActiveRecord
{
    public static function tableName()
    {
        return 'news';
    }

    public function rules()
    {
        return [
            [['title', 'text', 'guid'], 'required'],
            [['title', 'text', 'link', 'source', 'guid'], 'string'],
            [['date_news'], 'date', 'format' => 'php:d.m.Y', 'skipOnEmpty' => true, 'message' => 'Неправильный формат',
                'when' => function () {
                    $checked = false;
                    if (preg_match("/^\d\d\d\d\-\d\d\-\d\d\s\d\d:\d\d:\d\d$/", $this->date_news)) {
                        preg_match_all("/^(\d\d\d\d)\-(\d\d)\-(\d\d)\s/", $this->date_news, $matches);
                        $day = $matches[3][0];
                        $month = $matches[2][0];
                        $year = $matches[1][0];
                        $checked = checkdate($month, $day , $year);
                    }
                    if (!$checked) {
                        $this->addError('date_news', 'Неправильный формат '.$this->date_news);
                    }
                },
            ],
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public static function getById($id): ?News
    {
        return static::findOne(['id' => $id]);
    }

    public static function getByGuid($guid): ?News
    {
        return static::findOne(['guid' => $guid]);
    }

    public static function getAll($source = ""): array
    {
        return self::find()
            ->where(['source' => $source])
            ->orderBy(['date_news' => SORT_DESC])
            ->all();
    }

    public static function getLimit($source = "", $limit = 15): array
    {
        return self::find()
            ->where(['source' => $source])
            ->orderBy(['date_news' => SORT_DESC])
            ->limit($limit)
            ->all();
    }
}