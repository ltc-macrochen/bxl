<?php
/**
 * Created by PhpStorm.
 * User: macrochen
 * Date: 2017/7/17
 * Time: 11:28
 */
?>

<div class="wrapper wrapper-content bxl-web-wraper">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-lg-8">

                <!--分页内容-->
                <?php foreach ($postdata as $item):?>
                    <div class="ibox float-e-margins">
                        <div class="ibox-content bxl-item">
                            <div>
                                <span class="pull-right text-right hidden">
                                    <small>在过去的一个月销售的平均值：<strong>山东</strong></small>
                                    <br/>
                                    所有销售： 162,862
                                </span>
                                <div class="bxl-user">
                                    <a href="<?php echo $item['userDetailUrl'];?>" target="_blank">
                                        <img class="bxl-head" src="<?php echo $item['userHead'];?>" alt="<?php echo $item['userNick'];?>">
                                        <span class="bxl-nick"><?php echo $item['userNick'];?></span>
                                    </a>
                                </div>
                            </div>

                            <div class="m-t-sm">
                                <a class="bxl-article" href="<?php echo $item['contentDetailUrl'];?>">
                                    <h2 class="bxl-title">
                                        <?php echo $item['title'];?>
                                    </h2>
                                    <div class="bxl-content">
                                        <span><?php echo $item['content'];?></span>
                                    </div>
                                    <?php if(!empty($item['imgUrl'])):?>
                                        <div class="bxl-thumb text-center" id="image_kill_referrer_<?php echo $item['id'];?>">
                                            <?php if($item['killrefer'] == 'true'):?>
                                                <script>
                                                    document.getElementById('image_kill_referrer_<?php echo $item['id'];?>').innerHTML = ReferrerKiller.imageHtml('<?php echo $item['imgUrl'];?>');
                                                </script>
                                            <?php else:?>
                                                <img src="<?php echo $item['imgUrl'];?>" alt="<?php echo $item['title'];?>">
                                            <?php endif;?>
                                        </div>
                                    <?php endif;?>
                                </a>

                            </div>

                            <div class="m-t-md">
                                <div class="pull-right hidden">
                                    <i class="fa fa-clock-o"> </i>
                                    2015.02.30更新
                                </div>
                                <div class="bxl-btn">
                                    <button type="button" class="btn btn-link bxl-good" onclick="doLike('<?php echo $item['id'];?>', 'like', this);return false;">
                                        <i class="fa fa-thumbs-o-up"></i><!--fa-thumbs-up-->
                                        <span><?php echo $item['vGood'];?></span>
                                    </button>
                                    <button type="button" class="btn btn-link bxl-bad" onclick="doLike('<?php echo $item['id'];?>', 'unlike', this);return false;">
                                        <i class="fa fa-thumbs-o-down"></i><!--fa-thumbs-o-down-->
                                        <span><?php echo $item['vBad'];?></span>
                                    </button>
                                    <button type="button" class="btn btn-link bxl-comment" onclick="window.location.href='<?php echo $item['contentDetailUrl'];?>'">
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
                                            url:"http://www.baoxiaolv.cn",
                                            title:"爆笑驴-做人最重要的是开心！",
                                            summary:"爆笑驴::爆笑笑话_糗事笑话_爆笑GIF_内涵段子_冷笑话_专注幽默搞笑网站！",
                                            pic:"/web/images/head_default.jpg",
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
                <?php endforeach;?>

                <!--分页页码-->
                <div class="text-center">
                    <?php $this->widget('BsPager',array(
                        //'header' => '',
                        'firstPageLabel' => '首页',
                        'lastPageLabel' => '尾页',
                        'prevPageLabel' => '上一页',
                        'nextPageLabel' => '下一页',
                        'pages' => $pages,
                        'maxButtonCount'=>4,
                    ));?>
                </div>
            </div>

            <!--side right-->
            <?php require_once 'side-right.php';?>
        </div>

    </div>

</div>
