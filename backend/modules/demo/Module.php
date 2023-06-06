<?php
/**
 * 模板
 * @author Athellow
 * @version 1.0 (2023-06-06 23:09:00)
 */
namespace backend\modules\demo;

use Yii;

class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\demo\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        Yii::$app->view->title = '模板';
        
        parent::init();

    }

}
