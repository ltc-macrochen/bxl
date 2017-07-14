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

    <p class="help-block"></p>

    <?php echo $form->errorSummary($model); ?>
    <?php $sites = $this->getUserSites();?>
    <?php echo $form->autoControlGroup(BsActiveForm::INPUT_TYPE_LIST_DROPDOWN,$model,'siteId',(count($sites) == 1 ? array() : array("empty"=>array("0"=>"请选择项目"))),CHtml::listData($sites,'id', 'title')); ?>
    <?php 
        $cats = $this->getUserCategoryPrivilege();
        $catList = ($model->siteId==0||!isset($cats[$model->siteId]))?array():CHtml::listData($cats[$model->siteId],'id', 'title');
        echo $form->autoControlGroup(BsActiveForm::INPUT_TYPE_LIST_DROPDOWN,$model,'catId',array("empty"=>array("0"=>"请选择类别")),$catList); 
    ?> 
    <?php echo $form->textFieldControlGroup($model,'title',array('maxlength'=>35)); ?>
    <?php echo $form->textAreaControlGroup($model,'description',array('rows'=>3)); ?>
    <?php 
        if ($picSize=="") {
            echo $form->uploadPicControlGroup($model,'thumb',array('maxlength'=>255, 'placeHolder'=>"缩略图", 'readonly'=>'true')); 
        }else {
            $tempPicSizeArray = explode("*", $picSize);
            echo $form->uploadPicControlGroup($model,'thumb',array('maxlength'=>255, 'placeHolder'=>($picSize==""?"缩略图":("缩略图(尺寸{$picSize})")), 'readonly'=>'true', "data-width"=> $tempPicSizeArray[0],"data-height"=> $tempPicSizeArray[1])); 
        }
    ?>
    <!--<?php echo $form->textFieldControlGroup($model,'audio',array('maxlength'=>255,'append'=>BsHtml::button("上传", array("color"=>"white","onclick"=>"javascript:void(0);")))); ?>-->
    <?php echo $form->textFieldControlGroup($model,'audio',array('maxlength'=>255,'placeHolder'=>'输入音频链接地址')); ?>
    <?php echo $form->textFieldControlGroup($model,'link',array('maxlength'=>512,"disabled"=>"disabled",'prepend'=>'<input name="linkCheckBox" type="checkbox" />')); ?>
   
    <!-- 富文本编辑器 -->
    <div class="form-group">
        <label class="control-label col-lg-2" for="ClubProductInfo_article">文章</label>
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
    
    <?php echo $form->dropDownListControlGroup($model,'status', CHtml::listData(Constant::$POST_STATUS_LIST, 'value', 'show')); ?>
    

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
        //项目和类别联动
        $("#CmsPost_siteId").change(function(){
            window.location.href = "create?siteId=" + $("#CmsPost_siteId").val();
        });
        $("#CmsPost_catId").change(function(){
            window.location.href = "create?siteId="+$("#CmsPost_siteId").val()+"&catId="+$("#CmsPost_catId").val();
        });    
        
        //外连接和富文本编辑器联动
        if ($("input[name='CmsPost[link]']").val()=="") {
            $("input[name='linkCheckBox']").prop("checked",false);
            $("input[name='CmsPost[link]']").attr("disabled","disabled");
            $("textarea[name='CmsPost[content]']").parents("form-group").show();
        }else {
            $("input[name='linkCheckBox']").prop("checked",true);
            $("input[name='CmsPost[link]']").removeAttr("disabled");
            $("textarea[name='CmsPost[content]']").parents(".form-group").hide();            
        }
        $("input[name='linkCheckBox']").click(function(){
            if ($(this).is(':checked')) {
                $(this).parent().next().removeAttr("disabled");
                
                $(this).parents(".form-group").next().hide();
            }else {
                $(this).parent().next().val("");
                $(this).parent().next().attr("disabled","disabled");
                
                $(this).parents(".form-group").next().show();
            }
        });
        
        $("form").submit(function(){
            var selList = {
                "#CmsPost_catId":0,
                "#CmsPost_title":"",
                "#CmsPost_description":"",
                //"#CmsPost_thumb":"",
                "#CmsPost_description":"",
            };
            
            for(var key in selList) {
                if ($(key).val()==selList[key]) {
                    alert("请您输入"+$(key).parents(".form-group").find("label").text());
                    return false;
                }                
            }
            
            if (!$("input[name='linkCheckBox']").is(':checked') && $("textarea[name='CmsPost[content]']").val()=="") {
                alert("请您输入文章内容");
                return false;                
            }
            if ($("input[name='linkCheckBox']").is(':checked') && $("input[name='CmsPost[link]']").val()=="") {
                alert("请您输入外链接");
                return false;                
            }
            
           return true; 
        });
    })
</script>