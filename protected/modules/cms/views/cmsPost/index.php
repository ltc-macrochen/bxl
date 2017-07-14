<?php

/* @var $this CmsPostController */
/* @var $dataProvider CActiveDataProvider */
?>
<?php 
$this->breadcrumbs = array(
    '内容管理',
    $this->title,
);
?>
<style>
    .col-lg-2{width:26%;}
    .col-lg-4{width:37%;}
    .col-lg-8{width:63%;}
    .pagination{margin:0;}
    table td{position: relative;}
    table td embed{vertical-align: top;}
</style>

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
    <div class="col-lg-2">
        <?php if ($catId!=0):?>
        <div class="input-group right-side">
            <div class="btn-group">               
                <a href="create?siteId=<?php echo $siteId?>&catId=<?php echo $catId?>" class="btn btn-primary" target="_blank"><span class="glyphicon glyphicon-plus"></span> 新建内容</a>
            </div>        
        </div> 
        <?php endif;?>
    </div>
</div>
    
<div class="row">
    <div class="col-lg-4">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
               <h5>预览 <small>根据项目类别的模板预览</small></h5> 
            </div>
            <div class="ibox-content" style="height: 637px;padding:40px 7px 20px 7px">
                <div class="phone-preview">
                    <iframe src="<?php echo $reviewUrl;?>"></iframe>
                </div>
            </div>
        </div> 
    </div>
    <div class="col-lg-8">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
               <h5>内容列表 <small>选定项目类别下的内容列表</small></h5> 
            </div>
            <div class="ibox-content"  style="height: 637px;">
<?php
$this->widget('bootstrap.widgets.BsGridView', array(
    'id' => 'cms-post-grid',
    'dataProvider' => $dataProvider,
    'template' => '{summary}{items}{pager}',
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
            'header' => 'link',
            'value' => '$data->link==""?CmsCategory::model()->getPostRealUrl($category, $data->id) : $data->link',
            'headerHtmlOptions' => array("style"=>"display:none;"),
            'htmlOptions' => array("style"=>"display:none;"),
        ),          
        array(
            'header' => '更新时间',
            'value' => '$data->updateTime',
            'htmlOptions' => array("style" => "width:11em;"),
        ),
        array(
            'header' => '标题',
            'value' => '$data->title',
            'htmlOptions' => array("style"=>"text-align:left;"),
        ), 
        array(
            'header' => '链接',
            "type" => "html",
            'value' => 'BsHtml::tag("a", array("url"=>"javascript:void(0);","class"=>"link"), "复制");',
            'htmlOptions' => array("style" => "width:4em;"),
        ),         
        array(
            'class' => 'BsButtonColumn',
            'header' => '操作',
            'template' => '{publish} {recom} {update} {delete}',
            'htmlOptions' => array("style" => "width:9em;"),
            'updateButtonLabel' => "编辑",
            'updateButtonIcon' => "fa fa-pencil-square-o",
            'updateButtonOptions' => array("target" => "_blank"),
            'buttons' => array(
                'link' => array(
                    'label' => "复制连接",
                    'url' => 'CHtml::decode("javascript:void(0);")',
                    'imageUrl' => '',
                    'options' => array("class" => "link"),
                    'click'=>'function(){copyToClipboard($(this));}',
                ),
                'publish' => array(
                    'label' => "撤销发布",
                    'url' => 'CHtml::decode("javascript:void(0);")',
                    'imageUrl' => '',
                    'icon' => 'fa fa-undo',
                    'options' => array("class" => "publish"),
                    'click'=>'function(){updateStatus("unpublish");}',
                ),
                'recom' => array(
                    'label' => "置顶",
                    'url' => 'CHtml::decode("javascript:void(0);")',
                    'imageUrl' => '',
                    'icon' => 'fa fa-thumbs-o-up',
                    'options' => array("class" => "recom"),
                    'click'=>'function(){updateSortTime();}',
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
            </div>
        </div> 
    </div>
</div>

<script type="text/javascript" src="/js/zclip/jquery.zclip.js"></script>
<script type="text/javascript">
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
    
    function updateSortTime() {
        event = window.event || arguments.callee.caller.arguments[0];
        var button = $(event.target);   

        jQuery.ajax({
            url: "/cms/cmsPost/updateSortTime/id/" + button.parents("tr").find("td").eq(0).text(),
            type: "POST",
            dataType: "json",
            async: false,
            data: {},
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
    
    function copyToClipboard (btn) {
    }
    
    $(function () {
        <?php if(count($sites) == 1):?>
            //项目管理员登录时，只能查看对应项目的内容    
            $("#select-categories").children("select").hide();
            $("#select-category-" + <?php echo $sites[0]->id;?>).show();
            currentCategory = 0;
        <?php endif;?>  
            
        var path = "index";
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
<script type="text/javascript">
    /*<![CDATA[*/
    jQuery(function($){
        jQuery(document).on('mouseover','body',function(){
            if ($(".link").hasClass("binded")) {
                return;
            }
            $(".link").addClass("binded");
            $(".link").zclip({  
                path: "/js/zclip/ZeroClipboard.swf",
                copy: function(e){
                    //console.log($(e.target).parents("tr").find("td").eq(1).html());
                    return $(e.target).parents("tr").find("td").eq(1).html();
                 },
                 afterCopy : function(e){
                     success("成功复制链接："+$(e.target).parents("tr").find("td").eq(1).html());
                 }
            });
        });             
    });
/*]]>*/    
</script>