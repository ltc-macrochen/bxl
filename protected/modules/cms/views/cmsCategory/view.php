<?php
/* @var $this CmsCategoryController */
/* @var $model CmsCategory */
?>

<?php
$this->breadcrumbs = array(
    '类别管理',
    $this->title,
);

$this->menu = array(
    //array('icon' => 'glyphicon glyphicon-list', 'label' => '全部', 'url' => array('index')),
    //array('icon' => 'glyphicon glyphicon-search', 'label' => '搜索', 'url' => array('admin')),
    array('icon' => 'glyphicon glyphicon-plus-sign', 'label' => '创建', 'url' => array('create')),
    array('icon' => 'glyphicon glyphicon-list-alt', 'label' => '查看', 'url' => array('view', 'id' => $model->id)),
    array('icon' => 'glyphicon glyphicon-edit', 'label' => '更新', 'url' => array('update', 'id' => $model->id)),
    //array('icon' => 'glyphicon glyphicon-minus-sign', 'label '=> '删除', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
);

?>


<?php
$this->widget('bootstrap.widgets.BsDetailView', array(
    'htmlOptions' => array(
        'class' => 'table table-striped table-bordered table-condensed table-hover',
    ),
    'data' => $model,
    'attributes' => array(
		'id',
		'siteId',
		'parentId',
		'parents',
		'childCount',
		'leafCount',
		'title',
		'slug',
		'keywords',
		'description',
		'template',
		'banner',
		'status',
		'createTime',
    ),
));
?>

<script>
    $(function () {
        $("table#yw0 th").addClass("col-lg-2 col-sm-2");
    })
</script>