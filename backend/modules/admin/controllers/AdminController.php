<?php
/**
 * Admin controller
 * @author Athellow
 * @version 1.0
 */
namespace backend\modules\admin\controllers;

use Yii;
use yii\web\Response;
use backend\controllers\BaseController;
use backend\models\Admin;
use backend\services\admin\AdminService;
use common\helpers\Page;
use common\helpers\Request;
use common\helpers\Url;

class AdminController extends BaseController
{
    /**
     * 管理员列表
     * 
     * @return string
     */
    public function actionIndex()
    {
        $params = Yii::$app->request->get();

        if (Yii::$app->request->isAjax) {
            $data = AdminService::getList($params);
            
            return $this->success('获取成功', $data['items'], $data['total']); 
        }

        return $this->render('index.html', array_merge($this->params, [
            'statusList' => Admin::$statusList,
            'addUrl' => Url::build('admin/admin/add'),
            'page' => ['title' => 'aasd']
        ]));
    }

    /**
     * 新增管理员信息
     * 
     * @return string|Response
     */
    public function actionAdd()
    {
        // $adminId = Yii::$app->user->identity->id;

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            
            if (AdminService::save($post)) {
                return $this->success('保存成功');
            }

            list($msg, $code) = AdminService::getFirstError('保存失败');
            
            return $this->error($msg, $code);
        }

        return $this->render('edit.html', array_merge($this->params, [
            'statusList' => Admin::$statusList,
        ]));
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
            'statusList' => Admin::$statusList,
        ]));
    }

    /**
     * 删除
     * @param  int    $param    参数
     * @return Response
     */
    public function actionDel()
    {
        $id = Request::input('id', 0);

        if (empty($id)) {
            return $this->error('请选中删除的数据');
        }

        if (is_string($id)) {
            $id = explode(',', $id);
        }

        return $this->success('删除成功', $id);
    }

    /**
     * 导出数据
     */
    public function actionExport()
    {
        $params = $this->input;

        return AdminService::export($params);	
    }

}