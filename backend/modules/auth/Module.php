<?php
/**
 * 认证模块
 * @author Athellow
 * @version 1.0
 */
namespace backend\modules\auth;

use Yii;

class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\auth\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

    }

}
