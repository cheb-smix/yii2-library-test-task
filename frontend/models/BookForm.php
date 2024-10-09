<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Book;
use common\models\BookToAuthor;
use frontend\helpers\Informer;

class BookForm extends Model
{
    public $id;
    public $title;
    public $year;
    public $description;
    public $isbn;
    public $image;
    public $authors = [];

    public function rules()
    {
        return [
            [["title", "year"], "required"],
            [["title", "image"], "string", "length" => [0, 256]],
            [["year"], "integer", "min" => 0, "max" => 65535],
            [["description"], "string"],
            [["isbn"], "string", "length" => [0, 32]],
            [["authors"], "each", "rule" => ["integer"]],
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
            "authors"       => "Авторы",
        ];
    }

    public function save()
    {
        $newBook = !$this->id;

        $model = $newBook ? new Book() : Book::findOne($this->id);

        $model->title = $this->title;
        $model->year = $this->year;
        $model->description = $this->description;
        $model->isbn = $this->isbn;
        $model->image = $this->image;

        if ($model->save()) {
            if ($this->authors) {
                if (!$newBook) {
                    BookToAuthor::deleteAll([
                        "AND",
                        ["book_id" => $this->id],
                        ["NOT IN", "author_id", $this->authors],
                    ]);
                }
                foreach ($this->authors as $author_id) {
                    $bta = new BookToAuthor();
                    $bta->book_id = $model->id;
                    $bta->author_id = $author_id;
                    if (!$bta->save()) {
                        $this->addError("authors", "Не удалось указать автора книги");
                        return false;
                    }
                }

                if ($newBook) {
                    $result = Informer::send($this);
                    if ($result["total"]) {
                        if ($result["success"]) {
                            Yii::$app->session->setFlash("warning", "Успешно отправлено {$result["success"]} уведомлений");
                        }
                        if ($result["error"]) {
                            Yii::$app->session->setFlash("danger", "Ошибка доставки {$result["error"]} уведомлений");
                        }
                    }
                }
            }

            return true;
        } else {
            return false;
        }

    }
}