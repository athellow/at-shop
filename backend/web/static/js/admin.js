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

// 获取图片上传本地的地址路径，兼容移动端浏览器
function getTempPath(obj, callback) {
    if (window.FileReader) {
        // var ext = obj.value.substring(obj.value.lastIndexOf(".") + 1).toLowerCase();
        var file = obj.files[0];
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function (e) {
            if (typeof callback === "function") {
                return callback(reader.result);
            }
            $(obj).parent().append("<img src='" + reader.result + "' />");
        }
    }
}

layui.use(['table'], function() {
    var table = layui.table;

    // 监听工具栏事件
    table.on('toolbar(main)', function (obj) {
        var btnObj = $('[lay-event="' + obj.event + '"]');
        var primaryKey = btnObj.data('primary-key') || 'id';
        var checkStatus = table.checkStatus(obj.config.id);
        var items = new Array();

        $.each(checkStatus.data, function (i, item) {
            items.push(item[primaryKey]);
        });
        
        if (obj.event === 'batch-del') {
            if (items.length <= 0) {
                layer.msg('未选择', {time: 2000});

                return false;
            }
            
            if (!btnObj.attr('confirm')) {
                btnObj.attr('confirm', '删除后将不能恢复，确认删除选中的 ' + items.length + ' 条记录吗？');
            }
            
            del(btnObj, {id: items.join(',')});
        } else if (obj.event === 'export') {
            var p = $.extend(window.Tnmc.getUrlFormArgs(), {id: items.join(',')});

            window.location.href = buildUrl(getCulRoute('export'), p);
        }
    });

    
    //监听行工具事件
    table.on('tool(main)', function (obj) {
        var data = obj.data;
        var btnObj = $(obj.tr).find('[lay-event="' + obj.event + '"]');
        var url = btnObj.data('url') || '';

        if (obj.event === 'del') {
            del(btnObj, {id: data.id});
        } else if (obj.event === 'edit') {
            url = url || buildUrl(getCulRoute('edit'), {id: data.id});

            window.location.href = url;
        } else if (obj.event === 'enter') {
            url = url || buildUrl(getCulRoute('enter'), {id: data.id});
            
            window.location.href = url;
        }
    });
});

function del(btnObj, params, url) {
    var url = url || btnObj.data('url') || buildUrl(getCulRoute('del'));
    var confirm = btnObj.attr('confirm') || '删除后将不能恢复，确认删除该条记录吗？';

    btnObj.attr('confirm', confirm);

    log(url, params);
            
    layer.confirm(confirm, function(index) {
        var index = layer.load(0, {
            shade: false
        }); // 0代表加载的风格，支持0-2

        window.Tnmc.ajax(url, 'POST', params, function(response) {
            layer.close(index)
            if (response.code === 0 || response.code==200) {
                layer.msg(response.message || response.msg || '操作成功', {
                    offset: '80px',
                    time: 4000,
                    icon: 1, 
                    end: function () {
                        table.reload('main-table');
                    }
                });
                
            } else {
                layer.msg(response.message || response.msg || '操作失败', {
                    offset: '80px',
                    time: 4000,
                    icon: 2
                });
            }
        });
    });
}