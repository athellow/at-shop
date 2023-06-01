<?php
/**
 * Admin service
 * @author Athellow
 * @version 1.0
 */
namespace backend\services\admin;

use Yii;
use yii\web\UploadedFile;
use backend\models\Admin;
use backend\services\BaseService;

class AdminService extends BaseService
{
    /**
     * 更新
     * @param  array    $params    参数
     * @return bool
     */
    public static function save($params)
    {
        //p(Yii::$app->user->identity->id);

        if (!($id = $params['id'] ?? 0) || !($model = Admin::findOne($id))) {
            $model = new Admin();
        }
        
        $model->setPassword($params['password']);
        // 设置此属性会强制该账户登出
        // $model->generateAuthKey();

        if ($model->load($params, '') && $model->save(true)) {
            $upModel = new \common\models\UploadForm();
            if ($upModel->load(['file' => UploadedFile::getInstanceByName('avatar')], '') && $upModel->upload()) {
                $model->avatar = $upModel->path;
                $model->save();
            }

            // return $model;
            return true;
        }
        
        return self::error($model->getError('保存失败'));
    }

}
