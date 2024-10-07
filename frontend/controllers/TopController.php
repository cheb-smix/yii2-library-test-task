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
        $searchModel = new TopSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render("index", [
            "dataProvider"  => $dataProvider,
            "searchModel"   => $searchModel,
        ]);
    }
}