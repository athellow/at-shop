$(function () {
	$.fn.serializeJson = function () {
		var serializeObj = {};
		$(this.serializeArray()).each(function () {
			// use condition for url route disable
			if (this.name != 'r') serializeObj[this.name] = this.value;
		});
		return serializeObj;
	};

    /* 表单提交 */
    $('body').on('click', '.J_AjaxSubmit', function () {
        var method = ($(this).parents('form').attr('method') || 'POST').toUpperCase();
        var url = window.location.href;
        var confirm = $(this).attr('confirm');

        if (confirm) {
            layer.confirm(confirm, {title: '确认操作'}, function (index) {
                layer.close(index);
                ajaxSubmit(method, url, $(this));
            }, function (index) {
                layer.close(index);
                return false;
            });

            return false;
        } else {
            $(this).prop('disabled', true);
            ajaxSubmit(method, url, $(this));
        }

        return false;
    });

});


/**
 * ajax异步通用请求
 * @var method      请求方式
 * @var url         请求地址
 * @var clickObj    按键对象
 * @var callback    回调函授
 */
function ajaxSubmit(method, url, clickObj, callback) {
    if ((clickObj == null) || (clickObj == undefined)) clickObj = $('<input></input>');
    
    // 重定向属性，表单提交成功后重定向
    var retUrl = clickObj.attr('redirect');
    // 失败回调，表单提交失败后回调
    var failCallback = clickObj.attr('fail-callback');
    // 表单对象
    var formObj = clickObj.parents('form');
    if (formObj == undefined) formObj = $('<form></form>');
    
    if (url) url = strReplace(url, '&amp;', '&');

    formObj.ajaxSubmit({
        type: method,
        url: url,
        async: false,
        cache: false,
        dataType: "json",
        beforeSubmit: function () {
            // return formObj.valid();
        },
        success: function (data) {
            log(url, data);

            if (data.code == SUCCESS_CODE) {
                // 回调
                if (typeof callback == 'function') {
                    return callback();
                }

                // 默认刷新当前页
                var redirect = 'reload';

                if ($.inArray(data.redirect, [undefined, '']) < 0) {
                    redirect = strReplace(data.redirect, '&amp;', '&');
                } else if ($.inArray(retUrl, [undefined, '']) < 0) {
                    redirect = strReplace(retUrl, '&amp;', '&');
                }

                layer.msg(data.msg, {
                    time: 2000, 
                    offset: '80px',     // top
                    icon: 1, 
                    end: function () {
                        go(redirect);
                    }
                });
            } else {
                // null不做操作
                var redirect = null;

                // 定义且不为空
                if ($.inArray(data.redirect, [undefined, '']) < 0) {
                    redirect = strReplace(data.redirect, '&amp;', '&');
                }

                layer.msg(data.msg, {
                    time: 2000, 
                    offset: '80px',
                    icon: 2, 
                    end: function () {
                        if (typeof failCallback == 'function') {
                            failCallback();
                        } else if (typeof failCallback == 'string' && failCallback != '') {
                            eval(failCallback);
                        } else {
                            go(redirect);
                        }
                    }
                });
            }
        },
        error: function (data) {
            layer.msg('请求异常', {
                offset: '40px',
                icon: 1
            });
        },
        complete: function (data) {
            clickObj.prop('disabled', false);
        }
    });
}

function strReplace(str, s, r) {
    if (typeof str != 'string') return str;

    // g global, 全部替换
    var reg = new RegExp(s, "g");

    return str.replace(reg, r);
}

function go(redirect) {
    if (redirect == 'reload') {
        window.location.reload();
    } else if (redirect == 'back') {
        window.history.go(-1);
    } else if (typeof redirect == 'string') {
        window.location = redirect;
    } else {
    }
}

// 格式化后的地址
function buildUrl(route, params) {
    var queryUrl = '';
    var params = (typeof params != 'undefined') ? params : {};
    
    var query = '';
    for (var i in params) {
        query += '&' + i + '=' + params[i];
    }
    query = query && query.substring(1);

    // 是否开启路由美化
    if (ENABLE_PRETTY_URL) {
        queryUrl = '/' + route + '' + (query ? '?' + query : '');
    } else {
        queryUrl =  '/index.php?r=' + route + (query ? '&' + query : '');
    }

    return queryUrl;
}

/* 获取当前模块及控制器下的action路由 */
function getCulRoute(action) {
    var parseUrl = layui.url();
    var pathname = [];

    if (ENABLE_PRETTY_URL) {
        pathname = parseUrl.pathname || pathname;
    } else {
        var r = parseUrl.search.r || '';
        pathname = r && r.split('/');
    }

    action = action || (pathname[2] || 'index');

    return (pathname[0] || 'index') + '/' + (pathname[1] || 'index') + '/' + action;
}

function log(...params) {
    if (ENV != 'prod') {
        console.log(...params)
    }
}
