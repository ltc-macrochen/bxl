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
                                <?php
                                if(!$post->userId){
                                    $userHome = '/';
                                    $userHead = "/web/images/head-".rand(1, 19).".jpg";
                                    $userNick = "驴传说";
                                }else{
                                    $userHome = '/web/user/id/' . $post->userId;
                                    $userHead = $post->user->head;
                                    $userNick = $post->user->nick;
                                }
                                ?>
                                <a href="<?php echo $userHome;?>" target="_blank">
                                    <img class="bxl-head" src="<?php echo $userHead;?>" alt="<?php echo $userNick;?>">
                                    <span class="bxl-nick"><?php echo $userNick;?></span>
                                </a>
                            </div>
                        </div>

                        <div class="m-t-sm">
                            <a class="bxl-article" href="<?php echo $this->createUrl('/web/content/id/' . $post->id);?>">
                                <h2 class="bxl-title">
                                    <?php echo $post->title;?>
                                </h2>
                                <div class="bxl-content">
                                    <span><?php echo $post->content;?></span>
                                </div>
                                <?php if(!empty($post->imgUrl)):?>
                                    <div class="bxl-thumb text-center" id="image_kill_referrer_<?php echo $post->id;?>">
                                        <!--<img src="" alt="图片图片">-->
                                        <script>
                                            document.getElementById('image_kill_referrer_<?php echo $post->id;?>').innerHTML = ReferrerKiller.imageHtml('<?php echo $post->imgUrl;?>');
                                        </script>
                                    </div>
                                <?php endif;?>
                            </a>
                            <div class="row text-center bxl-btn-view">
                                <div class="col-md-8 col-md-offset-2">
                                    <div class="pull-left btn-group" role="group">
                                        <a href="<?php echo ($post->id != 1) ? $this->createUrl('/web/content/id/' . (intval($post->id) - 1)) : 'javascript:alert(\'已经是第一条了~\');';?>" class="btn btn-link"><i class="fa fa-hand-o-left"></i> 上一条</a>
                                    </div>
                                    <div class="pull-right btn-group" role="group">
                                        <a href="<?php echo $this->createUrl('/web/content/id/' . (intval($post->id) + 1));?>" class="btn btn-link">下一条 <i class="fa fa-hand-o-right"></i></a>
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
                                <button type="button" class="btn btn-link bxl-good" onclick="doLike('<?php echo $post->id;?>', 'like', this);return false;">
                                    <i class="fa fa-thumbs-o-up"></i><!--fa-thumbs-up-->
                                    <span><?php echo $post->vGood;?></span>
                                </button>
                                <button type="button" class="btn btn-link bxl-bad" onclick="doLike('<?php echo $post->id;?>', 'unlike', this);return false;">
                                    <i class="fa fa-thumbs-o-down"></i><!--fa-thumbs-o-down-->
                                    <span><?php echo $post->vBad;?></span>
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
                                        url:"<?php echo $this->createUrl('/web/content/id/' . $post->id);?>",
                                        title:"<?php echo $post->title;?>",
                                        summary:"<?php echo $post->content;?>",
                                        pic:"<?php echo empty($post->imgUrl) ? '/web/images/head-deafult.jpg' : $post->imgUrl;?>",
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
                            <a class="col-xs-3 col-md-3">
                                <img src="/web/images/head-default.jpg" alt="扫描二维码 关注爆笑驴微信">
                                <p>扫描二维码 关注爆笑驴微信</p>
                            </a>
                            <a class="col-xs-3 col-md-3">
                                <img src="/web/images/head-default.jpg" alt="扫描二维码 关注爆笑驴微信">
                                <p>扫描二维码 关注爆笑驴微信</p>
                            </a>
                            <a class="col-xs-3 col-md-3">
                                <img src="/web/images/head-default.jpg" alt="扫描二维码 关注爆笑驴微信">
                                <p>扫描二维码 关注爆笑驴微信</p>
                            </a>
                            <a class="col-xs-3 col-md-3">
                                <img src="/web/images/head-default.jpg" alt="扫描二维码 关注爆笑驴微信">
                                <p>扫描二维码 关注爆笑驴微信</p>
                            </a>
                        </div>
                    </div>
                </div>

                <!--热门推荐-->
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h3>热门推荐</h3>
                    </div>
                    <div class="ibox-content text-center bxl-recommend">
                        <div class="row">
                            <a class="col-xs-3 col-md-3">
                                <img src="/web/images/head-default.jpg" alt="扫描二维码 关注爆笑驴微信">
                                <p>扫描二维码 关注爆笑驴微信</p>
                            </a>
                            <a class="col-xs-3 col-md-3">
                                <img src="/web/images/head-default.jpg" alt="扫描二维码 关注爆笑驴微信">
                                <p>扫描二维码 关注爆笑驴微信</p>
                            </a>
                            <a class="col-xs-3 col-md-3">
                                <img src="/web/images/head-default.jpg" alt="扫描二维码 关注爆笑驴微信">
                                <p>扫描二维码 关注爆笑驴微信</p>
                            </a>
                            <a class="col-xs-3 col-md-3">
                                <img src="/web/images/head-default.jpg" alt="扫描二维码 关注爆笑驴微信">
                                <p>扫描二维码 关注爆笑驴微信</p>
                            </a>
                            <a class="col-xs-3 col-md-3">
                                <img src="/web/images/head-default.jpg" alt="扫描二维码 关注爆笑驴微信">
                                <p>扫描二维码 关注爆笑驴微信</p>
                            </a>
                            <a class="col-xs-3 col-md-3">
                                <img src="/web/images/head-default.jpg" alt="扫描二维码 关注爆笑驴微信">
                                <p>扫描二维码 关注爆笑驴微信</p>
                            </a>
                            <a class="col-xs-3 col-md-3">
                                <img src="/web/images/head-default.jpg" alt="扫描二维码 关注爆笑驴微信">
                                <p>扫描二维码 关注爆笑驴微信</p>
                            </a>
                            <a class="col-xs-3 col-md-3">
                                <img src="/web/images/head-default.jpg" alt="扫描二维码 关注爆笑驴微信">
                                <p>扫描二维码 关注爆笑驴微信</p>
                            </a>
                        </div>
                    </div>
                </div>

                <!--评论-->
                <div class="ibox float-e-margins bxl-comment" id="bxl-comment-area">
                    <div class="ibox-title">
                        爆笑评论（<i class="text-danger"><?php echo $post->commentCount;?> </i>条评论）
                    </div>
                    <div class="ibox-content bxl-recommend">
                        <textarea class="form-control" title="说点什么吧，期待您的神回复！" rows="5" placeholder="说点什么吧，期待您的神回复！"></textarea>
                        <div class="comment-input-action">
                            <a class="btn btn-info" href="">登录</a>
                            <span>登录后评论可立即显示，并获得积分！</span>
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
