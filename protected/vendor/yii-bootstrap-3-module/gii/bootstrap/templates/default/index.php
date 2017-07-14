<?php
/**
 * The following variables are available in this template:
 * - $this: the BootstrapCode object
 */
?>
<?php echo "<?php\n"; ?>

/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $dataProvider CActiveDataProvider */
<?php echo "?>\n"; ?>

<?php
echo "<?php\n";
$label = $this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs = array(
    '$label',
);\n";
?>

$this->menu = array(
    array('icon' => 'glyphicon glyphicon-list', 'label' => '全部', 'url' => array('index')),
    array('icon' => 'glyphicon glyphicon-search', 'label' => '搜索', 'url' => array('admin')),
    array('icon' => 'glyphicon glyphicon-plus-sign', 'label' => '创建', 'url' => array('create'), 'target' => '_blank'),
);

?>

<?php /*
<?php echo "<?php echo BsHtml::pageHeader('$label') ?>\n"; ?>
*/ ?>

<?php echo "<?php\n"; ?>
$this->widget('bootstrap.widgets.BsGridView', array(
    'id' => '<?php echo $this->class2id($this->modelClass); ?>-grid',
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
        <?php
        $modalAttributePatterns = null;
        $count = 0;
        foreach ($this->tableSchema->columns as $column) {
            if (++$count == 7) {
                echo "\t\t/*\n";
            }
            
            // 20150624/Samuel/检测model的attributePatterns()，需额外处理select/strings/choice
            // pic/pics/text一般不在列表中显示，因此在此页面中无需做特别处理，如需要，可以参照view.php的读取方法
            // Lazy init
            $pattern = null;
            $display = '';
            if(!isset($modalAttributePatterns)){
                $theModal = new $this->modelClass;
                if(method_exists($theModal, 'attributePatterns')){
                    $modalAttributePatterns = $theModal->attributePatterns();
                }else{
                    $modalAttributePatterns = array();
                }
            }
            if(!empty($modalAttributePatterns)){
                $pattern = $modalAttributePatterns[$column->name];
                if(!empty($pattern)){
                    $display = $pattern['display'];
                }
            }
            
            if($display === 'select'){
                $data = $pattern['data'];
                echo "\t\tarray(\n";
                echo "\t\t\t'name' => '{$column->name}',\n";
                echo "\t\t),\n";
            }else{
                echo "\t\t'" . $column->name . "',\n";
            }
        }
        if ($count >= 7) {
            echo "\t\t*/\n";
        }
        ?>
        array(
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