<?php

$this->title = "Новая книга";
$this->params["breadcrumbs"][] = ["label" => "Каталог", "url" => ["index"]];
$this->params["breadcrumbs"][] = $this->title;
?>
<div class="book-create">
<?= $this->render("_form", [
    "model" => $model, 
    "authors" => $authors,
])?>
</div>