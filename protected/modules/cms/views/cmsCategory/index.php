<?php
/* @var $this CmsCategoryController */
/* @var $dataProvider CActiveDataProvider */
?>
<?php
$this->breadcrumbs = array(
    '类别管理',
    $this->title,
);
?>
<style>
    table td{position: relative;}
    table td embed{vertical-align: top;}
</style>
<div class="row choose-bar">
    <div class="col-lg-4">        
        <?php echo BsHtml::dropDownList("select-site", $siteId, (count($sites) == 1 ? CHtml::listData($sites, "id", "title") : array("0" => "请选择项目") + CHtml::listData($sites, "id", "title"))) ?>
    </div>
    <div class="col-lg-4">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="关键字搜索" id="search-text"> 
            <span class="input-group-btn"> 
                <button class="btn btn-primary" type="button" id="search-btn"><i class="fa fa-search"></i></button> 
            </span>
        </div>
    </div>
    <div class="col-lg-4">
        <a type="submit" class="btn btn-primary pull-right" href="create?siteId=<?php echo $siteId; ?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;新建类别</a>
    </div>
</div>

<?php
$this->widget('bootstrap.widgets.BsGridView', array(
    'id' => 'cms-category-grid',
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
            'header' => 'link',
            'value' => 'CmsCategory::model()->getCategoryRealUrl($data)',
            'headerHtmlOptions' => array("style"=>"display:none;"),
            'htmlOptions' => array("style"=>"display:none;"),
        ),
        array(
            'header' => '项目名称',
            'value' => '$data->site->title',
        ),
        array(
            'header' => '类别名称',
            'value' => '$data->title',
        ),
        array(
            'header' => '选用模板',
            'value' => 'Common::statusSelected($data->template, Constant::$_CATEGORY_TEMPLATES)',
            'htmlOptions' => array("style"=>"width:13em;"),
        ),
        array(
            'header' => '内容数量',
            'value' => '$data->leafCount."个"',
            'htmlOptions' => array("style"=>"width:8em;"),
        ),
        array(
            'header' => '状态',
            'value' => 'Common::statusSelected($data->status, Constant::$_STATUS_LIST_ENABLED)',
            'htmlOptions' => array("style"=>"width:6em;"),
        ),           
        array(
            'header' => '创建时间',
            'value' => '$data->createTime',
            'htmlOptions' => array("style"=>"width:12em;"),
        ),
        array(
            'header' => '链接',
            "type" => "html",
            'value' => 'BsHtml::tag("a", array("url"=>"javascript:void(0);","class"=>"link"), "复制");',
            'htmlOptions' => array("style" => "width:4em;"),
        ),             
        array(
            'header' => '操作',
            'class' => 'BsButtonColumn',
            'template' => '{update} {delete}',
            'htmlOptions' => array("style"=>"width:6em;"),
            'updateButtonLabel' => "编辑",
            'updateButtonIcon' => "fa fa-pencil-square-o",
            'deleteButtonLabel' => "删除",
            'deleteButtonIcon' => "fa fa-trash-o",
            'deleteConfirmation'=> "您确定要删除此类别么？",
        ),
    ),
));
?>

<script type="text/javascript" src="/js/zclip/jquery.zclip.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        
        $("#select-site").change(function(){
            location.href = '/cms/cmsCategory/index?siteId=' + $(this).val();
        });
        
        $("#search-btn").click(function () {
            search();
        });
        
        $('#search-text').change(function() {
            search();
        });
        
        $('#search-text').keydown(function(e) {
           if(e.which==13){
               search();
               e.preventDefault();
           }
        });
    });
    
    function search() {
        
        var text = $('#search-text').val();
        if($.trim(text).length == 0){
            warning("请输入关键字。");
            return;
        }
        if($("#select-site").val() == 0){
            warning("请先选择项目。");
            return;
        }
        
        var $grid = $('#cms-category-grid');
        var url = '/cms/cmsCategory/index';
        $grid.yiiGridView('update', {
            type: 'GET',
            url: url,
            data : {'text':text, 'siteId':<?php echo $siteId; ?>}
        });
    }
    
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