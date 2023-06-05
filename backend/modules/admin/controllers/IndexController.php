<?php
/**
 * Index controller
 * @author Athellow
 * @version 1.0
 */
namespace backend\modules\admin\controllers;

use Yii;
use backend\controllers\BaseController;

class IndexController extends BaseController
{
    /**
     * 首页
     * 
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index.html', ['content' => 'aaaa', 'page' => ['title' => 'aasd']]);
    }
    
    /**
     * 清理缓存
     */
    public function actionClearCache()
    {
        $this->success('清理缓存成功');
    }

}