<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = 'Топ-10 авторов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="top-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            "id",
            "first_name",
            "last_name",
            "third_name",
            "year",
            "cnt",
        ],
    ]) ?>
</div>

