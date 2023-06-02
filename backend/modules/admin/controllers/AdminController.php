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
use backend\services\admin\AdminService;

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
        // $adminId = Yii::$app->user->identity->id;
		$id = intval(Yii::$app->request->get('id'));

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            
            if (AdminService::save($post)) {
                return $this->success('保存成功');
            }

            list($msg, $code) = AdminService::getFirstError('保存失败');
            
            return $this->error($msg, $code);
        }

        $admin = Admin::findOne($id)->toArray();
        $admin['avatar'] = Yii::$app->params['loacalFileDomain'] . $admin['avatar'];
        
        return $this->render('edit.html', array_merge($this->params, [
            'admin' => $admin,
            'statusList' => Admin::$status,
        ]));
    }
    
}