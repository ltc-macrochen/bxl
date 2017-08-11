<?php
/**
 * Created by PhpStorm.
 * User: macrochen
 * Date: 2017/7/21
 * Time: 17:38
 */
?>

<!--side right-->
<div class="col-lg-4 bxl-side-right">
    <div class="ibox float-e-margins bxl-ibox-right">
        <div class="ibox-content text-center bxl-tougao">
            <a href="<?php echo Yii::app()->createUrl('/web/newAdd');?>"><i class="fa fa-edit"></i> 发帖</a>
        </div>
    </div>

    <div class="ibox float-e-margins bxl-ibox-right hidden-xs hidden-sm">
        <div class="ibox-content text-center bxl-qrcode">
            <img src="/web/images/bxl-wap.jpg" alt="扫描二维码 用手机看爆笑驴">
            <p>扫描二维码 用手机看爆笑驴</p>
        </div>
    </div>

    <!--ad-->
    <div class="ibox float-e-margins bxl-ibox-right">
        <div class="ibox-content bxl-tag">

        </div>
    </div>

    <div class="ibox float-e-margins bxl-ibox-right">
        <div class="ibox-content text-center bxl-qrcode">
            <img src="/web/images/bxl-weixin.jpg" alt="扫描二维码 关注爆笑驴微信">
            <p>扫描二维码 关注爆笑驴微信</p>
        </div>
    </div>

    <div class="ibox float-e-margins bxl-ibox-right hidden-xs hidden-sm">
        <div class="ibox-title">
            <h4>精彩推荐</h4>
        </div>
        <div class="ibox-content text-center bxl-recommend">
            <div class="row">
                <?php foreach ($top4 as $item):?>
                    <a class="col-xs-6 col-md-6 col-lg-6" href="<?php echo $item['contentDetailUrl'];?>">
                        <?php
                        $top4Id = $item['id'];
                        if(isset($post)){
                            $top4Id = ($item['id'] == $post[0]['id']) ? $item['id'] . '_top4' : $item['id'];
                        }
                        ?>
                        <div id="image_kill_referrer_<?php echo $top4Id;?>">
                            <?php if($item['killrefer'] == 'true'):?>
                                <script>
                                    var opt = {'style' : 'width:126px;height:95px;cursor: pointer;', 'contentDetailUrl' : '<?php echo $item['contentDetailUrl'];?>'};
                                    document.getElementById('image_kill_referrer_<?php echo $top4Id;?>').innerHTML = ReferrerKiller.imageHtml('<?php echo $item['imgUrl'];?>',opt);
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
</div>
