<?php

/* @var $this UserController */
/* @var $model User */
?>

<?php
$this->breadcrumbs = array(
    'Users' => array('index'),
    $model->name => array('view', 'id' => $model->id),
    'Update',
);

$this->menu = array(
    //array('icon' => 'glyphicon glyphicon-list','label'=>'全部', 'url'=>array('index')),
    //array('icon' => 'glyphicon glyphicon-search','label'=>'搜索', 'url'=>array('admin')),
    array('icon' => 'glyphicon glyphicon-plus-sign', 'label' => '创建', 'url' => array('create')),
    array('icon' => 'glyphicon glyphicon-list-alt', 'label' => '查看', 'url' => array('view', 'id' => $model->id)),
    array('icon' => 'glyphicon glyphicon-edit', 'label' => '更新', 'url' => array('update', 'id' => $model->id)),
);
?>


<?php $this->renderPartial('_form', array('model' => $model)); ?>