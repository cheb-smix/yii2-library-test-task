<?php

namespace frontend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use common\models\Book;
use common\models\Author;
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
        $model = $this->findModel($id, ["authorsNames"]);

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
        $book = $this->findModel($id, ["authors"], true);

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
        $book = $this->findModel($id);

        if ($book->delete()) {
            return $this->redirect(Url::to(["/book/index"]));
        }
    }

    private function findModel($id, $relations = [], $asArray = false)
    {
        $model = Book::find()->where(["id" => $id])->with($relations)->asArray($asArray)->one();

        if (!$model) {
            throw new NotFoundHttpException();
        }

        return $model;
    }
}
