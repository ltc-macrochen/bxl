<?php
/* @var $this CmsSiteController */
/* @var $model CmsSite */
/* @var $form BSActiveForm */
?>

<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm', array(
    'id'=>'cms-site-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>false,
    'layout' => BsHtml::FORM_LAYOUT_HORIZONTAL,
)); ?>

    <p class="help-block"></p>

    <?php echo $form->errorSummary($model); ?>
    
            
    <?php echo $form->textFieldControlGroup($model,'title',array('maxlength'=>35)); ?>
    <?php echo $form->uploadPicControlGroup($model,'logo',array("placeHolder"=>"项目图标（尺寸200*200）","readonly"=>"true","data-width"=>"200","data-height"=>"200")); ?>
    <!--<?php echo $form->dropDownListControlGroup($model,'adminId', CHtml::listData(AdminUser::model()->findAllByAttributes(array("roleId"=>2)), 'id', 'realName')); ?>-->
    <?php echo $form->dropDownListControlGroup($model,'status', CHtml::listData(Constant::$_STATUS_LIST_ENABLED, 'value', 'show')); ?>
    
    <?php echo BsHtml::formActions(array(BsHtml::submitButton($model->isNewRecord ? '创建' : '更新', array('color' => BsHtml::BUTTON_COLOR_PRIMARY)))); ?>

<?php $this->endWidget(); ?>

<?php $cs = Yii::app()->clientScript;
$themePath = Yii::app()->theme->baseUrl;?>
<?php $cs->registerScriptFile($themePath . '/js/plugins/plupload/plupload.full.min.js', CClientScript::POS_END); ?>
<?php $cs->registerScriptFile($themePath . '/js/plugins/plupload/zh_CN.js', CClientScript::POS_END); ?>
<?php $cs->registerCssFile($themePath . '/css/plugins/datetimepicker/bootstrap-datetimepicker.css');?><?php $cs->registerScriptFile($themePath . '/js/plugins/datetimepicker/moment-with-locales.js', CClientScript::POS_END); ?>
<?php $cs->registerScriptFile($themePath . '/js/plugins/datetimepicker/bootstrap-datetimepicker.js', CClientScript::POS_END); ?>
