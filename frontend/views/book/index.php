<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = 'Каталог';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <?= Html::a("Новая книга", Url::to(['/book/create']), ["class" => "btn btn-success pull-right"]) ?>
    </div>
</div>

<div class="book-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function ($model) {
            return ['class' => 'grid-view-row-clickable', 'onclick' => 'location.href = "' . Url::to(['/book/view', 'id' => $model['id']]) . '"'];
        },
        'columns' => [
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
                'attribute' => 'authors_list',
                'format' => 'raw',
                'filter' => $authors,
                'value' => function ($model) use ($authors) {
                    return implode("<br>", array_map(function($row) use ($authors){
                        return $authors[$row->author_id];
                    }, $model->authors));
                },
            ],
        ],
    ]) ?>
</div>

