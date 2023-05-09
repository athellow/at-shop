<?php
/**
 * Api 公共基础Controller类
 * @author Athellow
 * @version 1.0 (2023-05-09 09:08:11)
 */
namespace api\components;

use Yii;

class Controller extends \common\components\Controller
{
    /**
     * @var bool whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[\yii\web\Request::enableCsrfValidation]] are true.
     */
    public $enableCsrfValidation = false;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

    }

    /**
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {
        // your custom code here, if you want the code to run before action filters,
        // which are triggered on the [[EVENT_BEFORE_ACTION]] event, e.g. PageCache or AccessControl
        
        if (!parent::beforeAction($action)) {
            return false;
        }

        // other custom code here
        
        return true; // or false to not run the action
    }

}
