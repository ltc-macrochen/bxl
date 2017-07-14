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
);
?>

<?php $this->renderPartial('_form', array('model' => $model, 'picSize'=>$picSize)); ?>