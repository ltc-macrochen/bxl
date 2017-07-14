<?php
/* @var $this AdminRoleController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'全部角色',
);

$this->menu=array(
    array('icon' => 'glyphicon glyphicon-list','label'=>'全部', 'url'=>array('index')),
	array('icon' => 'glyphicon glyphicon-plus-sign','label'=>'创建', 'url'=>array('create')),
);
?>

<?php $this->widget('bootstrap.widgets.BsGridView', array(
	'id'=>'adminRole-grid',
	'dataProvider'=>$dataProvider,
        'template' => '{summary}{pager}{items}{pager}',
        'summaryText' => '第 {start}-{end} 条,&nbsp;&nbsp;共 {count} 条.',
        'type'=>'hover bordered striped',    
    	'pager' => array(
		'class'=>'BsPager',
		'firstPageLabel' => '首页',            
		'prevPageLabel' => '上一页',
		'nextPageLabel' => '下一页',
		'lastPageLabel' => '末页',
	),
	'columns'=>array(
		array(
			'header' => '角色ID',
			'value' => '$data->id'
		),            
		array(
			'header' => '角色名称',
			'value' => '$data->name'
		),   
		array(
			'header' => '描述',
			'value' => '$data->description'
		),
		array(
            'class'=>'BsButtonColumn',
            'header' => '操作',
            'htmlOptions' => array("style"=>"min-width:50px;"),
            'template'=>'{update}',
		),            
	),
)); 
?>