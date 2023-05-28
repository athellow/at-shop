<?php
/**
 * System message controller
 * @author Athellow
 * @version 1.0
 */
namespace backend\modules\admin\controllers;

use Yii;
use backend\controllers\BaseController;

class SystemMessageController extends BaseController
{
    /**
     * 消息数量
     */
    public function actionNum()
    {
        return $this->success('获取成功', ['num' => 119]);
    }
}