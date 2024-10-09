<?php

namespace frontend\models;

use yii\base\Model;
use common\models\Author;

class AuthorForm extends Model
{
    public $id;
    public $first_name;
    public $last_name;
    public $third_name;

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

    public function save()
    {
        $model = !$this->id ? new Author() : Author::findOne($this->id);

        $model->first_name  = $this->first_name;
        $model->last_name   = $this->last_name;
        $model->third_name  = $this->third_name;

        if ($model->save()) {
            $this->id = $model->id;
            return true;
        }

        return false;
    }
}