<?php

/** @var yii\web\View $this */

$this->title = "Правка";
$this->params["breadcrumbs"][] = ["label" => "Авторы книг", "url" => ["/author/index"]];
$this->params["breadcrumbs"][] = ["label" => $model->first_name . " " . $model->last_name, "url" => ["/author/view", "id" => $model->id]];
$this->params["breadcrumbs"][] = $this->title;
?>
<div class="author-update">
<?= $this->render("_form", [
    "model" => $model,
])?>
</div>