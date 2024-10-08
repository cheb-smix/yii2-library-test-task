<?php

namespace common\models;

use yii\db\ActiveRecord;

class Book extends ActiveRecord 
{
    public function rules()
    {
        return [
            [["title", "year"], "required"],
            [["title", "image"], "string", "length" => [0, 256]],
            [["year"], "integer", "min" => 0, "max" => 65535],
            [["description"], "string"],
            [["isbn"], "string", "length" => [0, 32]],
        ];
    }

    public function attributeLabels()
    {
        return [
            "title"         => "Название",
            "year"          => "Год выпуска",
            "description"   => "Описание",
            "isbn"          => "ISBN",
            "image"         => "Обложка",
        ];
    }

    public function getAuthors()
    {
        return $this->hasMany(BookToAuthor::class, ["book_id" => "id"]);
    }

    public function getAuthorsNames()
    {
        return $this->hasMany(Author::class, ["id" => "author_id"])->viaTable(BookToAuthor::tableName(), ["book_id" => "id"]);
    }

}
