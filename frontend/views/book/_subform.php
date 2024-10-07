<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

$form = ActiveForm::begin(); ?>
    <?= $form->field($subscriptionmodel, 'phone')->textInput() ?>
    <?= $form->field($subscriptionmodel, 'authors')->widget(Select2::class, [
        'data' => $subauthors,
        'options' => ['placeholder' => 'Выберите автора ...'],
        'pluginOptions' => [
            'allowClear' => true,
            'multiple' => true,
        ],
    ]) ?><br>
    <?= Html::submitButton("Подписаться", ["class" => "btn btn-success"]) ?>
<?php ActiveForm::end(); ?>
