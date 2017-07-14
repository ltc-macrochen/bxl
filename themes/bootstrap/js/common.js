// Custom scripts
$(document).ready(function () {
    // MetsiMenu
    $('#side-menu').metisMenu();
    
    // minimalize menu
    $('.navbar-minimalize').click(function () {
        $("body").toggleClass("mini-navbar");
        SmoothlyMenu();
    });
    
    // Initialize slimscroll for sidebar
    if ($("body").hasClass('fixed-sidebar')) {
        $('.sidebar-collapse').slimScroll({
            height: '100%',
            railOpacity: 0.9
        });
    }
    $('.full-height-scroll').slimscroll({
        height: '100%'
    });    
    
    // Full height of sidebar
    function fix_height() {
        //if ($("body").hasClass('fixed-sidebar')) {
            var heightWithoutNavbar = $("body > #wrapper").height() - 61;
            $(".sidebard-panel").css("min-height", heightWithoutNavbar + "px");

            var navbarHeigh = $('nav.navbar-default').height();
            var wrapperHeigh = $('#page-wrapper').height();

            if (navbarHeigh > wrapperHeigh) {
                $('#page-wrapper').css("min-height", navbarHeigh + "px");
            }

            if (navbarHeigh < wrapperHeigh) {
                $('#page-wrapper').css("min-height", $(window).height() + "px");
            }

            if ($('body').hasClass('fixed-nav')) {
                $('#page-wrapper').css("min-height", $(window).height() - 60 + "px");
            }
        //}
    } 
    
    fix_height();
    
    $(window).bind("load resize scroll", function () {
        if (!$("body").hasClass('body-small')) {
            fix_height();
        }
    });    
    
    // 初始化通知组件
    initToastr();
    
    // 处理表单上的交互
    // 单个图片上传
    $(".btn-upload-pic").each(function(i, n){
        var btn = $(n);
        setupSingleUploader(btn);
        
        // 显示已上传图片
        var img = btn.parent().prev("input").val();
        if(img.length>0){
            singleUploaderPreview(btn, img);
        }
    });
    // 单个音频上传
    $(".btn-upload-audio").each(function(i, n){
        var btn = $(n);
        setupSingleUploaderAudio(btn);
        
        // 显示已上传图片
        var img = btn.parent().prev("input").val();
        if(img.length>0){
            singleUploaderPreviewAudio(btn, img);
        }
    });
    // 单个视频上传
    $(".btn-upload-video").each(function(i, n){
        var btn = $(n);
        setupSingleUploaderVideo(btn);
        
        // 显示已上传图片
        var img = btn.parent().prev("input").val();
        if(img.length>0){
            singleUploaderPreviewVideo(btn, img);
        }
    });    
    // 批量图片上传
    $(".btn-upload-pics").each(function(i, n){
        var btn = $(n);
        setupMultiUploader(btn);
        
        // 显示已上传图片
        var hidden = btn.parent().prev(":hidden");
        var hiddenArray = getHiddenArray(hidden);
        $.each(hiddenArray, function(j, m){
            multiUploaderPreview(btn, m);
        });
        
        //批量图片上传的处理拖拽的函数(https://github.com/cmaish/DragSort)
        btn.parent().dragsort({dragSelector: ".upload-pic", 
                                placeHolderTemplate: "<a class='btn btn-white btn-upload-pics' id='btn-upload-pics-pics' style='display:block;height:150px;width:150px;float:left;margin-right:10px;margin-bottom:10px;'></a>",
                                dragEnd: function(){
                                    var array = [];
                                    btn.parent().find(".upload-pic").each(function(){
                                        array.push($(this).find("img").attr("src"))
                                    });
                                    //console.log(array);
                                    setHiddenArray(hidden, array);
                                } 
        });            
    });
    
    // 日期和时间组件
    $(".datetime-input").each(function(i, n){
        $(n).datetimepicker({
            locale: 'zh-cn',
            format: $(n).data("format")
        });
    });
    // 字符串列表维护组件
    $(".strings-input").each(function(i, n){
        var dummy = $(n);
        var hidden = dummy.prev(":hidden");
        // 修改textarea的时候更新hidden input
        dummy.on('change keyup paste', function() {
            var oldValue = $(this).data("oldValue");
            var currentValue = $.trim($(this).val());
            if(oldValue && oldValue === currentValue){
                return;
            }
            $(this).data("oldValue", currentValue);
            setHiddenArray(hidden, currentValue.split('\n'));
        }); 
        // 从hidden input中读取字符串列表的值，转换后放入文本框中
        var hiddenArray = getHiddenArray(hidden);
        if(hiddenArray.length > 0){
            dummy.val(hiddenArray.join("\n"));
        }else{
            dummy.val("");
        }
    });
    // 多选组件
    $(".choice-input").each(function(i, n){
        setupChoiceInput($(n));
    });
});


