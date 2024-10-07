<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$subauthors = [];
foreach ($model->authorsNames as $author) {
    $subauthors[$author->id] = $author->first_name . " " . $author->last_name;
}
?>
<div class="book-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            "id",
            [
                'attribute' => 'image',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::img($model->image, ["style" => "width: 50px"]);
                },
            ],
            "title",
            "year",
            "description",
            "isbn",
            [
                'attribute' => 'authorsNames',
                'label'     => 'Авторы',
                'format'    => 'raw',
                'value'     => function ($model) {
                    return implode("<br>", array_map(function($row) {
                        return $row->first_name . " " . $row->last_name;
                    }, $model->authorsNames));
                },
            ],
        ],
    ]) ?>

    <?php
    if (Yii::$app->user->isGuest) {
        echo $this->render("_subform", [
            "subscriptionmodel" => $subscriptionmodel, 
            "subauthors" => $subauthors,
        ]);
    } else {
        echo Html::a("Редактировать", Url::to(['/book/update', 'id' => $model['id']]), ["class" => "btn btn-primary"]);
        echo Html::a("Удалить", Url::to(['/book/delete', 'id' => $model['id']]), ["class" => "btn btn-danger"]);
    }
    ?>
</div>

