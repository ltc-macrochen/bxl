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
    'action'=>Yii::app()->createUrl(\$this->route),
    'method'=>'get',
)); ?>\n"; ?>

<?php foreach ($this->tableSchema->columns as $column): ?>
    <?php
        $field = $this->generateInputField($this->modelClass, $column);
        if (strpos($field, 'password') !== false) {
            continue;
        }
    ?>
<?php echo "<?php echo " . $this->generateActiveControlGroup($this->modelClass, $column) . "; ?>\n"; ?>
<?php endforeach; ?>

    <div class="form-actions">
        <?php echo "<?php echo BsHtml::submitButton('搜索',  array('color' => BsHtml::BUTTON_COLOR_PRIMARY,));?>\n" ?>
    </div>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>