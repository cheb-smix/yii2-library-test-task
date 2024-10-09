<?php

namespace frontend\models;

use yii\data\ActiveDataProvider;
use common\models\Author;

class AuthorSearch extends Author
{
    public $authors_list = [];

    public static function tableName()
    {
        return "author";
    }

    public function search($params)
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            "query" => $query,
        ]);
    
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            "LIKE", "title", $this->first_name,
            "LIKE", "title", $this->last_name,
            "LIKE", "title", $this->third_name,
        ]);
    
        return $dataProvider;
    }
}