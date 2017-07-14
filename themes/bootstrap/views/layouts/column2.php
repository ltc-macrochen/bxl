<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
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