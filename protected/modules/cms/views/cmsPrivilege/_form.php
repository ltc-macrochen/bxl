<?php
/* @var $this CmsPrivilegeController */
/* @var $model CmsPrivilege */
/* @var $form BSActiveForm */
?>

<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm', array(
    'id'=>'cms-privilege-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>false,
    'layout' => BsHtml::FORM_LAYOUT_HORIZONTAL,
)); ?>

    <p class="help-block">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>
    
            
    <?php echo $form->numberFieldControlGroup($model,'adminId'); ?>
    <?php echo $form->numberFieldControlGroup($model,'siteId'); ?>
    <?php echo $form->numberFieldControlGroup($model,'catId'); ?>

    <?php echo BsHtml::formActions(array(BsHtml::submitButton($model->isNewRecord ? '创建' : '更新', array('color' => BsHtml::BUTTON_COLOR_PRIMARY)))); ?>

<?php $this->endWidget(); ?>

    