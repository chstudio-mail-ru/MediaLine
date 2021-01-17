<?php

namespace app\services\dto;

class RBCDto
{
    public $title;
    public $text;
    public $link;
    public $description;
    public $author;
    public $guid;
    public $pubDate;
    public $enclosureUrl = [];
    public $enclosureType = [];
    public $enclosureLength = [];
}