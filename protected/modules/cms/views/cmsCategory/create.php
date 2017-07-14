<?php

/* @var $this CmsCategoryController */
/* @var $model CmsCategory */
?>

<?php
$this->breadcrumbs = array(
    '类别管理',
    $this->title,
);

$this->menu = array(
    //array('icon' => 'glyphicon glyphicon-list','label'=>'全部', 'url'=>array('index')),
    //array('icon' => 'glyphicon glyphicon-search','label'=>'搜索', 'url'=>array('admin')),
    array('icon' => 'glyphicon glyphicon-plus-sign', 'label' => '创建', 'url' => array('create')),
);
?>


<?php $this->renderPartial('_form', array('model' => $model)); ?>

<script type="text/javascript">
    $(document).ready(function(){
<?php if($siteId != 0) { ?>
        $("#CmsCategory_siteId").val(<?php echo $siteId;?>);
<?php } ?>        
    });
</script>