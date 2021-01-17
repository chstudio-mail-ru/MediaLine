<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "news_paragraph".
 *
 * @property integer $id
 * @property integer $news_id
 * @property string $text
 * @property integer $date_add
 * @property integer $date_update
 */
class NewsParagraph extends ActiveRecord
{
    public static function tableName()
    {
        return 'news_paragraph';
    }

    public function rules()
    {
        return [
            [['text', 'news_id'], 'required'],
            [['text'], 'string'],
            [['news_id'], 'integer']
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public static function getById($id): ?NewsParagraph
    {
        return static::findOne(['id' => $id]);
    }

    public static function getAllByNewsId($newsId = 0): array
    {
        return self::find()
            ->where(['news_id' => $newsId])
            ->orderBy(['id' => SORT_ASC])
            ->all();
    }
}