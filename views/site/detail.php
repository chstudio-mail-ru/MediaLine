<?php

/* @var $this yii\web\View */
/* @var $model app\models\News */

$this->title = $model->title;
?>
<div class="site-index">

    <?php
    echo '<pre>'; echo print_r($model, true); echo '</pre>';
    ?>
</div>
