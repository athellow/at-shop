<?php
/**
 * 公共基础 Service 类
 * @author Athellow
 * @version 1.0
 */
namespace common\services;

use Yii;
use common\helpers\ErrorCode;

class BaseService
{
    /**
     * @var array 错误信息
     */
    private static $_errors;

    /**
     * 添加新的错误并返回 false
     * @param string $msg 新的错误信息
     * @param int $code 错误码
     * @param array $data 数据
     * @return bool 返回false
     */
    public static function error($msg = '数据异常', $code = ErrorCode::CODE_PARAM_ERROR)
    {
        self::$_errors[] = [
            'msg' => $msg, 'code' => $code
        ];

        return false;
    }

    /**
     * 返回所有错误
     * @return array 所有的错误。如果没有错误，则返回空数组。
     */
    public static function getErrors()
    {
        return self::$_errors === null ? [] : self::$_errors;
    }

    /**
     * 返回第一个错误
     * @param string|null $defaultMsg 默认错误信息
     * @param int|null $defaultCode 默认错误码
     * @return array 错误信息
     */
    public static function getFirstError($defaultMsg = '系统异常', $defaultCode = ErrorCode::CODE_SYS_ERROR)
    {
        if (empty(self::$_errors)) {
            return [$defaultMsg, $defaultCode];
        } else {
            $err = reset(self::$_errors);
            return [$err['msg'], $err['code']];
        }
    }

}