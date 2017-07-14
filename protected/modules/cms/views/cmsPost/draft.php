<?php
/* @var $this WxMenuController */
/* @var $dataProvider CActiveDataProvider */
?>
<?php 
$this->breadcrumbs = array(
    '内容管理' => array("index"),
    $this->title,
);
?>
<div class="row choose-bar">
    <div class="col-lg-4">
        <?php echo BsHtml::dropDownList("select-site", $siteId, (count($sites) == 1 ? CHtml::listData($sites, "id", "title") : array("0" => "请选择项目") + CHtml::listData($sites, "id", "title"))) ?>
    </div>
    <div class="col-lg-4" id="select-categories">
        <?php
        if(!empty($cats)){
            foreach ($cats as $sId => $categories) {
                if ($siteId != $sId) {
                    echo BsHtml::dropDownList("select-category-$sId", "0", array("0" => "请选择类别") + CHtml::listData($categories, "id", "title"), array("style" => "display:none"));
                } else {
                    echo BsHtml::dropDownList("select-category-$sId", $catId, array("0" => "请选择类别") + CHtml::listData($categories, "id", "title"));
                }
            }
        }else{
            echo BsHtml::dropDownList("select-category-$siteId", $catId, array("0" => "请选择类别"));
        }
        ?>
    </div>
    <div class="col-lg-4">&nbsp;</div>
</div>

<?php
$this->widget('bootstrap.widgets.BsGridView', array(
    'id' => 'cms-post-grid',
    'dataProvider' => $dataProvider,
    'template' => '{pager}{summary}{items}{pager}',
    'summaryText' => '第 {start}-{end} 条,&nbsp;&nbsp;共 {count} 条.',
    'emptyText' => '无记录',
    'type' => 'hover bordered striped',
    'pager' => array(
        'class' => 'BsPager',
        'firstPageLabel' => '首页',
        'prevPageLabel' => '上一页',
        'nextPageLabel' => '下一页',
        'lastPageLabel' => '末页',
    ),
    'columns' => array(
        array(
            'header' => 'ID',
            'value' => '$data->id',
            'headerHtmlOptions' => array("style"=>"display:none;"),
            'htmlOptions' => array("style"=>"display:none;"),
        ),        
        array(
            'header' => '更新时间',
            'value' => '$data->updateTime',
            'headerHtmlOptions' => array("style"=>"text-align:left;"),
            'htmlOptions' => array("style"=>"text-align:left;width:15em;"),            
        ),
        array(
            'header' => '标题',
            'value' => '$data->title',
            'headerHtmlOptions' => array("style"=>"text-align:left;"),
            'htmlOptions' => array("style"=>"text-align:left;"),
        ),
        array(
            'class' => 'BsButtonColumn',
            'header' => '操作',
            'template' => '{publish} {update} {delete}',
            'htmlOptions' => array("style" => "width:8em;"),
            'updateButtonLabel' => "编辑",
            'updateButtonIcon' => "fa fa-pencil-square-o",
            'updateButtonOptions' => array("target" => "_blank"),
            'buttons' => array(
                'publish' => array(
                    'label' => "发布",
                    'url' => 'CHtml::decode("javascript:void(0);")',
                    'imageUrl' => '',
                    'icon' => 'fa fa-upload',
                    'options' => array("class" => "publish"),
                    'click'=>'function(){updateStatus("publish");}',
                ),
                'delete' => array(
                    'label' => "删除",
                    'url' => 'CHtml::decode("javascript:void(0);")',
                    'imageUrl' => '',
                    'icon' => 'fa fa-trash-o',
                    'options' => array("class" => "delete"),
                    'click'=>'function(){updateStatus("delete");}',
                ),                
            ),
        ),
    ),
));
?>

<script>
    function updateStatus(status) {
        event = window.event || arguments.callee.caller.arguments[0];        
        var button = $(event.target);
        var param = {"status":status};
        
        if (status=="delete" && !confirm('您确定要删除此内容么?')) {
            return;
        }        
        
        jQuery.ajax({
            url: "/cms/cmsPost/updateStatus/id/" + button.parents("tr").find("td").eq(0).text(),
            type: "POST",
            dataType: "json",
            async: false,
            data: param,
            success: function(result) { 
                if (result.error == 0) {
                    alert(result.message);
                    window.location.reload();
                }else {
                    alert(result.message);
                }
            },
            error: function(XHR) {
                alert(XHR.responseText);
            }
        });
    }

    $(function () {
        <?php if(count($sites) == 1):?>
            //项目管理员登录时，只能查看对应项目的内容    
            $("#select-categories").children("select").hide();
            $("#select-category-" + <?php echo $sites[0]->id;?>).show();
            currentCategory = 0;
        <?php endif;?>
            
        var path = "draft";
        var currentSite = 0;
        var currentCategory = 0;
        $("#select-site").change(function () {
            var site = $(this).val();
            if (site==0) {
                window.location.href = path;
            }else {
                $("#select-categories").children("select").hide();
                $("#select-category-" + site).show();
                currentCategory = 0;
            }
        });
        $("#select-categories select").change(function () {
            // Display posts under selected category
            currentSite = $("#select-site").val();
            currentCategory = $(this).val();
            if (currentCategory == 0) {
                window.location.href = path;
            }else {
                window.location.href = path+"?catId=" + currentCategory;
            }
        });
    });
</script>
