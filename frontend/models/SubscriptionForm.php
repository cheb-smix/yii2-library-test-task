<?php

namespace frontend\models;

use yii\base\Model;
use common\models\Subscription;

class SubscriptionForm extends Model
{
    public $phone;
    public $author_id;

    public function rules()
    {
        return [
            [["phone", "author_id"], "required"],
            [["phone"], "string"],
            [["author_id"], "integer"],
        ];
    }

    public function attributeLabels()
    {
        return [
            "phone"         => "Номер телефона",
            "author_id"     => "Автор",
        ];
    }

    public function save()
    {
        $model = Subscription::findOne([
            "phone"     => $this->phone,
            "author_id" => $this->author_id,
        ]);

        if ($model) {
            $this->addError("author_id", "Подписка уже оформлена");
        } else {
            $model = new Subscription();
            $model->phone = $this->phone;
            $model->author_id = $this->author_id;
            if (!$model->save()) {
                $this->addError("author_id", "Ошибка оформления подписки");
                return false;
            }
        }

        return true;
    }
}