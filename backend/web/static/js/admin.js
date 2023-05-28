$(function () {
    /* 全选 */
    $('.checkall').click(function () {
        $('.checkitem').prop('checked', this.checked);
    });

    $('body').on('click', '.J_BatchDel', function () {
        if ($('.checkitem:checked').length == 0) {
            layer.msg('没有选择项');
            return false;
        }
        // 获取选中项 
        var items = '';
        $('.checkitem:checked').each(function () {
            items += this.value + ',';
        });
        items = items.substr(0, (items.length - 1));
        var uri = $(this).attr('uri');
        uri = uri + ((uri.indexOf('?') != -1) ? '&' : '?') + $(this).attr('name') + '=' + items;
        ajaxRequest($(this), uri);
    });

    // 文字悬浮提示
    $('.J_Tips').on('mouseover', function () {
        layer.tips($(this).attr('data-value'), this, {
            tips: [3, '#0d6fb8'], // 1-上 2-右 3-下 4-左
        });
    });

    // 每10秒查询系统消息数量
    setInterval(systemMessgae, 10000);
});

/**
 * 系统消息
 */
function systemMessgae() {
    var url = buildUrl('admin/system-message/num');

    $.ajax({
        async : true,
        url : url,
        type : "GET",
        dataType : "json",
        data : {},
        success: function(data) {
            log(url, data);
            var megNum = data.data.num || 0;
            if (data.code == SUCCESS_CODE && megNum > 0) {
              $('.tipnum').text(megNum >= 10 ? '...' : megNum);
              $('.J_Message').attr('data-value', '您有'+ megNum +'条新的消息，点击查看详情')
              $('.J_Message').show();
            }
        }
    });
}

/**
 * 清理缓存
 */
function clearCache() {
    var url = buildUrl('admin/index/clear-cache');

    $.getJSON(url, function (data) {
        layer.msg(data.msg);
    });
}

/**
 * 全屏
 */
function fullScreen() {
    var el = document.documentElement;  // target兼容Firefox
    var isFullscreen = document.fullScreen || document.mozFullScreen || document.webkitIsFullScreen;
    var o = $('body').find('.fullScreen');
    if (!isFullscreen) {                // 进入全屏,多重短路表达式
        (el.requestFullscreen && el.requestFullscreen()) ||
            (el.mozRequestFullScreen && el.mozRequestFullScreen()) ||
            (el.webkitRequestFullscreen && el.webkitRequestFullscreen()) || (el.msRequestFullscreen && el.msRequestFullscreen());

        o.find('i').removeClass('icon-fangda').addClass('icon-suoxiao');
        o.attr('data-value', '缩小');
    } else {                            // 退出全屏,三目运算符
        document.exitFullscreen ? document.exitFullscreen() :
            document.mozCancelFullScreen ? document.mozCancelFullScreen() :
                document.webkitExitFullscreen ? document.webkitExitFullscreen() : '';

        o.find('i').addClass('icon-fangda').removeClass('icon-suoxiao');
        o.attr('data-value', '全屏');
    }
}

/**
 * 上传图片前，显示（获取）图像信息
 * @param {obj} obj 
 */
function getTempPathcallback(obj) {
    getTempPath(obj, function (res) {
        var imgObj = $(obj).parent().find('.type-file-image');
        if (imgObj.find('img').length > 0) {
            imgObj.find('img').attr('src', res);
        } else {
            imgObj.html('<img class="block" src="' + res + '"><span>修改图片</span>');
        }
    });
}
