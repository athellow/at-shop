<?php
/**
 * 公共基础 Model 类
 * @author Athellow
 * @version 1.0
 */
namespace common\components;

use Yii;

class Model extends \yii\base\Model
{
    /**
     * 获取第一条错误信息
     * @param  string    $defaultMsg    默认信息
     * @param  string    $attribute     属性
     * @return string|null the error message. Null is returned if no error.
     */
    public function getError($defaultMsg = '', $attribute = '')
    {
        if ($attribute) {
            $error = $this->getFirstError($attribute);
        } else {
            $errors = $this->getErrorSummary(false);
            $error = empty($errors) ? $defaultMsg : $errors[0];
        }

        return $error;
    }

}