<?php

namespace frontend\models;

use yii\data\ActiveDataProvider;
use common\models\Book;

class BookSearch extends Book
{
    public $authors_list = [];

    public static function tableName()
    {
        return "book";
    }

    public function rules()
    {
        return parent::rules() + [
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

        $query->andFilterWhere([
            "LIKE", "title", $this->title,
            "=", "year", $this->year,
            "LIKE", "isbn", $this->isbn,
        ]);

        $query->andFilterWhere([
            "author_id" => $this->authors_list,
        ]);
    
        return $dataProvider;
    }
}