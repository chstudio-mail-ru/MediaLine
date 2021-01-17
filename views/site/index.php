<?php

/* @var $this yii\web\View */
/* @var $objects array app\services\dto\RBCDto */

$this->title = 'MediaLine';
?>
<div class="site-index">
    <?php
    foreach ($objects as $object) {
        if (mb_strlen(strip_tags($object->text)) > 200) {
            $text = mb_substr(strip_tags($object->text), 0, 200)."...";
        } else {
            $text = $object->text;
        }
        echo '<div>'.date("d.m.Y H:i:s", strtotime($object->pubDate)).'</div>
        <div>'.$text.'</div>
        <div><a href="/rbc-detail/'.$object->guid.'" title="'.$object->description.'">Побробнее</a></div>';
    }
    ?>
</div>
