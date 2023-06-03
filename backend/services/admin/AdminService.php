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
use common\helpers\Page;
use common\helpers\Request;
use common\helpers\Time;

class AdminService extends BaseService
{
    /**
     * 获取列表
     * @param  array    $params    参数
     * @param  bool     $isExport  是否是导出数据
     * @return array
     */
    public static function getList($params, $isExport = false)
    {
        $data = [];

        $query = Admin::getWhere($params)
            ->select('id, username, email, status, last_ip, last_time, login_count, avatar, created_at, updated_at')
            ->orderBy(['id' => SORT_ASC]);
        
        if (!$isExport) {
            $count = $query->count();

            $limit = Request::getLimit($params['limit'] ?? 0);

            // $pageNum = Request::getPage($params['page'] ?? 0);
            // $offset = Request::getOffset($pageNum, $limit);
            // $query->offset($offset)->limit($limit);
            
            $page = Page::getPage($count, $limit);
            $limit = $page->limit;
            $offset = $page->offset;
            $query->offset($offset)->limit($limit);

            $data['pageSize'] = $limit;
            $data['offset'] = $offset;
            $data['total'] = (int)$count;
        }
        // echo $query->createCommand()->getRawSql();exit;

        $list = $query->asArray()->all();

        foreach ($list as &$item) {
            $item['status_text'] = Admin::$statusList[$item['status']] ?? '';
            $item['created_time'] =  Time::formatTime($item['created_at']);
            $item['updated_time'] = Time::formatTime($item['updated_at']);
        }

        $data['items'] = $list;
        
        return $data;
    }

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
