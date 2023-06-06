<?php
/**
 * 模板控制器
 * @author Athellow
 * @version 1.0 (2023-06-06 23:10:48)
 */
namespace backend\modules\demo\controllers;

use Yii;
use backend\controllers\BaseController;

class DemoController extends BaseController
{
    /**
     * 登录
     * 
     * @return string|Response
     */
    public function actionForm()
    {
        return $this->render('form.html');
    }

}
