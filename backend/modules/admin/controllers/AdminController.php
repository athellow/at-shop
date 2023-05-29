<?php
/**
 * Admin controller
 * @author Athellow
 * @version 1.0
 */
namespace backend\modules\admin\controllers;

use Yii;
use backend\controllers\BaseController;
use backend\models\Admin;

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
        $adminId = $admin->id;

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            
            // $admin->avatar = $post[$post['filekey'] ?? 'file'];
            
            $model = new \backend\models\AdminForm(['formType' => 'edit', 'adminId' => $adminId]);
            if ($model->load($post, '') && $model->save(true)) {
                return $this->success('登录成功');
            }
            
            $msg = '登录失败';
            if ($errors = $model->getFirstErrors()) {
                $msg = array_values($errors)[0] ?? $msg;
            }

            return $this->error($msg);
        }

        unset($admininfo['password_hash'], $admininfo['password_reset_token'], $admininfo['auth_key']);
        unset($admininfo['verification_token'], $admininfo['token']);
        
        return $this->render('edit.html', array_merge($this->params, [
            'admin' => $admininfo,
            'statusList' => Admin::$status,
        ]));
    }
    
}