<?php
/**
 * 公共基础Controller类
 * @author Athellow
 * @version 1.0 (2023-05-09 15:00:44)
 */
namespace common\components;

use Yii;

class Controller extends \yii\web\Controller
{
    /**
     * 构建控制器的外部动作
     * @param  array  $actions   动作列表
     * @param  string $actionDir 动作目录
     * @return array
     */
    public function buildActions($actions, $actionDir)
    {
        $namespace = $this->module->controllerNamespace .'\\'. $actionDir;

        $newActions = array_map(function($item) use ($namespace) {
            return $namespace . '\\' . $item;
        }, $actions);
        
        return $newActions;
    }
    
}
