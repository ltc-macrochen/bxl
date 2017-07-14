<?php
/* @var $this AdminRoleController */
/* @var $model AdminRole */

$this->breadcrumbs=array(
	'角色配置'=>array('index'),
	'创建',
);

$this->menu=array(
    array('icon' => 'glyphicon glyphicon-list','label'=>'全部', 'url'=>array('index')),
	array('icon' => 'glyphicon glyphicon-plus-sign','label'=>'创建', 'url'=>array('create')),
);
?>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>