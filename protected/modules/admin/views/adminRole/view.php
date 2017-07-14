<?php
/* @var $this AdminRoleController */
/* @var $model AdminRole */

$this->breadcrumbs=array(
	'角色配置'=>array('index'),
	$model->name,
);

$this->menu=array(
        array('icon' => 'glyphicon glyphicon-list','label'=>'全部', 'url'=>array('index')),
//        array('icon' => 'glyphicon glyphicon-plus-sign','label'=>'创建', 'url'=>array('create')),
//	array('icon' => 'glyphicon glyphicon-tasks','label'=>'搜索', 'url'=>array('admin')),    
	array('icon' => 'glyphicon glyphicon-list-alt','label'=>'查看', 'url'=>array('view', 'id'=>$model->id)),        
	array('icon' => 'glyphicon glyphicon-edit','label'=>'更新', 'url'=>array('update', 'id'=>$model->id)),
);
?>

<?php $this->widget('bootstrap.widgets.BsDetailView', array(
	'data'=>$model,
        'type'=>'hover bordered striped',
	'attributes'=>array(
                array(
                    "name"=>"角色ID",
                    "value"=>"$model->id",
                ),
                array(
                    "name"=>"角色名称",
                    "value"=>"$model->name",
                ),
                array(
                    "name"=>"角色描述",
                    "value"=>"$model->description",
                ),            
	),
)); ?>
<script>
  $(function(){
      $("table#yw0 th").addClass("col-lg-2 col-sm-2");
  })  
</script>
