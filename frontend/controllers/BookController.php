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
use frontend\models\BookSearch;
use frontend\models\BookForm;
use frontend\models\SubscriptionForm;

class BookController extends Controller
{
    public function behaviors()
    {
        return [
            "access" => [
                "class" => AccessControl::class,
                // "only" => ["logout", "signup"],
                "rules" => [
                    [
                        "actions" => ["index", "view"],
                        "allow" => true,
                        "roles" => ["?"],
                    ],
                    [
                        "actions" => ["index", "view", "create", "update", "delete"],
                        "allow" => true,
                        "roles" => ["@"],
                    ],
                ],
            ],
            "verbs" => [
                "class" => VerbFilter::class,
                "actions" => [
                    "logout" => ["post"],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new BookSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render("index", [
            "dataProvider"  => $dataProvider,
            "searchModel"   => $searchModel,
            "authors"       => Author::getList(),
        ]);
    }

    public function actionView($id)
    {
        $model = Book::find()->where(["id" => $id])->with("authorsNames")->one();

        if (!$model) {
            throw new NotFoundHttpException();
        }

        $subscriptionmodel = new SubscriptionForm();

        if (Yii::$app->request->isPost) {
            $subscriptionmodel->load(Yii::$app->request->post());
            $subscriptionmodel->validate();
            $subscriptionmodel->save();
        }

        return $this->render("view", [
            "model"  => $model,
            "subscriptionmodel" => $subscriptionmodel,
        ]);
    }

    public function actionUpdate($id)
    {
        $book = Book::find()->where(["id" => $id])->with("authors")->asArray()->one();

        if (!$book) {
            throw new NotFoundHttpException();
        }

        $book["authors"] = array_map(function($item) {
            return $item["author_id"];
        }, $book["authors"]);

        $model = new BookForm();
        $model->id = $id;

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
                Yii::$app->session->setFlash("success", "Книга сохранена!");
                return $this->redirect(Url::to(["/book/view", "id" => $id]));
            }
        } else {
            $model->load(["BookForm" => ArrayHelper::toArray($book)], null);
        }

        return $this->render("update", [
            "model"  => $model,
            "authors"=> Author::getList(),
        ]);
    }

    public function actionCreate()
    {
        $model = new BookForm();

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
                Yii::$app->session->setFlash("success", "Книга добавлена!");
                return $this->redirect(Url::to(["/book/view", "id" => $model->id]));
            }
        }

        return $this->render("create", [
            "model"  => $model,
            "authors"=> Author::getList(),
        ]);
    }

    public function actionDelete($id)
    {
        $book = Book::find()->where(["id" => $id])->one();

        if (!$book) {
            throw new NotFoundHttpException();
        }

        if ($book->delete()) {
            return $this->redirect(Url::to(["/book/index"]));
        }
    }
}
