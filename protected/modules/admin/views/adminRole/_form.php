<?php
/* @var $this AdminRoleController */
/* @var $model AdminRole */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm', array(
	'id'=>'admin-role-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
        'layout' => BsHtml::FORM_LAYOUT_HORIZONTAL,    
)); ?>

	<?php echo $form->errorSummary($model); ?>

        <?php echo $form->textFieldControlGroup($model,'name'); ?>
        <?php echo $form->textFieldControlGroup($model,'description'); ?>
        
        <?php echo BsHtml::formActions(array(BsHtml::submitButton($model->isNewRecord ? '创建' : '更新', array('color' => BsHtml::BUTTON_COLOR_PRIMARY)))); ?>

<?php $this->endWidget(); ?>

</div><!-- form -->