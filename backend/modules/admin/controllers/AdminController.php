<?php
/**
 * Admin controller
 * @author Athellow
 * @version 1.0
 */
namespace backend\modules\admin\controllers;

use Yii;
use backend\controllers\BaseController;

class AdminController extends BaseController
{
    /**
     * 管理员列表
     * 
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index.html', array_merge($this->params, ['content' => 'aaaa', 'page' => ['title' => 'aasd']]));
    }

    /**
     * 编辑管理员信息
     * 
     * @return string|Response
     */
    public function actionEdit()
    {
        $admin = Yii::$app->user->identity;
        $admininfo = $admin->attributes;
        unset($admininfo['password_hash'], $admininfo['password_reset_token'], $admininfo['auth_key']);
        unset($admininfo['verification_token'], $admininfo['token']);
        
        return $this->render('edit.html', array_merge($this->params, [
            'admin' => $admininfo
        ]));
    }
    
}