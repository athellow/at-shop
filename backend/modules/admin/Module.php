<?php
/**
 * 后台模块
 * @author Athellow
 * @version 1.0
 */
namespace backend\modules\admin;

use Yii;

class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\admin\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        Yii::$app->view->title = 'Athellow 后台管理系统';
        
        parent::init();

    }

}