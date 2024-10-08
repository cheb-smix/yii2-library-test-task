<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->title = $model->first_name . " " . $model->last_name;
$this->params["breadcrumbs"][] = ["label" => "Авторы книг", "url" => ["/author/index"]];
$this->params["breadcrumbs"][] = $this->title;

?>
<div class="author-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        "model" => $model,
        "attributes" => [
            "id",
            "first_name",
            "last_name",
            "third_name",
        ],
    ]) ?>

    <?php
    if (Yii::$app->user->isGuest) {
        // echo $this->render("_subform", [
        //     "subscriptionmodel" => $subscriptionmodel, 
        //     "subauthors" => $subauthors,
        // ]);
    } else {
        echo Html::a("Редактировать", Url::to(["/author/update", "id" => $model["id"]]), ["class" => "btn btn-primary"]);
        echo Html::a("Удалить", Url::to(["/author/delete", "id" => $model["id"]]), ["class" => "btn btn-danger"]);
    }
    ?>
</div>

