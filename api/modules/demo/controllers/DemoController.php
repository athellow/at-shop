<?php
/**
 * 示例模块
 * @author Athellow
 * @version 1.0 (2023-05-08 20:40:43)
 */
namespace api\modules\demo\controllers;

use Yii;
use api\components\Controller;

class DemoController extends Controller
{
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
