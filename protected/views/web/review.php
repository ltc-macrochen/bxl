<?php
/**
 * Created by PhpStorm.
 * User: macrochen
 * Date: 2017/8/2
 * Time: 15:40
 */
?>
<div class="wrapper wrapper-content bxl-web-wraper">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-8 col-lg-8">
                <!--投稿-->
                <div class="ibox">

                    <div class="ibox-content">
                        <div class="m-t-sm">
                            <a class="bxl-article" href="<?php echo $post[0]['contentDetailUrl'];?>">
                                <h2 class="bxl-title text-center">
                                    <?php echo $post[0]['title'];?>
                                </h2>
                                <div class="bxl-content">
                                    <span><?php echo $post[0]['content'];?></span>
                                </div>
                                <?php if(!empty($post[0]['imgUrl'])):?>
                                    <div class="bxl-thumb text-center" id="image_kill_referrer_<?php echo $post[0]['id'];?>">
                                        <?php if($post[0]['killrefer'] == 'true'):?>
                                            <script>
                                                document.getElementById('image_kill_referrer_<?php echo $post[0]['id'];?>').innerHTML = ReferrerKiller.imageHtml('<?php echo $post[0]['imgUrl'];?>');
                                            </script>
                                        <?php else:?>
                                        <img src="<?php echo $post[0]['imgUrl'];?>" alt="<?php echo $post[0]['title'];?>">
                                        <?php endif;?>
                                    </div>
                                <?php endif;?>
                            </a>
                            <div class="row text-center bxl-btn-review">
                                <div class="col-md-8 col-md-offset-2">
                                    <div class="pull-left btn-group" role="group">
                                        <a href="javascript:;" onclick="doReview(<?php echo $post[0]['id'];?>,'bad');return false;" class="btn btn-default"><i class="fa fa-frown-o"></i> 不好笑</a>
                                    </div>
                                    <div class="btn-group" role="group">
                                        <a href="javascript:;" onclick="doReview(<?php echo $post[0]['id'];?>,'hard');return false;" class="btn btn-white">不确定 <i class="fa fa-meh-o"></i></a>
                                    </div>
                                    <div class="pull-right btn-group" role="group">
                                        <a href="javascript:;" onclick="doReview(<?php echo $post[0]['id'];?>,'good');return false;" class="btn btn-warning">好笑 <i class="fa fa-smile-o"></i></a>
                                    </div>
                                </div>
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