function SmoothlyMenu() {
    if (!$('body').hasClass('mini-navbar') || $('body').hasClass('body-small')) {
        // Hide menu in order to smoothly turn on when maximize menu
        $('#side-menu').hide();
        // For smoothly turn on menu
        setTimeout(
            function () {
                $('#side-menu').fadeIn(500);
            }, 100);
    } else if ($('body').hasClass('fixed-sidebar')){
        $('#side-menu').hide();
        setTimeout(
            function () {
                $('#side-menu').fadeIn(500);
            }, 300);
    } else {
        // Remove all inline style from jquery fadeIn function to reset menu state
        $('#side-menu').removeAttr('style');
    }
}


/*
 * 在页面显示消息，消息类型分为success/info/warn/error
 */

function initToastr(){
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "onclick": null,
        "showDuration": "400",
        "hideDuration": "1000",
        "timeOut": "3000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
}
function success(msg){
    toastr.success(msg);
}
function info(msg){
    toastr.info(msg);
}
function warning(msg){
    toastr.warning(msg);
}
function error(msg){
    toastr.error(msg);
}


/*
 * 安装文件上传组件
 */

// 单个图片上传
// 注意container在.form-group上，即生成的隐藏控件会放在被append到这个tag里边
function setupSingleUploader(btn) {
    var btnLabel = btn.text();
    var uploader = new plupload.Uploader({
        runtimes: 'html5,flash,html4',
        browse_button: btn.attr("id"),
        container: btn.closest(".form-group").get(0),
        url: "/service/upload/picture",
        filters: {
            max_file_size: '2mb',
            mime_types: [
                {title: "Image files", extensions: "jpg,gif,png,bmp"}
            ]
        },
        multi_selection: false,
        resize: {width: 1080, height: 1080, quality: 100},
        flash_swf_url: '/themes/bootstrap/js/plugins/plupload/Moxie.swf',
        init: {
            PostInit: function () {
                //
            },
            FilesAdded: function (up, files) {
                uploader.start();
            },
            UploadProgress: function (up, file) {
                //
                btn.text(btnLabel + " ... " + file.percent + "%");
            },
            FileUploaded: function (up, file, c) {
                //
                var d = jQuery.parseJSON(c.response);
                btn.text(btnLabel);

                if (d.error == 0) {
                    var img = d.url;
                    btn.parent().prev("input").val(img);
                    singleUploaderPreview(btn, img);
                } else {
                    error(d.message);
                }
            },
            Error: function (up, err) {
                btn.text(btnLabel);
                error("文件上传失败。（#" + err.code + ": " + err.message + "）");
            }
        }
    });
    uploader.init();
}
function singleUploaderPreview(btn, img){
    var parent = btn.closest("div.input-group");
    if (!parent.next("div").is(".upload-pic-result")) {
        parent.after("<div class='upload-pic-result'><a class='upload-pic'><img class='img-thumbnail'/><span class='remove glyphicon glyphicon-minus' onclick='removeSingleUploadPic($(this))'></span></a></div>");
    }
    parent.next("div.upload-pic-result").find("img").attr("src", img);
}
function removeSingleUploadPic(btn){
    var r = confirm("确定删除么？");
    if (!r) {
        return;
    }
    var ur = btn.closest("div.upload-pic-result");
    ur.prev("div.input-group").find(":text").val("");
    ur.remove();
}

