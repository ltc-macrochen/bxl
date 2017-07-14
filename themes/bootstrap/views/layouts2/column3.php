<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts2/main3'); ?>
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
                        <?php if(count($this->menu)>0): ?>
                            <div class="btn-group">
                                <?php foreach ($this->menu as $m) {
                                    $class = $this->isMenuActive($m)?"btn btn-primary":"btn btn-white";
                                    echo "<a href='" . CHtml::normalizeUrl($m['url']) . "' class='{$class}'><span class='{$m['icon']}'></span> {$m['label']}</a>";
                                } ?> 
                            </div>
                        <?php endif; ?>                        
                    </div>
                </div>
            </div>
            <div class="fh-breadcrumb">          
                <div class="fh-column" style="background-color:#f3f3f4;">
                    <div class="full-height-scroll "></div>
                </div>
                <div class="full-height">
                    <div class="full-height-scroll border-left"><div class="col-md-12"><?php echo $content; ?></div></div>
                </div>
            </div>

<?php $this->endContent(); ?>