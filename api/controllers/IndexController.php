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
        $exception = Yii::$app->getErrorHandler()->exception;
        if (empty($exception)) {
            throw new SystemErrorException('Not Found.');
        }

        $code = $exception->getCode() ? $exception->getCode() : ErrorCode::CODE_SYS_ERROR;
        throw new SystemErrorException($exception->getMessage(), $code);
    }

}
