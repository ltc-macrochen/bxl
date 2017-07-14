<?php
/* @var $this AdminUserController */
/* @var $model AdminUser */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm', array(
	'id'=>'admin-user-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
        'layout' => BsHtml::FORM_LAYOUT_HORIZONTAL,        
)); ?>

	<?php echo $form->errorSummary($model); ?>
        
        <?php echo $form->dropdownListControlGroup($model, 'roleId', CHtml::listData(AdminRole::model()->getDataToList(), 'value', 'show')); ?>          
        <?php echo $form->textFieldControlGroup($model,'name'); ?>
        <?php echo $form->passwordFieldControlGroup($model,'password'); ?>
        <?php echo $form->textFieldControlGroup($model,'realName'); ?>
        <?php echo $form->textFieldControlGroup($model,'mobile'); ?>
        <?php echo $form->textFieldControlGroup($model,'email'); ?>

        <?php echo $form->dropdownListControlGroup($model, 'status', CHtml::listData(AdminUser::$_USER_STATUS_LIST, 'value', 'show'), array("disabled"=>"disabled"));?>  

        <?php echo BsHtml::formActions(array(BsHtml::submitButton($model->isNewRecord ? '创建' : '更新', array('color' => BsHtml::BUTTON_COLOR_PRIMARY)))); ?>

<?php $this->endWidget(); ?>

</div><!-- form -->