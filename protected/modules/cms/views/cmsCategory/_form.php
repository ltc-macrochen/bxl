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

    <p class="help-block"></p>

    <?php echo $form->errorSummary($model); ?>
    <?php 
    $user = AdminUser::model()->findByPk(Yii::app()->user->id);
    $condition = (Yii::app()->user->roleId == Constant::ADMIN_ROLE_ADMINISTRATOR) ? array("status"=>Constant::STATUS_SHOW) : array("status"=>Constant::STATUS_SHOW, 'id' => $user->siteId);
    ?>
    <?php echo $form->dropDownListControlGroup($model,'siteId', CHtml::listData(CmsSite::model()->findAllByAttributes($condition), 'id', 'title')); ?>
    <?php echo $form->textFieldControlGroup($model,'title',array('maxlength'=>35)); ?>
    <!--<?php echo $form->dropDownListControlGroup($model,'template', CHtml::listData(Constant::$_CATEGORY_TEMPLATES, 'value', 'show'),array('labelOptions'=>array('label'=>'选用模板'))); ?>-->
    <div class="form-group">
        <label class="control-label col-lg-2 required" for="CmsCategory_template">
            选用模板 
            <span class="required">*</span>
        </label>

        <div class="col-lg-9">
            <ul id="template_list" class="clearfix">
                <!--
                <li>
                    <img src="/images/template_grid_s.jpg">
                    <p>这是模版一</p>
                </li>
                -->
                <?php foreach (Constant::$_CATEGORY_TEMPLATES as $template) {?>
                <li data-template="<?php echo $template["value"]; ?>" data-banner="<?php echo Constant::$_CATEGORY_TEMPLATES_CONFIG[$template["value"]]["bannerSize"]; ?>">
                    <img src="<?php echo $template["pic"]?>" />
                    <p><?php echo $template['show'];?></p>
                    <div class="check"></div>
                </li>    
                <?php    }?>
                <input name="CmsCategory[template]" id="CmsCategory_template" type="hidden" value="<?php echo $model->template;?>">
            </ul>
        </div>
    </div>
    <?php echo $form->uploadPicsControlGroup($model,'banner', array('labelOptions'=>array('label'=>'模板头图<br/><small>请选模板</small>'))); ?>
    <?php echo $form->dropDownListControlGroup($model,'status', CHtml::listData(Constant::$_STATUS_LIST_ENABLED, 'value', 'show')); ?>

    <?php echo BsHtml::formActions(array(BsHtml::submitButton($model->isNewRecord ? '创建' : '更新', array('color' => BsHtml::BUTTON_COLOR_PRIMARY)))); ?>

<?php $this->endWidget(); ?>

<?php $cs = Yii::app()->clientScript;
$themePath = Yii::app()->theme->baseUrl;?>
<?php $cs->registerScriptFile($themePath . '/js/plugins/plupload/plupload.full.min.js', CClientScript::POS_END); ?>
<?php $cs->registerScriptFile($themePath . '/js/plugins/plupload/zh_CN.js', CClientScript::POS_END); ?>
<?php $cs->registerCssFile($themePath . '/css/plugins/datetimepicker/bootstrap-datetimepicker.css');?><?php $cs->registerScriptFile($themePath . '/js/plugins/datetimepicker/moment-with-locales.js', CClientScript::POS_END); ?>
<?php $cs->registerScriptFile($themePath . '/js/plugins/datetimepicker/bootstrap-datetimepicker.js', CClientScript::POS_END); ?>
<script>

    $(function(){
        $('#template_list li').on('click', function(){
            if($(this).hasClass('select')) {
                $(this).removeClass('select');
                $('#CmsCategory_template').val('');
            }else {
                $(this).addClass('select').siblings().removeClass('select');
                $('#CmsCategory_template').val($(this).attr('data-template'));
                console.log($('label[for="CmsCategory_banner"] small'));
                $('label[for="CmsCategory_banner"] small').html($(this).attr('data-banner'));
            }
        });
        
        if("<?php echo $model->template;?>" != ''){
            $('#template_list li[data-template=<?php echo $model->template;?>]').addClass('select');
            $('label[for="CmsCategory_banner"] small').html($('#template_list li[data-template=<?php echo $model->template;?>]').attr('data-banner'));
        }
    })
</script>
    