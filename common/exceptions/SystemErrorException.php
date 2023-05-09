<?php
/**
 * 系统异常类
 * @author Athellow
 * @version 1.0 (2023-05-08 21:43:26)
 */
namespace common\exceptions;

use Yii;
use common\helpers\ErrorCode;

class SystemErrorException extends \Exception
{
    /**
     * construct
     * @param string $message  错误消息
     * @param int    $code     错误码
     * @param Throwable|null $previous 先前用于异常链的可抛出对象
     */
    public function __construct($message = 'System Error', $code = ErrorCode::CODE_SYS_ERROR, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    
}
