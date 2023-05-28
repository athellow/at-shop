<?php
/**
 * URL helper
 * @author Athellow
 * @version 1.0
 */
namespace common\helpers;

use Yii;

class Url
{
    /**
     * 获取url
     * @param  string    $route    路由
     * @param  array    $params    参数
     * @return string
     */
    public static function build($route, $params = [])
    {
        $queryUrl = '';
    
        $query = '';
        foreach ($params as $key => $val) {
            $query .= ('&'. $key .'='. $val);
        }
        
        $query = $query ? substr($query, 1) : '';
    
        $enablePrettyUrl = Yii::$app->urlManager->enablePrettyUrl ? true : false;

        // 是否开启路由美化
        if ($enablePrettyUrl) {
            $queryUrl = '/'. $route .'.html'. ($query ? '?'. $query : '');
        } else {
            $queryUrl =  '/index.php?r='. $route . ($query ? '&'. $query : '');
        }
    
        return $queryUrl;
    }
    
}
