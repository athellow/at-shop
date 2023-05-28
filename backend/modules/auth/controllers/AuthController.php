<?php
/**
 * Auth controller
 * @author Athellow
 * @version 1.0 (2023-05-23 09:48:09)
 */
namespace backend\modules\auth\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\captcha\CaptchaValidator;
use backend\controllers\BaseController;
use backend\models\LoginForm;

class AuthController extends BaseController
{
    /**
	 * @var array 无需认证的Action
	 */
	public $noAuthAction = ['login', 'forgot-password'];

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        // $actions = $this->buildActions([
        //     'login' => 'LoginAction',
        // ], 'auth');
        $actions = [];

        return ArrayHelper::merge(parent::actions(), $actions);
    }

    /**
     * 登录
     * 
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            
            // if (isset(Yii::$app->params['captcha']['status']) && Yii::$app->params['captcha']['status']) {
            //     $captchaValidator = new CaptchaValidator(['captchaAction' => 'auth/auth/captcha']);
            //     if (!$captchaValidator->validate($post['captcha'])) {
            //         return $this->error('验证码错误');
            //     }
            // }

            $model = new LoginForm();
            if ($model->load($post, '') && $model->login()) {
                return $this->success('登录成功');
            }
            
            $msg = '登录失败';
            if ($errors = $model->getFirstErrors()) {
                $msg = array_values($errors)[0] ?? $msg;
            }

            return $this->error($msg);
        }
        
        // $this->layout ='@app/views/layouts/basic.php';
        return $this->renderPartial('login.html', $this->params);
    }

    /**
     * 退出登录
     * 
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
        
    }
    
    /**
     * 忘记密码
     * 
     * @return string|Response
     */
    public function actionForgotPassword()
    {
        p('忘记密码');
    }
    
    /**
     * 登录
     */
    public function actionPasswordReset()
    {
        p(1113);
    }

}