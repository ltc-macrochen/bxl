/**
 * Created by macrochen on 2017/7/21.
 */

var g_default_tip = '驴子被踢了，请稍后再试~';
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
    if(sessionStorage.dolike >= 100){
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
                sessionStorage.dolike = parseInt(sessionStorage.dolike) + 1;
                increaseValue($(obj).find('span'), 1);
            }
        },
        error : function () {
            alert(g_default_tip)
        }
    });
}

//投稿
function submitHappy() {
    var pic = $('#bxl-newadd-area input[name=uploadPic]').val();
    var content = $('#bxl-newadd-area textarea').val();
    if(pic == '' && $.trim(content).length == 0){
        alert('请上传一张好玩的图片或者用文字描述搞笑好玩的事儿');
        return;
    }

    $.ajax({
        'type' : 'POST',
        'url' : '/web/submitHappy',
        'data' : {pic : pic, content : content},
        'cache' : false,
        'async' : true,
        'dataType' : 'json',
        success : function (ret) {
            alert(ret.msg);
            if(ret.err == 0){
                //清空输入框
                $('#bxl-newadd-area textarea').val('');
                $('#bxl-newadd-area .upload-pic-result').remove();
                $('#bxl-newadd-area input[name=uploadPic]').val('');
                $('#bxl-newadd-area .bxl-newadd-limit span').text(300);
            }
        },
        error : function () {
            alert(g_default_tip)
        }
    });
}

//审稿
function doReview(id, action) {
    $.ajax({
        'type' : 'POST',
        'url' : '/web/doReview',
        'data' : {id:id, action:action},
        'cache' : false,
        'async' : true,
        'dataType' : 'json',
        success : function (ret) {
            if(ret.err == 0){
                window.location.reload();
            }
        },
        error : function () {
            alert(g_default_tip)
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

    //投稿
    $('#bxl-newadd-area input[type=submit]').on('click', function () {
        submitHappy();
    })

    //图片延时加载
    $("img.lazy").lazyload({effect: "fadeIn",threshold :180});
});