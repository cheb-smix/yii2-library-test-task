<?php

/** @var yii\web\View $this */

$this->title = "Правка";
$this->params["breadcrumbs"][] = ["label" => "Каталог", "url" => ["index"]];
$this->params["breadcrumbs"][] = ["label" => $model->title, "url" => ["/book/view", "id" => $model->id]];
$this->params["breadcrumbs"][] = $this->title;
?>
<div class="book-update">
<?= $this->render("_form", [
    "model" => $model, 
    "authors" => $authors,
])?>
</div>