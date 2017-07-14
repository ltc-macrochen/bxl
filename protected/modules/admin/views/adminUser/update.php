<?php
/* @var $this AdminUserController */
/* @var $model AdminUser */

$this->breadcrumbs=array(
	'管理员配置'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'更新',
);

$this->menu=array(
    array('icon' => 'glyphicon glyphicon-list','label'=>'全部', 'url'=>array('index')),
	array('icon' => 'glyphicon glyphicon-list-alt','label'=>'查看', 'url'=>array('view', 'id'=>$model->id)),        
	array('icon' => 'glyphicon glyphicon-edit','label'=>'更新', 'url'=>array('update', 'id'=>$model->id)),
);
?>


<?php $this->renderPartial('_form', array('model'=>$model)); ?>
<script>$(function(){$("#AdminUser_name").attr("disabled","disabled");})</script>