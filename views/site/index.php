<?php

/* @var $this yii\web\View */
/* @var $objects array app\services\dto\RBCDto */
/* @var $texts array string */

$this->title = 'MediaLine';
?>
<div class="site-index">
    <?php
    foreach ($objects as $object) {
        if (mb_strlen(strip_tags($texts[$object->guid])) > 200) {
            $text = mb_substr(strip_tags($texts[$object->guid]), 0, 200)."...";
        } else {
            $text = $texts[$object->guid];
        }
        echo '<div>'.date("d.m.Y H:i:s", strtotime($object->pubDate)).'</div>
        <div>'.$text.'</div>
        <div><a href="/rbc-detail/'.$object->guid.'" title="'.$object->description.'">Побробнее</a></div>';
    }
    ?>
</div>
