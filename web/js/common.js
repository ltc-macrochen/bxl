/**
 * Created by macrochen on 2017/8/2.
 */
// Custom scripts
$(document).ready(function () {

    // 处理表单上的交互
    // 单个图片上传
    $(".btn-upload-pic").each(function(i, n){
        var btn = $(n);
        setupSingleUploader(btn);

        // 显示已上传图片
        var img = btn.parent().prev("input").val();
        if(img && img.length>0){
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
        parent.after("<div class='upload-pic-result'><a class='upload-pic'><img class='img-thumbnail'/><span class='remove fa fa-minus' onclick='removeSingleUploadPic($(this))'></span></a></div>");
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


