<?php
/**
 * 示例模块
 * @author Athellow
 * @version 1.0 (2023-05-08 20:40:43)
 */
namespace api\modules\demo\controllers;

use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use api\components\Controller;

class DemoController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'authenticator' => [
                'class' => QueryParamAuth::class,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return $this->buildActions([
            'index' => 'IndexAction',
        ], 'demo');
    }

}
