<?php

/** @var string $homeUrl */
$homeUrl = \common\helpers\Url::build(Yii::$app->defaultRoute);

/** @var string $avatar */
$avatar = Yii::$app->user->identity->avatar ?? '';
$avatar = !empty($avatar) ? Yii::$app->params['loacalFileDomain'] . $avatar : '/static/images/default_user_avatar.gif';

?>

<style>
    .tipnum {
        width: 10px; height: 10px; padding: 2px; background-color: red; color: #fff;
        top: -6px; right: -6px; border-radius: 100%; line-height: 10px; text-align: center;
    }
</style>

<div id="header" class="layui-header">
    <div class="breadcrumb">
        <i class="iconfont icon-zhankaimulu ml10"></i>
        <i class="iconfont icon-shuaxin ml10 mr10 pointer J_Tips" data-value="刷新" onclick="javascript:window.location.reload();"></i>
        <span class="layui-breadcrumb ml10" lay-separator="/">
            <a href="javascript:;" class="menu">首页</a>
            <a class="submenu"><cite>首页概览</cite></a>
        </span>
    </div>

    <ul class="layui-nav layui-layout-right webkit-box breadbtn">
        <li class="ml10 mr20">
            <a href="javascript:;" target="_blank" class="J_Message J_Tips relative hidden" 
                data-value="您有新的消息通知，点击查看详情">
                <ins class="absolute layui-font-12 block tipnum" >0</ins>
                <i class="layui-icon layui-icon-notice layui-font-20"></i>
            </a>
        </li>
        <li class="ml10 mr20">
            <a href="javascript:;" class="fullScreen J_Tips" data-value="全屏" onclick="fullScreen()">
                <i class="iconfont icon-fangda"></i>
            </a>
        </li>
        <li class="ml10 mr20">
            <a href="javascript:;" class="J_Tips" data-value="清理缓存" onclick="clearCache()">
                <i class="iconfont icon-qinglihuancun"></i>
            </a>
        </li>
        <li class="layui-nav-item layui-hide layui-show-md-inline-block">
            <a href="javascript:;" style="color: #333;">
                <img src="<?= $avatar ?>" width="40" height="40" style="border-radius: 50%;">
                <span class="ml5"><?= Yii::$app->user->identity->username ?></span>
            </a>
            <dl class="layui-nav-child">
                <dd>
                    <a href="<?= \common\helpers\Url::build('admin/admin/edit', ['id' => Yii::$app->user->identity->id]) ?>">个人设置</a>
                </dd>
                <dd>
                    <a href="<?= \common\helpers\Url::build('auth/auth/logout') ?>">退出登录</a>
                </dd>
            </dl>
        </li>
    </ul>

    <ul class="menutabs J_Menutabs">
        <li class="selected">
            <a class="mr5" href="<?= $homeUrl ?>">首页</a>
            <i data-id="0" class="layui-icon layui-icon-close layui-unselect layui-tab-close f-13"></i>
        </li>
    </ul>
</div>