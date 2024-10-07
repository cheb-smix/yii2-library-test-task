<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;

class Author extends ActiveRecord 
{
    public function rules()
    {
        return [
            [["first_name", "last_name"], "required"],
            [["first_name", "last_name", "third_name"], "string", "length" => [0, 64]],
        ];
    }

    public function attributeLabels()
    {
        return [
            "first_name"    => "Имя",
            "last_name"     => "Фамилия",
            "third_name"    => "Отчество",
        ];
    }

    public function getBooks()
    {
        return $this->hasMany(Book::class, ['id' => 'book_id'])->viaTable(BookToAuthor::tableName(), ['author_id' => 'id']);
    }

    public static function getList()
    {
        $cacheKey = "authors_list";

        if (!$data = Yii::$app->cache->get($cacheKey)) {
            $data = self::find()->select(new Expression("CONCAT(`first_name`, ' ', `last_name`)"))->indexBy("id")->column();

            Yii::$app->cache->set($cacheKey, $data, 3600);
        }

        return $data;
    }
}
