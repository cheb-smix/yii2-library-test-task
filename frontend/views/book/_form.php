<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

$form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'title') ?>
    <?= $form->field($model, 'year')->textInput(["type" => "number"]) ?>
    <?= $form->field($model, 'description')->textarea() ?>
    <?= $form->field($model, 'isbn')->textInput() ?>
    <?= $form->field($model, 'image')->textInput() ?>
    <?= $form->field($model, 'authors')->widget(Select2::class, [
        'data' => $authors,
        'options' => ['placeholder' => 'Выберите автора ...'],
        'pluginOptions' => [
            'allowClear' => true,
            'multiple' => true,
        ],
    ]) ?><br>
    <?= Html::submitButton("Сохранить", ["class" => "btn btn-success"]) ?>
<?php ActiveForm::end(); ?>
