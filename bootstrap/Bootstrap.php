<?php

namespace app\bootstrap;

use app\services\RBCService;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = \Yii::$container;

        $container->setSingleton(RBCService::class);
    }
}