function setupSingleUploaderAudio(btn) {
    var btnLabel = btn.text();
    var uploader = new plupload.Uploader({
        runtimes: 'html5,flash,html4',
        browse_button: btn.attr("id"),
        container: btn.closest(".form-group").get(0),
        url: "/service/upload/audio",
        filters: {
            max_file_size: '5mb',
            mime_types: [
                {title: "Audio files", extensions: "mp3,wma,amr"}
            ]
        },
        multi_selection: false,
        resize: {width: 1080, height: 1080, quality: 100},
        flash_swf_url: '/themes/bootstrap/js/plugins/plupload/Moxie.swf',
        init: {
            PostInit: function () {
                //
            },
            FilesAdded: function (up, files) {
                uploader.start();
            },
            UploadProgress: function (up, file) {
                //
                btn.text(btnLabel + " ... " + file.percent + "%");
            },
            FileUploaded: function (up, file, c) {
                //
                var d = jQuery.parseJSON(c.response);
                btn.text(btnLabel);

                if (d.error == 0) {
                    var img = d.url;
                    btn.parent().prev("input").val(img);
                    singleUploaderPreviewAudio(btn, img);
                } else {
                    error(d.message);
                }
            },
            Error: function (up, err) {
                btn.text(btnLabel);
                error("文件上传失败。（#" + err.code + ": " + err.message + "）");
            }
        }
    });
    uploader.init();
}

function singleUploaderPreviewAudio(btn, audio){
    var parent = btn.closest("div.input-group");
    if (!parent.next("div").is(".upload-pic-result")) {
        parent.after("<div class='upload-pic-result'><a class='upload-pic'><audio src='song.ogg' controls='controls'></audio><span class='remove glyphicon glyphicon-minus' onclick='removeSingleUploadPic($(this))'></span></a></div>");
    }
    parent.next("div.upload-pic-result").find("audio").attr("src", audio);
}

function setupSingleUploaderVideo(btn) {
    var btnLabel = btn.text();
    var uploader = new plupload.Uploader({
        runtimes: 'html5,flash,html4',
        browse_button: btn.attr("id"),
        container: btn.closest(".form-group").get(0),
        url: "/service/upload/video",
        filters: {
            max_file_size: '5mb',
            mime_types: [
                {title: "Audio files", extensions: "mp4,wav"}
            ]
        },
        multi_selection: false,
        resize: {width: 1080, height: 1080, quality: 100},
        flash_swf_url: '/themes/bootstrap/js/plugins/plupload/Moxie.swf',
        init: {
            PostInit: function () {
                //
            },
            FilesAdded: function (up, files) {
                uploader.start();
            },
            UploadProgress: function (up, file) {
                //
                btn.text(btnLabel + " ... " + file.percent + "%");
            },
            FileUploaded: function (up, file, c) {
                //
                var d = jQuery.parseJSON(c.response);
                btn.text(btnLabel);

                if (d.error == 0) {
                    var img = d.url;
                    btn.parent().prev("input").val(img);
                    singleUploaderPreviewVideo(btn, img);
                } else {
                    error(d.message);
                }
            },
            Error: function (up, err) {
                btn.text(btnLabel);
                error("文件上传失败。（#" + err.code + ": " + err.message + "）");
            }
        }
    });
    uploader.init();
}

function singleUploaderPreviewVideo(btn, video){
    var parent = btn.closest("div.input-group");
    if (!parent.next("div").is(".upload-pic-result")) {
        parent.after("<div class='upload-pic-result'><a class='upload-pic'><video src='song.ogg' controls='controls'></video><span class='remove glyphicon glyphicon-minus' onclick='removeSingleUploadPic($(this))'></span></a></div>");
    }
    parent.next("div.upload-pic-result").find("video").attr("src", video);
}


