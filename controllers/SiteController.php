<?php

namespace app\controllers;

use yii\web\Controller;
use app\services\RBCService;

class SiteController extends Controller
{
    private $RBCService;

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function __construct($id, $module, RBCService $RBCService, $config = [])
    {
        $this->RBCService = $RBCService;
        parent::__construct($id, $module, $config);
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $objects = $this->RBCService->importNews();

        return $this->render('index', ['objects' => $objects]);
    }
}
