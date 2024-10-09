<?php

namespace frontend\controllers;

use Yii;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use common\models\Book;
use common\models\Author;
use frontend\models\AuthorSearch;
use frontend\models\AuthorForm;
use frontend\models\TopSearch;
use frontend\models\SubscriptionForm;

class AuthorController extends Controller
{
    public function behaviors()
    {
        return [
            "access" => [
                "class" => AccessControl::class,
                "rules" => [
                    [
                        "actions" => ["index", "view", "subscribe", "top"],
                        "allow" => true,
                        "roles" => ["?"],
                    ],
                    [
                        "actions" => ["index", "view", "create", "update", "delete", "top"],
                        "allow" => true,
                        "roles" => ["@"],
                    ],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if ($action->id == "subscribe") {
            Yii::$app->response->format = Response::FORMAT_JSON;
        }

        if (!parent::beforeAction($action)) {
            return false;
        }

        return true;
    }

    public function actionIndex()
    {
        $searchModel = new AuthorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render("index", [
            "dataProvider"  => $dataProvider,
            "searchModel"   => $searchModel,
        ]);
    }

    public function actionView($id)
    {
        return $this->render("view", [
            "model"  => $this->findModel($id),
        ]);
    }

    public function actionUpdate($id)
    {
        $author = $this->findModel($id);

        $model = new AuthorForm();
        $model->id = $id;

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
                Yii::$app->session->setFlash("success", "Автор сохранен!");
                return $this->redirect(Url::to(["/author/view", "id" => $id]));
            }
        } else {
            $model->load(["AuthorForm" => ArrayHelper::toArray($author)], null);
        }

        return $this->render("update", [
            "model"  => $model,
        ]);
    }

    public function actionCreate()
    {
        $model = new AuthorForm();

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
                Yii::$app->session->setFlash("success", "Автор добавлен!");
                return $this->redirect(Url::to(["/author/view", "id" => $model->id]));
            }
        }

        return $this->render("create", [
            "model"  => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->delete()) {
            return $this->redirect(Url::to(["/author/index"]));
        }
    }

    public function actionTop()
    {
        $searchModel = new TopSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render("top", [
            "dataProvider"  => $dataProvider,
            "searchModel"   => $searchModel,
        ]);
    }

    public function actionSubscribe($id)
    {
        if (!Yii::$app->request->isAjax) {
            return [
                "success"   => false,
                "message"   => "Некорректный запрос",
            ];
        }

        $model = $this->findModel($id);

        $subscription = new SubscriptionForm();
        $subscription->author_id = $model->id;
        $subscription->phone = Yii::$app->request->post("phone");

        if (!$subscription->validate() || !$subscription->save()) {
            return [
                "success"   => false,
                "message"   => current($subscription->getFirstErrors()),
            ];
        }

        return [
            "success"   => true,
            "message"   => "Подписка на автора {$model->first_name} {$model->last_name} успешно оформлена!",
        ];
    }

    private function findModel($id)
    {
        $model = Author::find()->where(["id" => $id])->one();

        if (!$model) {
            throw new NotFoundHttpException();
        }

        return $model;
    }
}
