<?php

/* @var $this yii\web\View */
/* @var $objects array app\services\dto\RBCDto */
/* @var $texts array string */

$this->title = 'MediaLine';
?>
<div class="site-index">
    <?php
    foreach ($objects as $object) {
        if (mb_strlen($texts[$object->guid]) > 200) {
            $text = mb_substr($texts[$object->guid], 0, 200)."...";
        } else {
            $text = $texts[$object->guid];
        }
        echo '<div>'.date("d.m.Y H:i:s", strtotime($object->pubDate)).'</div>
        <div>'.$text.'</div>
        <div><a href="/rbc-detail/'.$object->guid.'">Побробнее</a></div>';
    }
    ?>
</div>
