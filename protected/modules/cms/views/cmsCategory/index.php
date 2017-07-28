<?php

/* @var $this CmsCategoryController */
/* @var $dataProvider CActiveDataProvider */
?>

<?php
$this->breadcrumbs = array(
    'Cms Categories',
);

$this->menu = array(
    array('icon' => 'glyphicon glyphicon-list', 'label' => '全部', 'url' => array('index')),
    array('icon' => 'glyphicon glyphicon-search', 'label' => '搜索', 'url' => array('admin')),
    array('icon' => 'glyphicon glyphicon-plus-sign', 'label' => '创建', 'url' => array('create'), 'target' => '_blank'),
);

?>


<?php
$this->widget('bootstrap.widgets.BsGridView', array(
    'id' => 'cms-category-grid',
    'dataProvider' => $dataProvider,
    'template' => '{pager}{summary}{items}{pager}',
    'summaryText' => '第 {start}-{end} 条,&nbsp;&nbsp;共 {count} 条.',
    'emptyText' => '无记录',
    'type' => 'hover bordered striped',
    'pager' => array(
        'class' => 'BsPager',
        'firstPageLabel' => '首页',
        'prevPageLabel' => '上一页',
        'nextPageLabel' => '下一页',
        'lastPageLabel' => '末页',
    ),
    'columns'=>array(
        'id',
		'name',
		'description',
		array(
		    'header' => '缩略图',
            'type' => 'raw',
            'value' => 'CHtml::image($data->thumb, \'\', array(\'style\' => \'max-height:50px;\'))'
        ),
		array(
		    'header' => '审核状态',
            'value' => 'Common::statusSelected($data->status, Constant::$_STATUS_LIST_SHOW)'
        ),
		'createTime',
		/*
		'updateTime',
		*/
        array(
            'header' => '#',
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