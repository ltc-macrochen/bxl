<?php

/* @var $this AdminUserController */
/* @var $model AdminUser */

$this->breadcrumbs = array(
    '管理员' => array('index'),
    '搜索',
);

$this->menu = array(
    array('icon' => 'glyphicon glyphicon-list','label' => '全部', 'url' => array('index')),
    array('icon' => 'glyphicon glyphicon-plus-sign','label' => '创建', 'url' => array('create')),
    array('icon' => 'glyphicon glyphicon-tasks','label' => '搜索', 'url' => array('admin')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#admin-user-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<p>您可以在查询条件中使用比较运算符 (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b> or <b>=</b>)</p>

<?php

$this->widget('bootstrap.widgets.BsGridView', array(
    'id' => 'admin-user-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'template' => '{summary}{pager}{items}{pager}',
    'summaryText' => '第 {start}-{end} 条, 共 {count} 条.',
    'type' => 'hover bordered striped',
    'pager' => array(
        'class' => 'CLinkPager',
        'header' => '',
        'firstPageLabel' => '首页',
        'prevPageLabel' => '上一页',
        'nextPageLabel' => '下一页',
        'lastPageLabel' => '末页',
        'cssFile' => '/css/new/yiipager.css',
    ),
    'columns' => array(
        'id',
        array(
            'header' => '角色',
            'value' => '$data->role->name',
            'filter' => BsHtml::activeTextField($model, 'roleId', array('placeHolder' => '')),
        ),
        'name',
        'realName',
        'mobile',
        'email',
        array(
            'header' => '审核状态',
            'value' => 'Common::statusSelected($data->status, Constant::$_USER_STATUS_LIST)',
        ),
        /*
          'lastLoginIp',
          'lastLoginTime',
         */
        array(
            'class' => 'BsButtonColumn',
            'header' => '操作',
            'htmlOptions' => array("style" => "min-width:50px;"),
            'template' => '{view} {update}',
        ),
    ),
));
?>
