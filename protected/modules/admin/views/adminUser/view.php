<?php
/* @var $this AdminUserController */
/* @var $model AdminUser */

$this->breadcrumbs=array(
	'管理员'=>array('index'),
	$model->name,
);

$this->menu=array(
    array('icon' => 'glyphicon glyphicon-list','label'=>'全部', 'url'=>array('index')),  
	array('icon' => 'glyphicon glyphicon-list-alt','label'=>'查看', 'url'=>array('view', 'id'=>$model->id)),        
	array('icon' => 'glyphicon glyphicon-edit','label'=>'更新', 'url'=>array('update', 'id'=>$model->id)),
);
?>



<?php $this->widget('bootstrap.widgets.BsDetailView', array(
	'data'=>$model,
    'type'=>'hover bordered striped',
    'itemTemplate'=>'<tr class=\"{class}\"><th class="col-lg-4">{label}</th><td>{value}</td></tr>',
	'attributes'=>array(
		'id',
		array(
            'name' => '角色',
            'value' => $model->role->name,
            'htmlOptions' => array("style"=>"width:20em;"),
		),               
		'name',
		'password',
		'realName',
		'email',        
		'mobile',
		array(
            'name' => '状态',
            'value' => Common::statusSelected($model->status, AdminUser::$_USER_STATUS_LIST)
		),   
        'openId',
        'unionId',        
		'lastLoginIp',
		'lastLoginTime',
	),
)); ?>
