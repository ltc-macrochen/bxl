<?php
/* @var $this CmsCategoryController */
/* @var $model CmsCategory */
/* @var $form BSActiveForm */
?>

<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm', array(
    'id'=>'cms-category-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>false,
    'layout' => BsHtml::FORM_LAYOUT_HORIZONTAL,
)); ?>

    <p class="help-block">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>
    
            
    <?php echo $form->textFieldControlGroup($model,'name'); ?>
    <?php echo $form->textAreaControlGroup($model,'description',array('rows'=>3)); ?>
    <?php echo $form->uploadPicControlGroup($model,'thumb'); ?>
    <?php echo $form->dropDownListControlGroup($model,'status', CHtml::listData(Constant::$_STATUS_LIST_SHOW, 'value', 'show')); ?>

    <?php echo BsHtml::formActions(array(BsHtml::submitButton($model->isNewRecord ? '创建' : '更新', array('color' => BsHtml::BUTTON_COLOR_PRIMARY)))); ?>

<?php $this->endWidget(); ?>

<?php $cs = Yii::app()->clientScript;
$themePath = Yii::app()->theme->baseUrl;?>
<?php $cs->registerCssFile($themePath . '/css/plugins/datetimepicker/bootstrap-datetimepicker.css');?><?php $cs->registerScriptFile($themePath . '/js/plugins/datetimepicker/moment-with-locales.js', CClientScript::POS_END); ?>
<?php $cs->registerScriptFile($themePath . '/js/plugins/datetimepicker/bootstrap-datetimepicker.js', CClientScript::POS_END); ?>
<?php $cs->registerScriptFIle($themePath . '/js/plugins/plupload/plupload.full.min.js', CClientScript::POS_END);?>
<?php $cs->registerScriptFIle($themePath . '/js/common.js', CClientScript::POS_END);?>
    