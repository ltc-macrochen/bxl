<?php

/* @var $this CmsCategoryController */
/* @var $model CmsCategory */


$this->breadcrumbs = array(
    'Cms Categories' => array('index'),
    'Manage',
);

$this->menu = array(
    array('icon' => 'glyphicon glyphicon-list', 'label' => '全部', 'url' => array('index')),
    array('icon' => 'glyphicon glyphicon-search', 'label' => '搜索', 'url' => array('admin')),
    array('icon' => 'glyphicon glyphicon-plus-sign', 'label' => '创建', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#cms-category-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>


<p>您可以在查询条件中使用比较运算符 (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b> or <b>=</b>)</p>

<?php
$this->widget('bootstrap.widgets.BsGridView', array(
    'id' => 'cms-category-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'template' => '{pager}{summary}{items}{pager}',
    'summaryText' => '第 {start}-{end} 条, 共 {count} 条.',
    'emptyText' => '无记录',
    'type' => 'hover bordered striped',
    'pager' => array(
        'class' => 'BsPager',
        'firstPageLabel' => '首页',
        'prevPageLabel' => '上一页',
        'nextPageLabel' => '下一页',
        'lastPageLabel' => '末页',
    ),
    'columns' => array(
		'id',
		'name',
		'description',
//		'thumb',
        array(
            'header' => '状态',
            'value' => 'Common::statusSelected($data->status, Constant::$_STATUS_LIST_SHOW)',
            'filter'=>BsHtml::activeTextField($model,'status',array("placeHolder"=>"")),
            'htmlOptions' => array("style"=>"width:6em;"),
        ),
		'createTime',
		/*
		'updateTime',
		*/
        array(
            'header' => ' #',
            'class' => 'BsButtonColumn',
            'template' => '{view} {update} {delete}',
            'viewButtonLabel' => "查看",
            'viewButtonOptions' => array("target" => "_blank"),
            'updateButtonLabel' => "更新",
            'updateButtonOptions' => array("target" => "_blank"),
            'deleteButtonLabel' => "删除",
        ),
    ),
));
?>
