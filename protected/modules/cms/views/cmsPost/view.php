<?php
/* @var $this CmsPostController */
/* @var $model CmsPost */
?>

<?php
$this->breadcrumbs = array(
    '内容管理' => array("index"),
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

<style>
    table td img{max-width: 100% !important;}
</style>

<?php
$this->widget('bootstrap.widgets.BsDetailView', array(
    'htmlOptions' => array(
        'class' => 'table table-striped table-bordered table-condensed table-hover',
    ),
    'data' => $model,
    'attributes' => array(
		'id',
        array(
            "name"=>"项目名称",
            "value"=>$model->site->title,
        ),
        array(
            "name"=>"类别名称",
            "value"=>$model->category->title,
        ),
		'title',
		'description',
        array(
            "name"=>($model->link=="")?"内容":"外链",
            "type"=>"raw",
            "value"=>($model->link=="")?$model->content:$model->link,
        ),
        array(
            "name"=>"图片",
            "type"=>"raw",
            "value"=>CHtml::image($model->thumb,'',array('style'=>'max-height:200px;')),
        ),
		'audio',
        array(
            "name"=>"编辑",
            "value"=>($model->editorId==0)?UserIdentity::DEFAULT_ADMINISTRATOR_NAME:$model->editor->realName,
        ),
        array(
            "name"=>"状态",
            "value"=>  Common::statusSelected($model->status, Constant::$POST_STATUS_LIST),
        ),         
		'createTime',
		'updateTime',
    ),
));
?>

<script>
    $(function () {
        $("table#yw0 th").addClass("col-lg-2 col-sm-2");
        $("table#yw0 td").css({"word-break":"break-all"});
    })
</script>