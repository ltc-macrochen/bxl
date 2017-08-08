<?php
/**
 * Created by PhpStorm.
 * User: macrochen
 * Date: 2017/7/21
 * Time: 17:37
 */
?>
<div class="wrapper wrapper-content bxl-web-wraper">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-lg-8">
                <div class="ibox float-e-margins">
                    <div class="ibox-content bxl-item">
                        <div>
                            <span class="pull-right text-right hidden">
                                <small>在过去的一个月销售的平均值：<strong>山东</strong></small>
                                <br/>
                                所有销售： 162,862
                            </span>
                            <div class="bxl-user">
                                <a href="<?php echo $post[0]['userDetailUrl'];?>" target="_blank">
                                    <img class="bxl-head" src="<?php echo $post[0]['userHead'];?>" alt="<?php echo $post[0]['userNick'];?>">
                                    <span class="bxl-nick"><?php echo $post[0]['userNick'];?></span>
                                </a>
                            </div>
                        </div>

                        <div class="m-t-sm">
                            <a class="bxl-article" href="<?php echo $post[0]['contentDetailUrl'];?>">
                                <h2 class="bxl-title">
                                    <?php echo $post[0]['title'];?>
                                </h2>
                                <div class="bxl-content">
                                    <span><?php echo $post[0]['content'];?></span>
                                </div>
                                <?php if(!empty($post[0]['imgUrl'])):?>
                                    <div class="bxl-thumb text-center" id="image_kill_referrer_<?php echo $post[0]['id'];?>">
                                        <?php if($post[0]['killrefer'] == 'true'):?>
                                            <script>
                                                var opt = {'style' : 'max-width:300px;', 'contentDetailUrl' : '<?php echo $post[0]['contentDetailUrl'];?>'}
                                                document.getElementById('image_kill_referrer_<?php echo $post[0]['id'];?>').innerHTML = ReferrerKiller.imageHtml('<?php echo $post[0]['imgUrl'];?>', opt);
                                            </script>
                                        <?php else:?>
                                            <img src="<?php echo $post[0]['imgUrl'];?>" alt="<?php echo $post[0]['title'];?>">
                                        <?php endif;?>
                                    </div>
                                <?php endif;?>
                            </a>
                            <div class="row text-center bxl-btn-view">
                                <div class="col-md-8 col-md-offset-2">
                                    <div class="pull-left btn-group" role="group">
                                        <a href="<?php echo ($post[0]['id'] != 1) ? Yii::app()->createUrl('/web/content/id/' . (intval($post[0]['id']) - 1)) : 'javascript:alert(\'已经是第一条了~\');';?>" class="btn btn-link"><i class="fa fa-hand-o-left"></i> 上一条</a>
                                    </div>
                                    <div class="pull-right btn-group" role="group">
                                        <a href="<?php echo Yii::app()->createUrl('/web/content/id/' . (intval($post[0]['id']) + 1));?>" class="btn btn-link">下一条 <i class="fa fa-hand-o-right"></i></a>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="m-t-md">
                            <div class="pull-right hidden">
                                <i class="fa fa-clock-o"> </i>
                                2015.02.30更新
                            </div>
                            <div class="bxl-btn">
                                <button type="button" class="btn btn-link bxl-good" onclick="doLike('<?php echo $post[0]['id'];?>', 'like', this);return false;">
                                    <i class="fa fa-thumbs-o-up"></i><!--fa-thumbs-up-->
                                    <span><?php echo $post[0]['vGood'];?></span>
                                </button>
                                <button type="button" class="btn btn-link bxl-bad" onclick="doLike('<?php echo $post[0]['id'];?>', 'unlike', this);return false;">
                                    <i class="fa fa-thumbs-o-down"></i><!--fa-thumbs-o-down-->
                                    <span><?php echo $post[0]['vBad'];?></span>
                                </button>
                                <button type="button" class="btn btn-link bxl-comment" onclick="location.href='#bxl-comment-area'">
                                    <i class="fa fa fa-comment-o"></i>
                                </button>
                                <button type="button" class="btn btn-link">
                                    <a href="javascript:;" class="jiathis jiathis_txt jtico_jiathis" target="_blank">
                                        <i class="fa fa-share-alt"></i>
                                    </a>
                                </button>
                                <!-- JiaThis Button BEGIN -->
                                <script type="text/javascript" >
                                    var jiathis_config={
                                        url:"<?php echo Yii::app()->createUrl('/web/content/id/' . $post[0]['id']);?>",
                                        title:"<?php echo $post[0]['title'];?>",
                                        summary:"<?php echo $post[0]['content'];?>",
                                        pic:"<?php echo empty($post[0]['imgUrl']) ? '/web/images/head-deafult.jpg' : $post[0]['imgUrl'];?>",
                                        shortUrl:false,
                                        hideMore:false
                                    }
                                </script>
                                <script type="text/javascript" src="http://v3.jiathis.com/code/jia.js" charset="utf-8"></script>
                                <!-- JiaThis Button END -->
                            </div>
                        </div>

                    </div>
                </div>

                <!--猜你喜欢-->
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h3>猜你喜欢</h3>
                    </div>
                    <div class="ibox-content text-center bxl-recommend">
                        <div class="row">
                            <?php foreach ($guess4 as $item):?>
                                <a class="col-xs-6 col-sm-3 col-md-3 col-lg-3" href="<?php echo $item['contentDetailUrl'];?>">
                                    <?php $guess4Id = ($item['id'] == $post[0]['id']) ? $item['id'] . '_guess4' : $item['id'];?>
                                    <div id="image_kill_referrer_<?php echo $guess4Id;?>">
                                        <?php if($item['killrefer'] == 'true'):?>
                                            <script>
                                                var opt = {'style' : 'width:126px;height:95px;cursor: pointer;', 'contentDetailUrl' : '<?php echo $item['contentDetailUrl'];?>'};
                                                document.getElementById('image_kill_referrer_<?php echo $guess4Id;?>').innerHTML = ReferrerKiller.imageHtml('<?php echo $item['imgUrl'];?>', opt);
                                            </script>
                                        <?php else:?>
                                        <img class="lazy" src="/web/images/grey.gif" data-original="<?php echo $item['imgUrl'];?>" alt="<?php echo $item['title'];?>">
                                        <?php endif;?>
                                    </div>
                                    <p><?php echo $item['title']?></p>

                                </a>
                            <?php endforeach;?>
                        </div>
                    </div>
                </div>

                <!--ad-->
                <div class="ibox float-e-margins">
                    <div class="ibox-content text-center bxl-recommend">
                        <div class="row">
                            <script type="text/javascript">
                                var sogou_ad_id=881925;
                                var sogou_ad_height=90;
                                var sogou_ad_width=580;
                            </script>
                            <script type='text/javascript' src='http://images.sohu.com/cs/jsfile/js/c.js'></script>
                        </div>
                    </div>
                </div>

                <!--评论-->
                <div class="ibox float-e-margins bxl-comment" id="bxl-comment-area">
                    <div class="ibox-title">
                        爆笑评论（<i class="text-danger"><?php echo $post[0]['commentCount'];?> </i>条评论）
                    </div>
                    <div class="ibox-content">
                        <textarea class="form-control" title="说点什么吧，期待您的神回复！" rows="5" placeholder="说点什么吧，期待您的神回复！"></textarea>
                        <div class="comment-input-action">
                            <a class="btn btn-info" href="">登录</a>
                            <span class="hidden-xs">登录后评论可立即显示，并获得积分！</span>
                            <input type="submit" class="btn btn-danger pull-right" value="评论">
                            <span class="pull-right bxl-comment-limit">还可输入<span class="text-danger">300</span>字</span>
                        </div>
                        <div class="comment-list">

                        </div>
                    </div>
                </div>
            </div>

            <!--side right-->
            <?php require_once 'side-right.php';?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $('#bxl-comment-area textarea').on('keyup', function () {
            var inputVal = $(this).val();
            var inputLen = inputVal.length;
            $('#bxl-comment-area .bxl-comment-limit span').text(300 - parseInt(inputLen));
        })
    })
</script>
