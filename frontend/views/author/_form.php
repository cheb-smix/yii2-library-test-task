<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin(); ?>
    <?= $form->field($model, "first_name")->textInput() ?>
    <?= $form->field($model, "last_name")->textInput() ?>
    <?= $form->field($model, "third_name")->textInput() ?><br>
    <?= Html::submitButton("Сохранить", ["class" => "btn btn-success"]) ?>
<?php ActiveForm::end(); ?>
