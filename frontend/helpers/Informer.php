<?php

namespace frontend\helpers;

use Yii;
use frontend\models\BookForm;
use common\models\Author;
use common\models\Subscription;

class Informer
{
    const apikey = "XXXXXXXXXXXXYYYYYYYYYYYYZZZZZZZZXXXXXXXXXXXXYYYYYYYYYYYYZZZZZZZZ";

    public static function send(BookForm $model) : array
    {
        $authors = Author::getList();

        $subscribers = Subscription::findAll(["author_id" => $model->authors]);

        $distinctBookCheck = [];

        $result = [
            "total" => 0,
            "success" => 0,
            "error" => 0,
        ];

        foreach ($subscribers as $subscriber) {

            if (!isset($distinctBookCheck[$subscriber->phone])) {
                $distinctBookCheck[$subscriber->phone] = [];
            } else {
                if (in_array($model->id, $distinctBookCheck[$subscriber->phone])) {
                    // Не будем информировать об одной книге дважды (если авторов несколько и подписка есть на нескольких из них)
                    continue;
                }
            }

            $result["total"]++;
            
            $message = "У автора " . $authors[$subscriber->author_id] . " вышла новая книга «" . $model->title . "»";

            $response = file_get_contents("https://smspilot.ru/api.php?send=" . urlencode($message) . "&to={$subscriber->phone}&apikey=" . self::apikey . "&format=json");

            if ($response) {
                try {
                    $response = json_decode($response);
                    if (isset($response->send)) {
                        $result["success"]++;
                        $distinctBookCheck[$subscriber->phone][] = $model->id;
                        continue;
                    }
                } catch (\Exception $e) {
                    //
                }
            }

            $result["error"]++;

        }

        return $result;
    }
}