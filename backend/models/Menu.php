<?php
/**
 * Menu model
 * @author Athellow
 * @version 1.0
 */
namespace backend\models;

use common\helpers\Url;
use Yii;
use yii\db\ActiveRecord;

class Menu extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%menu}}';
    }

    /**
     * 获取菜单
     */
    public static function getMenus()
    {
        $menu = [
            [
                'text'      => '首页',
                'ico'		=> 'icon-home',
                'url'   => Url::build('admin/index')
            ],
            [
                'text'      => '用户中心',
                'ico' => 'icon-huiyuan',
                'children'  => [
                    'admin' => [
                        'text' => '管理员列表',
                        'url'   => Url::build('admin/admin/index'),
                        'access'  => ['key' => 'manager|all', 'label' => '管理员管理']
                    ],
                ]
            ]
        ];
        
        return $menu;
    }
}