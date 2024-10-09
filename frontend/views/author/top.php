<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = "Топ-10";
$this->params["breadcrumbs"][] = ["label" => "Авторы книг", "url" => ["/author/index"]];
$this->params["breadcrumbs"][] = $this->title;
?>
<div class="top-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        "dataProvider" => $dataProvider,
        "filterModel" => $searchModel,
        "rowOptions" => function ($model) {
            return ["class" => "grid-view-row-clickable", "onclick" => "location.href = '" . Url::to(["/author/view", "id" => $model->id]) . "'"];
        },
        "columns" => [
            "id",
            "first_name",
            "last_name",
            "third_name",
            "year",
            "cnt",
        ],
    ]) ?>
</div>

