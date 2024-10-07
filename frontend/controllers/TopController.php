<?php

namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use common\models\Book;
use common\models\Author;
use common\models\BookToAuthor;
use common\models\Subscription;
use frontend\models\TopSearch;
use frontend\models\BookForm;
use frontend\models\SubscriptionForm;

class TopController extends Controller
{
    public function actionIndex()
    {
        // $data = Author::find()
        // ->select([
        //     Author::tableName() . ".id",
        //     Author::tableName() . ".first_name",
        //     Author::tableName() . ".last_name",
        //     Author::tableName() . ".third_name",
        //     "count(distinct " . Book::tableName() . ".id) as cnt",
        // ])
        // ->joinWith(["books"], false)
        // ->where(["year" => $year])
        // ->groupBy(["id", "year"])
        // ->orderBy(["cnt" => SORT_DESC])
        // ->limit(10)
        // ->all();

        // return $this->render('index', [
        //     "data" => $data,
        // ]);

        $searchModel = new TopSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            "dataProvider"  => $dataProvider,
            "searchModel"   => $searchModel,
        ]);
    }
}