<?php
/* @var $this CmsCategoryController */
/* @var $model CmsCategory */
?>

<?php
$this->breadcrumbs = array(
    'Cms Categories' => array('index'),
    $model->name,
);

$this->menu = array(
    array('icon' => 'glyphicon glyphicon-list', 'label' => '全部', 'url' => array('index')),
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
		'name',
		'description',
        array(
            'name' => '缩略图',
            'type' => 'raw',
            'value' => CHtml::image($model->thumb, '', array('style' => 'max-height:200px;'))
        ),
        array(
            'name' => '审核状态',
            'value' => Common::statusSelected($model->status, Constant::$_STATUS_LIST_SHOW)
        ),
		'createTime',
		'updateTime',
    ),
));
?>

<script>
    $(function () {
        $("table#yw0 th").addClass("col-lg-2 col-sm-2");
    })
</script>