<?php

/** @var array $menus */
$menus = \backend\models\Menu::getMenus();
/** @var string $curUrl */
$curUrl = urldecode(Yii::$app->request->getUrl());

?>

<div id="leftMenus">
    <div class="layui-side layui-bg-black">
        <div class="layui-side-scroll">
            <div class="logo">
                <a class="block center webkit-box white layui-font-18 box-align-center" title="<?= Yii::$app->params['site']['title'] ?>">
                    <img class="inline-block" style="border-radius: 6px;" src="/static/images/backendlogo.png" height="32">
                    <span class="white" style="vertical-align: middle;">
                        <?= Yii::$app->params['site']['name'] ?? 'Athellow' ?>
                    </span>
                </a>
            </div>
            <ul class="layui-nav layui-nav-tree" lay-bar="disabled" lay-accordion="true">
                <?php foreach ($menus as $menu) { ?>
                    <li class="layui-nav-item">
                        <!-- 一级菜单 -->
                        <?php if (empty($menu['url'])) { ?>
                            <a href="javascript:;"><i class="iconfont <?= $menu['ico'] ?>"></i><span><?= $menu['text'] ?></span></a>
                        <?php } else { ?>
                            <dl class="<?php if ($curUrl == $menu['url']) { ?>layui-this<?php } ?>">
                                <a href="<?= $menu['url'] ?>"><i class="iconfont <?= $menu['ico'] ?>"></i><span><?= $menu['text'] ?></span></a>
                            </dl>
                        <?php } ?>
                        
                        <?php if (!empty($menu['children'])) { ?>
                        <!-- 二级菜单 -->
                        <dl class="layui-nav-child">
                            <?php foreach ($menu['children'] as $secondMenu) { ?>
                                <dd class="<?php if ($curUrl == $secondMenu['url']) { ?>layui-this<?php } ?>">
                                    <a href="<?= $secondMenu['url'] ?>"><?= $secondMenu['text'] ?></a>
                                </dd>
                            <?php } ?>
                        </dl>
                        <?php } ?>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function () {
    // $.session.clear();
    var curmenu = $('#leftMenus').find('.layui-this');
    
    if (curmenu.length > 0) {   // 当前选中
        curmenu.parents('.layui-nav-item').addClass('layui-nav-itemed');

        var menu = {
            name: curmenu.parents('.layui-nav-item').find('a span').text(),
            child: { name: curmenu.find('a').text(), url: window.location.href }
        };
        var session = $.session.get('adminmenus');
        var menus = session ? JSON.parse(session) : [];
        if (menus.length > 10) {
            // 从数组开头删除一个元素
            menus.splice(0, 1);
        }

        var searched = false;
        $.each(menus, function (i, item) {
            if (item.child.url == window.location.href) {
                searched = true;
            }
        });
        if (searched === false) {
            menus.push(menu);
        }

        // 用于头部tab展示
        $.session.set('adminmenus', JSON.stringify(menus));
        $.session.set('lastadminmenu', JSON.stringify(menu));
log('menu', menu);
log('menus', menus);
        // TAB导航
        setTabs(menus);
        // 面包片导航
        setBreadcrumb(menu);
    } else {    // 当前没有选中则定位到最后一次定位的栏目
        if ((session = $.session.get('lastadminmenu'))) {
            var menu = JSON.parse(session);
log('lastadminmenu', menu);
            // window.location = menu.child.url;

            $('#leftMenus .layui-nav-item').find('dl dd a').each(function (i, item) {
                if ((menu.child.url).indexOf($(item).attr('href')) > -1) {
                    $(item).parents('.layui-nav-item').addClass('layui-nav-itemed');
                    $(item).parent().addClass('layui-this');
                }
            });

            // TAB导航
            setTabs(null, menu.child.url);
            // 面包片导航
            setBreadcrumb(menu);
        }
    }

    // 删除某个tab导航
    $('.J_Menutabs').on('click', '.layui-tab-close', function () {
        var index = $(this).attr('data-id');
        var adminmenus = JSON.parse($.session.get('adminmenus'));
        adminmenus.splice(index, 1);
        // 更新session
        $.session.set('adminmenus', JSON.stringify(adminmenus));
        
        // 重定向到最后一个tab
        if ((lastmenu = adminmenus.slice(-1)).length > 0) {
            window.location = lastmenu[0].child.url;
        }
    });

    $('.layui-side-scroll').scrollTop(-100);
});

/** 设置tab导航 */
function setTabs(adminmenus, curUrl) {
    if (!adminmenus) {
        adminmenus = JSON.parse($.session.get('adminmenus'));
    }

    if (!curUrl) curUrl = window.location.href;
    
    var html = '';
    $.each(adminmenus, function (i, item) {
        // 选中
        var selected = (item.child.url == curUrl) ? 'class="selected"' : '';
        html += '<li ' + selected + '>';
        html += '<a class="mr5" href="' + item.child.url + '">' + item.child.name + '</a>';
        html += '<i data-id=' + i + ' class="layui-icon layui-icon-close layui-unselect layui-tab-close f-13"></i>';
        html += '</li>';
    });

    if (html) $('.J_Menutabs').html(html);
}

/** 设置面包片导航 */
function setBreadcrumb(menu) {
    if (!menu) {
        menu = JSON.parse($.session.get('lastadminmenu'));
    }
log('setBreadcrumb', menu);
    $('#header').find('.layui-breadcrumb .menu').text(menu.name);
    $('#header').find('.layui-breadcrumb .submenu cite').text(menu.child.name);
}
</script>