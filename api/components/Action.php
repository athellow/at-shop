<?php
/**
 * Api 公共基础动作类
 * @author Athellow
 * @version 1.0 (2023-05-09 11:51:29)
 */
namespace api\components;

use Yii;
use yii\helpers\ArrayHelper;
use common\exceptions\SystemErrorException;
use common\helpers\ErrorCode;

abstract class Action extends \common\components\Action
{
    /**
     * @var array 输入参数
     */
    protected $params = [];

    /**
     * @var bool checkSign 是否验证签名
     */
    protected $isCheckSign = true;

    /**
     * 业务逻辑处理方法
     */
    abstract protected function handleAction();

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if (YII_ENV == 'dev') {
            $this->setCheckSign(false);
        }

        parent::init();
    }

    /**
     * 设置是否验证签名
     * @param $isCheckSign 是否验证签名，默认是
     */
    public function setCheckSign($check = true)
    {
        $this->isCheckSign = $check;
    }

    /**
     * 验证签名
     * @return bool
     * @throws SystemErrorException
     */
    protected function checkSign()
    {
        if (!$this->isCheckSign) {
            return true;
        }

        $request = Yii::$app->request;
        $params = array_merge($request->getQueryParams(), $request->getBodyParams());

        $inputSign = $params['sign'] ?? '';
        if (empty($inputSign)) {
            throw new SystemErrorException('参数异常');
        }

        unset($params['sign']);
        
        // TODO 生成签名
        $sign = ''; // doSign($params);

        if ($sign != $inputSign) {
            throw new SystemErrorException('非法请求');
        }

        return true;
    }

    /**
     * 初始化请求参数
     * @return array 请求输入参数
     */
    protected function initParams()
    {
        return [];
    }

    /**
     * 当操作被请求时，控制器将调用该方法
     */
    public function run()
    {
        $resp = [
            'code' => 0,
            'message' => '',
            'data' => new \stdClass
        ];

        try {
            $this->checkSign();
            $this->params = ArrayHelper::merge($this->params, $this->initParams());

            // 处理业务逻辑
            $resp['data'] = $this->handleAction();
        } catch (\Exception $e) {
            $code = $e->getCode() ? $e->getCode() : ErrorCode::CODE_SYS_ERROR;
            $msg = $e->getMessage();

            $resp['code'] = $code;
            $resp['message'] = $msg;

            // Yii::warning([
            //     'code' => $code,
            //     'message' => $msg,
            //     'class' => get_class($this),
            // ]);
        }
        
        // 已在main.php中配置 response
        // Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        // Yii::$app->response->statusCode = 200;
        // return $this->controller->asJson($resp);

        return $resp;
    }

}
