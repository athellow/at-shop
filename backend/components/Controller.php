<?php
/**
 * 后台公共基础Controller类
 * @author Athellow
 * @version 1.0
 */
namespace backend\components;

use Yii;
use yii\web\Response;
use common\helpers\ErrorCode;
use common\helpers\Url;

class Controller extends \common\components\Controller
{
    /**
     * @var bool whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[\yii\web\Request::enableCsrfValidation]] are true.
     */
    public $enableCsrfValidation = false;

    /**
     * @var array 无需认证的Action
     */
    protected $defaultNoAuthAction = ['captcha'];

    /**
     * @var array 无需认证的Action
     */
    protected $noAuthAction = [];

    /**
     * 输入参数
     */
    protected $params = null;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // 视图公共参数
        $this->params = [
            'env'               => YII_ENV,
            'enablePrettyUrl'   => Yii::$app->urlManager->enablePrettyUrl ? true : false,
            'successCode'       => ErrorCode::CODE_SUCCESS,
            'homeUrl'           => Url::build(Yii::$app->defaultRoute),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'captcha' => [
                'class'             => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode'   => YII_ENV_TEST ? 'atcode' : null,
                'maxLength'         => 4,
                'minLength'         => 4,
                'width'             => 108,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {
        // your custom code here, if you want the code to run before action filters,
        // which are triggered on the [[EVENT_BEFORE_ACTION]] event, e.g. PageCache or AccessControl

        if(!$this->checkAccess($action)) {
            return false;
        }
        
        if (!parent::beforeAction($action)) {
            return false;
        }

        // other custom code here
        
        return true; // or false to not run the action
    }

    /**
     * 检查访问权限
     * @param \yii\base\Action $action the action to be executed.
     * @return bool
     */
    public function checkAccess($action)
    {
        return true;
    }

    /**
     * 重定向到登录页
     * @return Response 当前响应对象
     */
    public function goLogin()
    {
        return $this->response->redirect(Yii::$app->user->loginUrl ?? 'admin/login');
    }

    /**
     * 操作成功
     * @param string $msg  响应信息
     * @param array  $data 响应数据
     * @param int  $code 响应状态码
     * @return Response
     */
    public function success($msg = '操作成功', $data = [], $code = ErrorCode::CODE_SUCCESS)
    {
        return $this->asJson(['code' => $code, 'msg' => $msg, 'data' => $data ?: new \stdClass]);
    }

    /**
     * 操作失败
     * @param string $msg 提示信息
     * @param int  $code 响应状态码
     * @return Response
     */
    public function error($msg = '操作失败', $code = 400)
    {
        return $this->asJson(['code' => $code, 'msg' => $msg]);
    }

}
