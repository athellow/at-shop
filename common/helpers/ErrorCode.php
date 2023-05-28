<?php
/**
 * 错误码帮助类
 * @author Athellow
 * @version 1.0 (2023-05-08 21:44:58)
 */
namespace common\helpers;

use Yii;

class ErrorCode
{
    const CODE_SUCCESS = 0;                 // 成功
    const CODE_SYS_ERROR = 10001;           // 系统异常
    const CODE_PARAM_ERROR = 10400;         // 参数错误
    const CODE_UN_LOGIN = 10401;            // 未登录

}
