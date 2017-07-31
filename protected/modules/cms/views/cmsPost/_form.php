<?php
/* @var $this CmsPostController */
/* @var $model CmsPost */
/* @var $form BSActiveForm */
?>

<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm', array(
    'id'=>'cms-post-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>false,
    'layout' => BsHtml::FORM_LAYOUT_HORIZONTAL,
)); ?>

    <p class="help-block">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>
    
            
    <?php echo $form->dropDownListControlGroup($model,'catId', CHtml::listData(CmsCategory::getAllCategorys(), 'id', 'name')); ?>
    <?php echo $form->textFieldControlGroup($model,'title'); ?>
    <?php echo $form->textAreaControlGroup($model,'description',array('rows'=>3)); ?>

    <!-- 富文本编辑器 -->
    <div class="form-group">
        <label class="control-label col-lg-2" for="CmsPost_content">文章</label>
        <div class="col-lg-9">
            <div style="z-index:1000;">
                <?php
                // use the following part to supput multimidea editor
                $this->widget('ext.ueditor.UeditorWidget',
                    array(
                        'model' => $model,
                        'attribute' => 'content',
                        'config'=>array(
                            'initialFrameHeight'=>'300',
                            'initialFrameWidth'=>'100%',
                            'toolbars'=>array(
                                array('source','undo','redo','formatmatch','removeformat','selectall','cleardoc','simpleupload','insertimage','imagenone','imageleft','imageright','imagecenter','paragraph','fontfamily','fontsize'),
                                array('forecolor','bold','italic','underline','strikethrough','link','indent','justifyleft','justifyright','justifycenter','justifyjustify','lineheight','insertorderedlist','insertunorderedlist'),
                            ),
                        ),
                        'htmlOptions' => array('rows'=>6, 'cols'=>50)
                    ));
                ?>
            </div>
        </div>
    </div>
    <!-- 富文本编辑器 -->

    <?php echo $form->textFieldControlGroup($model,'link'); ?>
    <?php echo $form->uploadPicControlGroup($model,'imgUrl'); ?>
    <?php echo $form->uploadAudioControlGroup($model,'audioUrl'); ?>
    <?php echo $form->uploadVideoControlGroup($model,'videoUrl'); ?>
    <?php echo $form->dropDownListControlGroup($model,'status', CHtml::listData(Constant::$_STATUS_LIST_SHOW, 'value', 'show')); ?>
    <?php echo $form->numberFieldControlGroup($model,'viewCount'); ?>
    <?php echo $form->numberFieldControlGroup($model,'commentCount'); ?>
    <?php echo $form->numberFieldControlGroup($model,'vGood'); ?>
    <?php echo $form->numberFieldControlGroup($model,'vBad'); ?>

    <?php echo BsHtml::formActions(array(BsHtml::submitButton($model->isNewRecord ? '创建' : '更新', array('color' => BsHtml::BUTTON_COLOR_PRIMARY)))); ?>

<?php $this->endWidget(); ?>

<?php $cs = Yii::app()->clientScript;
$themePath = Yii::app()->theme->baseUrl;?>
<?php $cs->registerCssFile($themePath . '/css/plugins/datetimepicker/bootstrap-datetimepicker.css');?><?php $cs->registerScriptFile($themePath . '/js/plugins/datetimepicker/moment-with-locales.js', CClientScript::POS_END); ?>
<?php $cs->registerScriptFile($themePath . '/js/plugins/datetimepicker/bootstrap-datetimepicker.js', CClientScript::POS_END); ?>
<?php $cs->registerScriptFIle($themePath . '/js/plugins/plupload/plupload.full.min.js', CClientScript::POS_END);?>
<?php $cs->registerScriptFIle($themePath . '/js/common.js', CClientScript::POS_END);?>
    