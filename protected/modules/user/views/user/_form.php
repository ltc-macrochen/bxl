<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form BSActiveForm */
?>

<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm', array(
    'id'=>'user-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>false,
    'layout' => BsHtml::FORM_LAYOUT_HORIZONTAL,
)); ?>

    <p class="help-block">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>
    
            
    <?php echo $form->textAreaControlGroup($model,'roleId',array('rows'=>6)); ?>
    <?php echo $form->textAreaControlGroup($model,'openId',array('rows'=>6)); ?>
    <?php echo $form->textAreaControlGroup($model,'nick',array('rows'=>6)); ?>
    <?php echo $form->textAreaControlGroup($model,'head',array('rows'=>6)); ?>
    <?php echo $form->textAreaControlGroup($model,'name',array('rows'=>6)); ?>
    <?php echo $form->textAreaControlGroup($model,'title',array('rows'=>6)); ?>
    <?php echo $form->textAreaControlGroup($model,'sex',array('rows'=>6)); ?>
    <?php echo $form->textAreaControlGroup($model,'desc',array('rows'=>6)); ?>
    <?php echo $form->textAreaControlGroup($model,'email',array('rows'=>6)); ?>
    <?php echo $form->textAreaControlGroup($model,'mobile',array('rows'=>6)); ?>
    <?php echo $form->textAreaControlGroup($model,'status',array('rows'=>6)); ?>
    <?php echo $form->textAreaControlGroup($model,'registerTime',array('rows'=>6)); ?>
    <?php echo $form->textAreaControlGroup($model,'loginTime',array('rows'=>6)); ?>
    <?php echo $form->textAreaControlGroup($model,'blockEndTime',array('rows'=>6)); ?>

    <?php echo BsHtml::formActions(array(BsHtml::submitButton($model->isNewRecord ? '创建' : '更新', array('color' => BsHtml::BUTTON_COLOR_PRIMARY)))); ?>

<?php $this->endWidget(); ?>

<?php $cs = Yii::app()->clientScript;
$themePath = Yii::app()->theme->baseUrl;?>
<?php $cs->registerCssFile($themePath . '/css/plugins/datetimepicker/bootstrap-datetimepicker.css');?><?php $cs->registerScriptFile($themePath . '/js/plugins/datetimepicker/moment-with-locales.js', CClientScript::POS_END); ?>
<?php $cs->registerScriptFile($themePath . '/js/plugins/datetimepicker/bootstrap-datetimepicker.js', CClientScript::POS_END); ?>
    