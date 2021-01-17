<?php

/* @var $this yii\web\View */
/* @var $objects array app\services\dto\RBCDto */

$this->title = 'MediaLine';
?>
<div class="site-index">
    <?php
    foreach ($objects as $object) {
        echo '<div>'.date("d.m.Y H:i:s", strtotime($object->pubDate)).'</div>';
        if ($object->text) {
            if (mb_strlen($object->text) > 200) {
                $text = mb_substr($object->text, 0, 200)."...";
            } else {
                $text = $object->text;
            }
            echo '<div>'.$text.'</div>';
        } elseif (count($object->newsParagraphs) > 0) {
            $text = "";
            foreach ($object->newsParagraphs as $newsParagraph) {
                if (is_string($newsParagraph)) {
                    $text .= '<div>'.$newsParagraph.'</div>';
                } elseif (is_object($newsParagraph)) {
                    $text .= '<div>'.$newsParagraph->text.'</div>';
                }
            }
            if (mb_strlen(strip_tags($text)) > 200) {
                $text = mb_substr(strip_tags($text), 0, 200)."...";
            }
            echo '<div>'.$text.'</div>';
        }
        echo '<div><a href="/rbc-detail/'.$object->guid.'" title="'.$object->description.'">Побробнее</a></div>';
    }
    ?>
</div>