// 批量图片上传
// 注意container在.form-group上，即生成的隐藏控件会放在被append到这个tag里边
function setupMultiUploader(btn) {
    var uploader = new plupload.Uploader({
        runtimes: 'html5,flash,html4',
        browse_button: btn.attr("id"),
        container: btn.closest(".form-group").get(0),
        url: "/service/upload/picture",
        filters: {
            max_file_size: '2mb',
            mime_types: [
                {title: "Image files", extensions: "jpg,gif,png"}
            ]
        },
        multi_selection: true,
        resize: {width: 1080, height: 1080, quality: 100},
        flash_swf_url: '/themes/bootstrap/js/plugins/plupload/Moxie.swf',
        init: {
            PostInit: function () {
                //
            },
            FilesAdded: function (up, files) {
                // Remove previous error uploads
                var parent = btn.parent();
                parent.children("a.upload-pic").not(".success").remove();
                
                // Add uploading template
                $.each(files, function(i, file) {
                    btn.before("<a id='upload-pic-"+file.id+"' class='btn btn-white upload-pic' style='height:150px;width:150px;'>"+
                            "<div class='progress progress-small' style='margin-top:63px'><div class='progress-bar' style='width: 5%;'></div></div></a>");                    
//                    parent.append("<a id='upload-pic-"+file.id+"' class='btn btn-white upload-pic' style='height:150px;width:150px;'>"+
//                            "<div class='progress progress-small' style='margin-top:63px'><div class='progress-bar' style='width: 5%;'></div></div></a>");
                });
                
                // Start 
                uploader.start();
            },
            UploadProgress: function (up, file) {
                //
                $('#upload-pic-' + file.id + ' .progress-bar').css({"width": file.percent + "%"});
            },
            FileUploaded: function (up, file, c) {
                //
                var d = jQuery.parseJSON(c.response);

                if (d.error == 0) {
                    var img = d.url;
                    // 上传成功后，去除边框，重置其高宽
                    $('#upload-pic-' + file.id).removeClass("btn btn-white").addClass("success").css({"height":"auto","width":"auto"}).html("<img class='img-thumbnail' src='"+img+"'/><span class='remove glyphicon glyphicon-minus' onclick='removeMultiUploadPic($(this))'></span>");
                    addToHiddenArray(btn.parent().prev(":hidden"), img);
                } else {
                    $('#upload-pic-' + file.id + " .progress-bar").addClass("progress-bar-danger");
                }
            },
            Error: function (up, err) {
                error("文件上传失败。（#" + err.code + ": " + err.message + "）");
            }
        }
    });
    uploader.init();
}
function multiUploaderPreview(btn, img){
    btn.before("<a class='upload-pic success'><img class='img-thumbnail' src='"+img+"'/><span class='remove glyphicon glyphicon-minus' onclick='removeMultiUploadPic($(this))'></span></a>");
//    var parent = btn.parent();
//    parent.append("<a class='upload-pic success'><img class='img-thumbnail' src='"+img+"'/><span class='remove glyphicon glyphicon-minus' onclick='removeMultiUploadPic($(this))'></span></a>");
}
function removeMultiUploadPic(btn){
    var r = confirm("确定删除此图片么？");
    if (!r) {
        return;
    }
    var hidden = btn.closest("div.form-group").find(":hidden");
    var img = btn.prev("img").attr("src");
    removeFromHiddenArray(hidden, img);
    btn.parent().remove();
}

// 安装多选组件
function setupChoiceInput(input){
    var hidden = input.prev(":hidden");
    var hiddenArray = getHiddenArray(hidden);
    // 从hidden input中读取字符串列表的值，转换后设置
    input.find(":checkbox").each(function(i, n){
        if($.inArray($(this).val(), hiddenArray) >= 0){
            $(this).prop('checked', true);
        }else{
            $(this).prop('checked', false);
        }
    }).
    // 选中或者取消选中的时候更新hidden input
    change(function(){
        if($(this).is(":checked")){
            addToHiddenArray(hidden, $(this).val());
        }else{
            removeFromHiddenArray(hidden, $(this).val());
        }
    });
}

