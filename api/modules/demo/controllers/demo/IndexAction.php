<?php
/**
 * 认证动作
 * @author Athellow
 * @version 1.0 (2023-05-09 11:47:17)
 */
namespace api\modules\demo\controllers\demo;

use Yii;
use api\components\Action;

class IndexAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function initParams()
    {
        return [
            'initInputField' => 'initInputValue'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function handleAction()
    {
        // Your custom code here.
        
        return [
            'initInputField' => $this->params['initInputField'],
            'remask' => 'This is a demonstration',
            'success' => true
        ];
    }

}
