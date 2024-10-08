<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = "Авторы книг";
$this->params["breadcrumbs"][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <?= Html::a("Новый автор", Url::to(["/author/create"]), ["class" => "btn btn-success pull-right"]) ?>
    </div>
</div>

<div class="author-index">
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
        ],
    ]) ?>
</div>