/*
 * 列表帮助方法
 *   - 用于批量上传文件组件，将成功上传的文件路径加入真正要提交的hidden input中
 *   - 用于多选组件，将选择或者取消选择的选项更新到要提交的hidden input中
 *   - 用于字符串列表维护组件，将增加的字符串更新到要提交的hidden input中
 *   
 * TODO:支持低级浏览器或者使用简单js脚本转化array和json字符串
 */

function addToHiddenArray(hidden, value){
    var array = getHiddenArray(hidden);
    if($.inArray(value, array) >= 0){
        return;
    }
    array.push(value);
    setHiddenArray(hidden, array);
}
function removeFromHiddenArray(hidden, value){
    var array = getHiddenArray(hidden);
    var pos = $.inArray(value, array);
    if(pos === -1){
        return;
    }
    array.splice(pos, 1);
    setHiddenArray(hidden, array);
}
function getHiddenArray(hidden){
    var hiddenVal = hidden.val();
    var array = [];
    if($.trim(hiddenVal).length > 0){
        array = JSON.parse(hiddenVal);
    }
    
    return array;
}
function setHiddenArray(hidden, array){
    if(array.length > 0){
        var noEmptyValueArray = $.grep(array, function(n){return n;});
        hidden.val(JSON.stringify(noEmptyValueArray));
    }else{
        hidden.val("");
    }
}

/*
 * 图表帮助方法
 *   - 获取默认的图表配置
 *   - 将后台返回的数据绘制到图表上
 */

// 生成直角系图表的配置，使用$.extend来覆盖默认设置
// 参数extend的格式与图表的格式一致
function prepareCartesianOption(extend){
    // http://echarts.baidu.com/doc/doc.html#Option
    var option = {
        color: ['#1ab394','#79d2c0','#bababa','#d3d3d3'],   // 颜色序列，循环使用  
        symbolList: ['emptyCircle'],                        // 
        title: {
            text: '',
            subtext: ''
        },
        legend: {                                   // 图例配置
            padding: 5,                             // 图例内边距，单位px，默认上下左右内边距为5
            itemGap: 10,                            // Legend各个item之间的间隔，横向布局时为水平间隔，纵向布局时为纵向间隔
            //data: ['ios', 'android']
            data: []
        },
        tooltip: {                                  // 气泡提示配置
            trigger: 'axis'                         // 触发类型，默认item，选中数据点时触发，可选为：'axis'
        },
        toolbox: {
            show : true,
            feature : {
                //mark : {show: true},
                dataView : {show: false, readOnly: false},
                //magicType : {show: true, type: ['line', 'bar', 'stack', 'tiled']},
                magicType : {show: true, type: ['line', 'bar']},
                //restore : {show: true},
                saveAsImage : {show: true}
            }
        },
        grid: {
            x: 50,                                  // 绘图网格的padding
            y: 80,
            x2: 20,
            y2: 40,
            borderColor: 'rgba(0,0,0,.05)'
        },
        xAxis: [                                    // 直角坐标系中横轴数组
            /*
            {
                type: 'category',                   // 坐标轴类型，横轴默认为类目轴，数值轴则参考yAxis说明
                data: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            }
            */
        ],
        yAxis: [                                    // 直角坐标系中纵轴数组
            {
                type: 'value',                      // 坐标轴类型，纵轴默认为数值轴，类目轴则参考xAxis说明
                boundaryGap: [0.1, 0.1],            // 坐标轴两端空白策略，数组内数值代表百分比
                //splitNumber: 5,                   // 数值轴用，分割段数，默认为5
                min: 0,
                splitLine: splitLineOption(),
                axisLine: axisLineOption(),
                axisTick: axisTickOption()
            }
        ],
        series: [
            /*
            {
                name: 'ios',                        // 系列名称
                type: 'line',                       // 图表类型，折线图line、散点图scatter、柱状图bar、饼图pie、雷达图radar
                smooth: true,
                data: [112, 23, 45, 56, 233, 343, 454, 89, 343, 123, 45, 123]
            },
            {
                name: 'android',                    // 系列名称
                type: 'line',                       // 图表类型，折线图line、散点图scatter、柱状图bar、饼图pie、雷达图radar
                smooth: true,
                data: [45, 123, 145, 526, 233, 343, 44, 829, 33, 123, 45, 13]
            }
            */
        ]
    };
    
    // https://api.jquery.com/jquery.extend/
    $.extend(true, option, extend);
    return option;
}

