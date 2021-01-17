<?php

/* @var $this yii\web\View */
/* @var $model app\models\News */
/* @var $images array string */

$this->title = $model->title;
?>
<div class="site-index">
<?php
    echo "<h1>".$model->title."</h1>";
    foreach ($images as $imgUrl) {
        echo '<div>';
        echo '  <img src="'.$imgUrl.'" alt="'.$model->title.'" />';
        echo '</div>';
    }
    echo '<div>'.date("d.m.Y H:i:s", strtotime($model->date_news)).'</div>';
    echo "<div>".$model->text."</div>";
?>
</div>
