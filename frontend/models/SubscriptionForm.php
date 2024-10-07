<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Subscription;

class SubscriptionForm extends Model
{
    public $phone;
    public $authors = [];

    public function rules()
    {
        return [
            [["phone"], "string"],
            [["authors"], "each", "rule" => ["integer"]],
        ];
    }

    public function attributeLabels()
    {
        return [
            "phone"         => "Номер телефона",
            "authors"       => "Авторы",
        ];
    }

    public function save()
    {
        if ($this->authors) {
            $dbCheck = Subscription::find()->where(["phone" => $this->phone])->select("author_id")->distinct()->column();

            if (count($this->authors) == count($dbCheck)) {
                Yii::$app->session->setFlash("success", "Подписка уже оформлена");
            } else {
                $this->authors = array_diff($this->authors, $dbCheck);

                foreach ($this->authors as $author_id) {
                    $sub = new Subscription();
                    $sub->phone = $this->phone;
                    $sub->author_id = $author_id;
                    if (!$sub->save()) {
                        Yii::$app->session->setFlash("warning", "Ошибка оформления подписки");
                        return true;
                    }
                }

                Yii::$app->session->setFlash("success", "Подписка успешно оформлена");
            }
        } else {
            Yii::$app->session->setFlash("warning", "Выберите автора для подписки");
        }

        return true;
    }
}