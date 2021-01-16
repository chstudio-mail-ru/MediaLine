<?php

namespace app\controllers;

use yii\web\Controller;
use app\services\RBCService;

class SiteController extends Controller
{
    protected $RBCService;

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
        $detailTexts = [];

        foreach ($objects as $object) {
            $model = $this->RBCService->getNewsByGuid($object->guid);
            $detailTexts[$object->guid] = $model->text;
        }

        return $this->render('index', ['objects' => $objects, 'texts' => $detailTexts]);
    }

    /**
     * Displays detail RBC news page.
     *
     * @param string $guid
     * @return string
     */
    public function actionRbcDetail(string $guid): string
    {
        $model = $this->RBCService->getNewsByGuid($guid);

        return $this->render('detail', ['model' => $model]);
    }
}
