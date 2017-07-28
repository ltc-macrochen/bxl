<?php
/**
 * The following variables are available in this template:
 * - $this: the BootstrapCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */
/* @var $form BSActiveForm */
<?php echo "?>\n"; ?>

<?php echo "<?php \$form=\$this->beginWidget('bootstrap.widgets.BsActiveForm', array(
    'id'=>'" . $this->class2id($this->modelClass) . "-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>false,
    'layout' => BsHtml::FORM_LAYOUT_HORIZONTAL,
)); ?>\n"; ?>

    <p class="help-block">Fields with <span class="required">*</span> are required.</p>

    <?php echo "<?php echo \$form->errorSummary(\$model); ?>\n"; ?>
    
    <?php $modalAttributePatterns = null; ?>
    <?php $displays = array(); ?>
    
<?php foreach ($this->tableSchema->columns as $column) :
    if ($column->autoIncrement) {
        continue;
    }
    
    // 20150620/Samuel
    // 先检测$modelClass，其生成规则优先于列的数据类型规则
    // Lazy init
    $pattern = null;
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
        
        // 通知页面加载需要第三方的js组件
        if(!empty($pattern)){
            Yii::log("Modal attribute {$column->name} has pattern " . var_export($pattern, true), CLogger::LEVEL_TRACE, "system.web.bootstrap");
            array_push($displays, $pattern['display']);
        }
    }
    
    // 检测数据库原生类型，通知页面加载需要第三方的js组件
    if(preg_match('/^(date|time|datetime)$/i', $column->dbType)){
        array_push($displays, 'datetime');
    }

    ?>
    <?php echo "<?php echo " . $this->generateActiveControlGroup($this->modelClass, $column, $pattern) . "; ?>\n"; ?>
<?php endforeach; ?>

    <?php echo "<?php echo BsHtml::formActions(array(BsHtml::submitButton(\$model->isNewRecord ? '创建' : '更新', array('color' => BsHtml::BUTTON_COLOR_PRIMARY)))); ?>\n"; ?>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>

<?php 

// 20150620/Samuel
// 在此处注入需要的第三方js组件后，通过common.js安装组件
// 不同组件的安装方式取决其具体实现，但是基本上的逻辑是通过某个特定的css找到需要安装组件的dom节点，再使用jQuery直接处理界面
if(count($displays)>0){
    echo "<?php \$cs = Yii::app()->clientScript;\n"; 
    echo "\$themePath = Yii::app()->theme->baseUrl;?>\n";
}
if(in_array('pic', $displays)||in_array('pics', $displays)){
    echo "<?php \$cs->registerScriptFile(\$themePath . '/vendors/plupload/plupload.full.min.js', CClientScript::POS_END); ?>\n";
    echo "<?php \$cs->registerScriptFile(\$themePath . '/vendors/plupload/zh_CN.js', CClientScript::POS_END); ?>\n";
    echo "<?php \$cs->registerScriptFile(\$themePath . '/vendors/plupload/pluploadHelp.js', CClientScript::POS_END); ?>\n";
}
if(in_array('datetime', $displays)){
    echo "<?php \$cs->registerCssFile(\$themePath . '/vendors/bower_components/datetimepicker/bootstrap-datetimepicker.css');?>\n";
    echo "<?php \$cs->registerScriptFile(\$themePath . '/vendors/bower_components/datetimepicker/moment-with-locales.js', CClientScript::POS_END); ?>\n";
    echo "<?php \$cs->registerScriptFile(\$themePath . '/vendors/bower_components/datetimepicker/bootstrap-datetimepicker.js', CClientScript::POS_END); ?>\n";
} 
?>
    