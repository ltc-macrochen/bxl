<?php

/* @var $this CmsPostController */
/* @var $model CmsPost */
?>

<?php
$this->breadcrumbs = array(
    '内容管理' => array("index"),
    $this->title,
);

$this->menu = array(
    //array('icon' => 'glyphicon glyphicon-list','label'=>'全部', 'url'=>array('index')),
    //array('icon' => 'glyphicon glyphicon-search','label'=>'搜索', 'url'=>array('admin')),
    array('icon' => 'glyphicon glyphicon-plus-sign', 'label' => '创建', 'url' => array('create')),
    array('icon' => 'glyphicon glyphicon-list-alt', 'label' => '查看', 'url' => array('view', 'id' => $model->id)),
    array('icon' => 'glyphicon glyphicon-edit', 'label' => '更新', 'url' => array('update', 'id' => $model->id)),
);
?>


<?php $this->renderPartial('_form', array('model' => $model, 'site' => $site, 'category'=>$category, 'picSize'=>$picSize)); ?>

<script>
$(function(){
    if ($("#CmsPost_siteId").val() != "0" && $("#CmsPost_catId").val() != "0") {
        $("#CmsPost_siteId").attr("disabled","disabled");
        $("#CmsPost_catId").attr("disabled","disabled");
    }
})
</script>