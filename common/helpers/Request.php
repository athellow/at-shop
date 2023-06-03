<?php
/**
 * Request helper
 * @author Athellow
 * @version 1.0
 */
namespace common\helpers;

use Yii;

class Request
{
    /**
     * 获取输入参数
     * @param  string $name 键
     * @param  mixed  $defaultValue 默认值
     * @param  array  $methods 请求方式
     * @return mixed
     */
    public static function input($name = null, $defaultValue = null, $methods = ['GET', 'POST'])
    {
        $request = Yii::$app->request;
        $value = ($name === null) ? [] : null;

        if (in_array('GET', $methods)) {
            $value = $request->get($name, $defaultValue);
        }
        if (in_array('POST', $methods)) {
            if ($name === null) {
                $value = array_merge($value, $request->post());
            } else {
                $value = $request->post($name, !empty($value) ? $value : $defaultValue);
            }
        }

        return $value;
    }

    /**
     * 获取分页页码
     * @param  int  $page
     * @return int
     */
    public static function getPage($page = 0)
    {
        if (empty($page)) {
            if (method_exists(Yii::$app->request, 'post')) {
                $page = Yii::$app->request->post('page');
            }
    
            if (empty($page) && method_exists(Yii::$app->request, 'get')) {
                $page = Yii::$app->request->get('page');
            }
        }

        return $page ?: 1;
    }

    /**
     * 获取分页每页大小
     * @param  int  $limit
     * @return int
     */
    public static function getLimit($limit = 0)
    {
        if (empty($limit)) {
            if (method_exists(Yii::$app->request, 'post')) {
                $limit = Yii::$app->request->post('limit');
            }
    
            if (empty($limit) && method_exists(Yii::$app->request, 'get')) {
                $limit = Yii::$app->request->get('limit');
            }
        }
        
        return $limit ?: 10;
    }

    /**
     * 获取分页偏移量
     * @param  int  $page  
     * @param  int  $limit 
     * @return int             
     */
    public static function getOffset($page = 0,  $limit = 0)
    {
        $page = self::getPage($page);
        $page = ($page - 1) < 0 ? 0 : $page -1 ;

        return $page * self::getLimit($limit);
    }


}
