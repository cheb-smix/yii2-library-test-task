<?php

namespace frontend\models;

use Yii;
use yii\data\ActiveDataProvider;
use common\models\Author;
use common\models\Book;

class TopSearch extends Author
{
    public $year;
    public $cnt;

    public static function tableName()
    {
        return 'author';
    }

    public function rules()
    {
        return [
            [["year"], "integer"],
        ];
    }

    public function attributeLabels()
    {
        return parent::attributeLabels() + [
            "cnt" => "Количество книг",
            "year"=> "Год выпуска",
        ];
    }

    public function search($params)
    {
        $query = self::find()->select([
            Author::tableName() . ".id",
            Author::tableName() . ".first_name",
            Author::tableName() . ".last_name",
            Author::tableName() . ".third_name",
            Book::tableName()   . ".year",
            "count(distinct " . Book::tableName() . ".id) as cnt",
        ])
        ->joinWith(["books"], false)
        ->groupBy(["id", "year"])
        ->orderBy(["cnt" => SORT_DESC])
        ->limit(10);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
    
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            "=", "year", $this->year,
        ]);
    
        return $dataProvider;
    }
}