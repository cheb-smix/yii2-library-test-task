<?php

namespace common\models;

use yii\db\ActiveRecord;

class Subscription extends ActiveRecord 
{
    public function rules()
    {
        return [
            [["phone", "author_id"], "required"],
            [["phone"], "string", "length" => [4, 15]],
            [["author_id"], "integer"],
        ];
    }

    public function attributeLabels()
    {
        return [
            "phone"     => "Телефон пользователя",
            "author_id" => "ID автора",
        ];
    }
}
