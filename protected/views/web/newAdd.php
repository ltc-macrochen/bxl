<?php
/**
 * Created by PhpStorm.
 * User: macrochen
 * Date: 2017/8/1
 * Time: 17:32
 */

$cs = Yii::app()->clientScript;
$themePath = Yii::app()->theme->baseUrl;
$cs->registerScriptFile($themePath . '/js/plugins/plupload/plupload.full.min.js', CClientScript::POS_END);
$cs->registerScriptFile('/web/js/common.js', CClientScript::POS_END);
?>

<div class="wrapper wrapper-content bxl-web-wraper">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-lg-8">
                <!--投稿-->
                <div class="ibox bxl-newadd" id="bxl-newadd-area">

                    <div class="ibox-content bxl-newadd">
                        <textarea class="form-control" title="分享我的爆笑糗事笑话~" rows="5" placeholder="分享我的爆笑糗事笑话~" maxlength="300"></textarea>
                        <div class="newadd-input-action">
                            <div class="input-group">
                                <input name="uploadPic" class="form-control hidden" placeholder="图片地址" type="text">
                                <span class="input-group-btn">
                                    <button id="btn-upload-pic-imgUrl" class="btn-upload-pic btn btn-info" name="yt0" type="button" >
                                        <i class="fa fa-upload"></i>
                                        <span class="hidden-xs">上传</span>图片
                                    </button>
                                    最大2M
                                </span>

                                <input type="submit" class="btn btn-danger pull-right" value="提交">
                                <span class="pull-right bxl-newadd-limit">还可输入<span class="text-danger">300</span>字</span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!--side right-->
            <div class="col-lg-4 bxl-side-right bxl-newadd-notice">
                <div class="ibox float-e-margins bxl-ibox-right">
                    <div class="ibox-title">
                        <h4>投稿须知</h4>
                    </div>
                    <div class="ibox-content">
                        <ol>
                            <li>自己的或朋友的糗事笑话，真实有笑点，不含政治、色情、广告、诽谤、歧视等内容。</li>
                            <li>糗事笑话经过审核后发表。</li>
                            <li>转载请注明出处。</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $('#bxl-newadd-area textarea').on('keyup', function () {
            var inputVal = $(this).val();
            var inputLen = inputVal.length;
            $('#bxl-newadd-area .bxl-newadd-limit span').text(300 - parseInt(inputLen));
        })
    })
</script>
