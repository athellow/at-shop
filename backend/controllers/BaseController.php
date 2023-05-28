<?php
/**
 * Base controller
 * @author Athellow
 * @version 1.0
 */
namespace backend\controllers;

use Yii;
use backend\components\Controller;

class BaseController extends Controller
{
    /**
     * 检查访问权限
     * @param \yii\base\Action $action the action to be executed.
     * @return bool
     */
    public function checkAccess($action)
    {
        if ($this->defaultNoAuthAction && in_array($action->id, $this->defaultNoAuthAction)) {
            return true;
        }
        
        // 排除无需认证的Action
        if ($this->noAuthAction && in_array($action->id, $this->noAuthAction)) {
            return true;
        }
        
        if (Yii::$app->user->isGuest) {
            $this->goLogin();
            return false;
        }

        // 模块权限控制
        if (!$this->checkPrivs($action)) {
            return false;
        }

        return true;
    }

    /**
     * 检查模块权限
     * @param $action
     */
    protected function checkPrivs($action) 
    {
        $controllerId = $action->controller->id;
        $actionId = $action->id;
        $userId = Yii::$app->user->id;

        // 判断有没有该页面访问权限
        return true;

        return false;
    }
    
}