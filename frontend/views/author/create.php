<?php

$this->title = "Новый автор";
$this->params["breadcrumbs"][] = ["label" => "Авторы книг", "url" => ["/author/index"]];
$this->params["breadcrumbs"][] = $this->title;
?>
<div class="author-create">
<?= $this->render("_form", [
    "model" => $model, 
])?>
</div>