function splitLineOption(){
    return {show: true, lineStyle: {color: ['rgba(0,0,0,.05)'], width: 1, type: 'solid'}};
}

function axisLineOption(){
    return {show: true, lineStyle: {color: 'rgba(0,0,0,.05)', width: 1, type: 'solid'}};
}

function axisTickOption(){
    return {show: true, length: 5, lineStyle: {color: 'rgba(0,0,0,.05)', width: 1, type: 'solid'}};
}

// 将服务器端返回的直角系图表数据转化为ECharts绘图需要的数据
// 
// 服务器端返回的格式如下：
// [
//   [x0, x1, x2, ... , x(n-1)],        // x轴上的标签
//   [y00, y01, y02, ... , y0(n-1)],    // y轴上的值
//   [y10, y11, y12, ... , y1(n-1)],
//   ...                                // 支持多个序列
// ]
function parseCartesianData(currentOption, legends, data){
    // Legend
    // 返回的是多个序列，每一个序列需要一个legend
    $.merge(currentOption.legend.data, legends);
    
    // X
    currentOption.xAxis.push({
        data: data[0],
        splitLine: splitLineOption(),
        axisLine: axisLineOption(),
        axisTick: axisTickOption()
    });
    
    // Y
    $.each(data.splice(1), function(i, n){
        currentOption.series.push({
            name: legends[i],                            
            type: 'line', 
            smooth: true,
            data: n,
            symbolSize: 3
        });
    });
    
    return currentOption;
}

// 生成饼图的配置，使用$.extend来覆盖默认设置
// 参数extend的格式与图表的格式一致
function preparePieOption(extend){
    // http://echarts.baidu.com/doc/doc.html#Option
    var option = {
        title: {
            text: '',
            subtext: ''
        },
        legend: {
            padding: 5, 
            itemGap: 10,
            data: []
        },
        tooltip: {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        toolbox: {
            show : true,
            feature : {
                //mark : {show: true},
                dataView : {show: true, readOnly: false},
                //magicType : {show: true, type: ['pie', 'funnel']},
                //restore : {show: true},
                saveAsImage : {show: true}
            }
        },
        //calculable : true,
        series: []
    };
    
    // https://api.jquery.com/jquery.extend/
    $.extend(true, option, extend);
    return option;    
}

// 将服务器端返回的饼图数据转化为ECharts绘图需要的数据
// 
// 服务器端返回的格式如下：
// [
//   [
//     {name: x0, value: y0}, 
//     ...
//   ],
//   ...
// ]
function parsePieData(currentOption, names, data){
    
    // Legend
    // 返回的是多个序列，每一个序列对应一个饼图
    // 但习惯上饼图的legend常常用来显示一个饼图的不同部分
    // 因此我们不传入legends，而传入names，标识序列的名字，鼠标移动到饼图上时，会显示该名字
    var legends = $.map(data[0], function(n, i){
        return n.name;
    });
    $.merge(currentOption.legend.data, legends);
    
    // Parts
    $.each(data, function(i, n){
        currentOption.series.push({
            name: names[i],
            type: 'pie', 
            radius : '55%',
            center: ['50%', '60%'],
            data: n
        });
    });
    
    return currentOption;
}
