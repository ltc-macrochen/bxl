<?php
/**
 * The following variables are available in this template:
 * - $this: the BootstrapCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */
<?php echo "?>\n"; ?>

<?php
echo "<?php\n";
$nameColumn = $this->guessNameColumn($this->tableSchema->columns);
$label = $this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs = array(
    '$label' => array('index'),
    \$model->{$nameColumn},
);\n";
?>

$this->menu = array(
    //array('icon' => 'glyphicon glyphicon-list', 'label' => '全部', 'url' => array('index')),
    //array('icon' => 'glyphicon glyphicon-search', 'label' => '搜索', 'url' => array('admin')),
    array('icon' => 'glyphicon glyphicon-plus-sign', 'label' => '创建', 'url' => array('create')),
    array('icon' => 'glyphicon glyphicon-list-alt', 'label' => '查看', 'url' => array('view', 'id' => $model->id)),
    array('icon' => 'glyphicon glyphicon-edit', 'label' => '更新', 'url' => array('update', 'id' => $model-><?php echo $this->tableSchema->primaryKey; ?>)),
    //array('icon' => 'glyphicon glyphicon-minus-sign', 'label '=> '删除', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model-><?php echo $this->tableSchema->primaryKey; ?>), 'confirm' => 'Are you sure you want to delete this item?')),
);

?>

<?php /*
<?php echo "<?php echo BsHtml::pageHeader('View','$this->modelClass '.\$model->{$this->tableSchema->primaryKey}) ?>\n"; ?>
*/ ?>

<?php echo "<?php\n"; ?>
$this->widget('bootstrap.widgets.BsDetailView', array(
    'htmlOptions' => array(
        'class' => 'table table-striped table-bordered table-condensed table-hover',
    ),
    'data' => $model,
    'attributes' => array(
<?php
foreach ($this->tableSchema->columns as $column) {
    echo "\t\t'" . $column->name . "',\n";
}
?>
    ),
));
?>

<script>
    $(function () {
        $("table#yw0 th").addClass("col-lg-2 col-sm-2");
    })
</script>