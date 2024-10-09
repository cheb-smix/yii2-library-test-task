<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->registerJsFile("/js/subscription-form.js", ["position" => $this::POS_HEAD]);
$this->registerCssFile("/css/subscription-form.css", ["position" => $this::POS_HEAD]);

if (!isset($btnLabel)) {
    $btnLabel = "+ Подписаться";
}

if (!isset($btnClass)) {
    $btnClass = "btn btn-sm btn-success";
}

if (!isset($btnTag)) {
    $btnTag = "button";
}

echo Html::tag($btnTag, $btnLabel, [
    "class"             => $btnClass,
    "data-author-id"    => $author_id,
    "data-author-name"  => $author_name,
    "style"             => "cursor: pointer",
    "title"             => "Подписаться на автора",
    "onclick"           => "(new SubscriptionPopup(this)).show()",
]);
