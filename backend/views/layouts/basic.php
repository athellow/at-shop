<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-param" content="<?= Yii::$app->request->csrfParam ?>">
    <meta name="csrf-token" content="<?= Yii::$app->request->csrfToken ?>">
    <!--remove underline for edge-->
    <meta name="format-detection" content="telephone=no">
    <title><?= $this->title ?? '后台管理系统' ?></title>

    <link href="/vendors/layui/css/layui.css" rel="stylesheet" type="text/css" />
    <link href="/vendors/layui/css/global.css" rel="stylesheet" type="text/css" />
    <link href="/vendors/font_1864554/iconfont.css" rel="stylesheet" type="text/css" />
    <link href="/vendors/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
    <link href="/static/css/admin.css" rel="stylesheet" type="text/css" />

    <script type="text/javascript">
        var ENV = "<?= YII_ENV ?>";
        var ENABLE_PRETTY_URL = "<?= Yii::$app->urlManager->enablePrettyUrl ?>";
        var SUCCESS_CODE = "<?= \common\helpers\ErrorCode::CODE_SUCCESS ?>";
        var HOME_URL = "<?= \common\helpers\Url::build(Yii::$app->defaultRoute) ?>";

        window.Tnmc = {
            debug:true
        }
    </script>

    <script type="text/javascript" src="/static/js/jquery.min.js" charset="<?= Yii::$app->charset ?>"></script>
    <script type="text/javascript" src="/static/js/jquery/jquery.form.js" charset="<?= Yii::$app->charset ?>"></script>
    <script type="text/javascript" src="/static/js/jquery/jquery.session.js" charset="<?= Yii::$app->charset ?>"></script>
    <script type="text/javascript" src="/vendors/layui/layui.js" charset="<?= Yii::$app->charset ?>"></script>
    <script type="text/javascript" src="/vendors/layui/modules/global.js" charset="<?= Yii::$app->charset ?>"></script>
    <script type="text/javascript" src="/static/js/at.js" charset="<?= Yii::$app->charset ?>"></script>
    <script type="text/javascript" src="/static/js/admin.js" charset="<?= Yii::$app->charset ?>"></script>

    <block name="head"></block>
</head>

<body class="layui-layout layui-layout-admin">

<div id="main">
    <?php include('../views/public/header.php') ?>
    <?php include('../views/public/menus.php') ?>
    <?= $content ?>
</div>







<script type="text/javascript">
    $(function() {
        layui.use(['element','form'], function() {
            var element = layui.element;
            layui.form.render();

            // 表格无数据
            if (tableIns = window.Tnmc.tableIns) {
                tableIns.config && (tableIns.config.parseData = function(res) {
                    if (res.count == 0) {
                        return {
                            'code': 201,
                            'msg': '无数据',
                            'count': 0,
                            'data': []
                        }
                    }
                });
            }
        });
    })
    
    window.Tnmc.afterRender = function() {
        layui.use(['element','form'], function() {
            var element = layui.element;
            layui.form.render();
        });
    }
</script>
</body>
</html>