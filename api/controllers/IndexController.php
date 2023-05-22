<?php
/**
 * Index Controller
 * @author Athellow
 * @version 1.0 (2023-05-08 21:24:02)
 */
namespace api\controllers;

use Yii;
use yii\web\Controller;
use common\exceptions\SystemErrorException;
use common\helpers\ErrorCode;

class IndexController extends Controller
{
    /**
     * Error handler action.
     * @return void
     */
    public function actionError()
    {
        Yii::$app->response->statusCode = 200;
        
        $exception = Yii::$app->getErrorHandler()->exception;
        if (empty($exception)) {
            // throw new SystemErrorException('Not Found.');
            return $this->asJson([
                'code' => ErrorCode::CODE_SYS_ERROR,
                'message' => '系统错误',
                'data' => new \stdClass
            ]);
        }
        
        $code = $exception->getCode() ? $exception->getCode() : ErrorCode::CODE_SYS_ERROR;
        // $statusCode = $exception->statusCode;
        // throw new SystemErrorException($exception->getMessage(), $code);

        return $this->asJson([
            'code' => $code,
            'message' => $exception->getMessage(),
            'data' => new \stdClass
        ]);
    }

}
