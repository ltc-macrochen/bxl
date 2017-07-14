<?php
/* @var $this AdminRoleController */
/* @var $model AdminRole */

$this->breadcrumbs=array(
	'角色配置'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'更新',
);

$this->menu=array(
    array('icon' => 'glyphicon glyphicon-list','label'=>'全部', 'url'=>array('index')),  
    array('icon' => 'glyphicon glyphicon-plus-sign','label'=>'创建', 'url'=>array('create')),
	array('icon' => 'glyphicon glyphicon-edit','label'=>'更新', 'url'=>array('update', 'id'=>$model->id)),
);
?>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>