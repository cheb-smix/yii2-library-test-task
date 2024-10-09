<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * BookToAuthor
 *
 * @property int $id
 * @property int $book_id
 * @property int $author_id
 */

class BookToAuthor extends ActiveRecord 
{
    public function rules()
    {
        return [
            [["book_id", "author_id"], "required"],
            [["book_id", "author_id"], "integer"],
        ];
    }

    public function attributeLabels()
    {
        return [
            "book_id"   => "ID книги",
            "author_id" => "ID автора",
        ];
    }
}
