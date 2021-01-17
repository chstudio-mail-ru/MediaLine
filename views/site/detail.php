<?php

/* @var $this yii\web\View */
/* @var $dto app\services\dto\RBCDto */
/* @var $images array string */

$this->title = $dto->title;
?>
<div class="site-index">
<?php
    echo "<h1>".$dto->title."</h1>";
    echo "<div>".$dto->description."</div>";
    foreach ($images as $imgUrl) {
        echo '<div>';
        echo '  <img src="'.$imgUrl.'" alt="'.$dto->title.'" />';
        echo '</div>';
    }
    echo '<div>'.date("d.m.Y H:i:s", strtotime($dto->pubDate)).'</div>';
    echo "<div>".$dto->text."</div>";
    foreach ($dto->newsParagraphs as $newsParagraph) {
        if (is_string($newsParagraph)) {
            echo '<div>'.$newsParagraph.'</div>';
        }
   }
    echo ($dto->author)? "<div>Автор:".$dto->author."</div>" : null;
?>
</div>
