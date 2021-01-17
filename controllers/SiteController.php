<?php

namespace app\controllers;

use Yii;
use yii\helpers\FileHelper;
use yii\web\Controller;
use app\services\RBCService;
use yii\web\NotFoundHttpException;

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
        //$objects = $this->RBCService->importNews(); //TODO: перенести $this->RBCService->importNews() в commands и настроить запуск по крону,
                                                    //заменить на сл. строку после настройки крона
        $objects = $this->RBCService->getLimit("app\services\RBCService", 15);

        return $this->render('index', ['objects' => $objects]);
    }

    /**
     * Displays detail RBC news page.
     *
     * @param string $guid
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionRbcDetail(string $guid): string
    {
        $dto = $this->RBCService->getNewsByGuid($guid);
        if (!$dto) {
            throw new NotFoundHttpException('Page not found.');
        }
        $filePath = Yii::getAlias('@webroot/images/'.$guid);
        if (is_dir($filePath)) {
            $images = FileHelper::findFiles($filePath);
        } else {
            $images = [];
        }
        $imageFiles = [];
        foreach ($images as $image) {
            $imageFiles[] = preg_replace("/^.*?(\/images\/[\w\d]+?\/[\wА-Яа-я\d\-_\s\.]+?)$/u", "\\1", str_replace("\\", "/", $image));
        }

        return $this->render('detail', ['dto' => $dto, 'images' => $imageFiles]);
    }
}
