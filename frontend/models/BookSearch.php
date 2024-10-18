<?php

namespace frontend\models;

use yii\data\ActiveDataProvider;
use common\models\Book;
use yii\db\Expression;

class BookSearch extends Book
{
    public $authors_list = [];

    public static function tableName()
    {
        return 'book';
    }

    public function rules()
    {
        return [
            [["title", "image"], "string", "length" => [0, 256]],
            [["year"], "integer", "min" => 0, "max" => 65535],
            [["description"], "string"],
            [["isbn"], "string", "length" => [0, 32]],
            [["authors_list"], "each", "rule" => ["integer"]],
        ];
    }

    public function attributeLabels()
    {
        return parent::attributeLabels() + [
            "authors_list" => "Авторы",
        ];
    }

    public function search($params)
    {
        $query = self::find()->joinWith(["authors"]);

        $dataProvider = new ActiveDataProvider([
            "query" => $query,
        ]);
    
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if ($this->title) {
            $query->andWhere(new Expression("MATCH (`title`, `description`) AGAINST ('{$this->title}*' IN BOOLEAN MODE)"));
        }

        $query->andFilterWhere([
            "=", "year", $this->year,
            "LIKE", "isbn", $this->isbn,
        ]);

        $query->andFilterWhere([
            "author_id" => $this->authors_list,
        ]);
    
        return $dataProvider;
    }
}