<?php
/* @var $this CmsPrivilegeController */
/* @var $model CmsPrivilege */
/* @var $form BSActiveForm */
?>

<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm', array(
    'action'=>Yii::app()->createUrl($this->route),
    'method'=>'get',
)); ?>

    <?php echo $form->numberFieldControlGroup($model,'id'); ?>
    <?php echo $form->numberFieldControlGroup($model,'adminId'); ?>
    <?php echo $form->numberFieldControlGroup($model,'siteId'); ?>
    <?php echo $form->numberFieldControlGroup($model,'catId'); ?>

    <div class="form-actions">
        <?php echo BsHtml::submitButton('搜索',  array('color' => BsHtml::BUTTON_COLOR_PRIMARY,));?>
    </div>

<?php $this->endWidget(); ?>
