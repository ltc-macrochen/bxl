/**
 * Created by macrochen on 2017/7/21.
 */
/******************************************************************************/
/***                                常用方法                                ***/
/******************************************************************************/
/**
 * Created by macrochen on 2017/5/8.
 */
function increaseValue(obj,value) {
    if (obj) {
        var oldValue = obj.html();
        obj.html(parseInt(oldValue) + parseInt(value));
    }
}
function decreaseValue(obj,value) {
    if (obj) {
        var oldValue = obj.html();

        if (parseInt(oldValue) < parseInt(value)) {
            obj.html(0);
        }else {
            obj.html(parseInt(oldValue) - parseInt(value));
        }
    }
}

//收藏
function addFavorite() {
    var url = window.location;
    var title = document.title;
    var ua = navigator.userAgent.toLowerCase();
    if (ua.indexOf("360se") > -1) {
        alert("由于360浏览器功能限制，请按 Ctrl+D 手动收藏！");
    }else if (ua.indexOf("msie 8") > -1) {
        window.external.AddToFavoritesBar(url, title); //IE8
    }else if (document.all) {
        try{
            window.external.addFavorite(url, title);
        }catch(e){
            alert('当前浏览器不支持，请按 Ctrl+D 手动收藏!');
        }
    }else if (window.sidebar) {
        window.sidebar.addPanel(title, url, "");
    }else {
        alert('当前浏览器不支持，请按 Ctrl+D 手动收藏!');
    }
}

//投票
sessionStorage.dolike = 0;
function doLike(id, action, obj) {
    if(sessionStorage.dolike != 0){
        return;
    }

    $.ajax({
        'type' : 'POST',
        'url' : '/web/doLike',
        'data' : {id : id, action : action},
        'cache' : false,
        'async' : true,
        'dataType' : 'json',
        success : function (ret) {
            if(ret.err == 0){
                sessionStorage.dolike = 1;
                increaseValue($(obj).find('span'), 1);
            }
        },
        error : function () {
            alert('系统繁忙，请稍后再试~~')
        }
    });
}

/******************************************************************************/
/***                              加载页面方法                              ***/
/******************************************************************************/
$(function () {
    $('.bxl-article .jiathis').on('onmouseover', function () {
        alert(1);
    })
});