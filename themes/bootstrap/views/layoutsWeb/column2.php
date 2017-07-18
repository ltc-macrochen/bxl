<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layoutsWeb/main'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-4">
                    <h2><?php echo $this->title;?></h2>
                    <?php if(isset($this->breadcrumbs)):?>
                            <?php $this->widget('bootstrap.widgets.BsBreadcrumb', array(
                                    'homeLink' => BsHtml::tag("li", array(), BsHtml::link('首页', CHtml::normalizeUrl(array("/site/index")))),
                                    'links'=>$this->breadcrumbs,
                            )); ?><!-- breadcrumbs -->
                    <?php endif?>                     
                </div>
                <div class="col-sm-8">
                    <div class="title-action">
                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="wrapper wrapper-content">
                        <div class="">
                            <?php if(count($this->menu)>0) { ?>
                            <div style='margin-bottom: 20px'>
                                <div class="btn-group">
                                    <?php foreach ($this->menu as $m) {
                                        $class = $this->isMenuActive($m)?"btn btn-primary":"btn btn-white";
                                        $target = isset($m['target'])?"target='{$m["target"]}'":"";   
                                        echo "<a href='" . CHtml::normalizeUrl($m['url']) . "' class='{$class}' {$target}><span class='{$m['icon']}'></span> {$m['label']}</a>";
                                    } ?> 
                                </div>
                            </div>
                            <?php } ?>
                            <?php echo $content; ?>
                        </div>
                    </div>
                </div>
            </div>
<?php $this->endContent(); ?>