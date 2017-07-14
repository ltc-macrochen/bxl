<?php
/* @var $this CmsSiteController */
/* @var $dataProvider CActiveDataProvider */
?>

<?php
$this->breadcrumbs = array(
    '项目管理',
    $this->title,
);
?>

<div class="row choose-bar">
    <div class="col-lg-4">
        <div class="input-group filters">
            <input type="text" class="form-control" placeholder="关键字搜索" id="search-text"> 
            <span class="input-group-btn"> 
                <button class="btn btn-primary" type="button" id="search-btn"><i class="fa fa-search"></i></button> 
            </span>
        </div>
    </div>
    <div class="col-lg-6">&nbsp;</div>
    <div class="col-lg-2">
        <a type="submit" class="btn btn-primary pull-right" href="create"><i class="fa fa-plus"></i>&nbsp;&nbsp;新建项目</a>
    </div>
</div>

<?php
$this->widget('bootstrap.widgets.BsGridView', array(
    'id' => 'cms-site-grid',
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
//        array(
//            'header' => 'ID',
//            'value' => '$data->id',
//            'htmlOptions' => array("style"=>"width:5em;vertical-align:middle;"),
//        ),          
        array(
            'header' => '图标',
            'type' => 'html',
            'value' => 'CHtml::image($data->logo,"",array("height"=>"40px"))',
            'htmlOptions' => array("style"=>"width:80px;vertical-align:middle;"),
        ),
        array(
            'header' => '项目名称',
            'value' => '$data->title',
            'htmlOptions' => array("style"=>"vertical-align:middle;"),
        ),
        array(
            'header' => '管理员',
            'value' => '$data->adminId==0?"待分配":$data->admin->realName',
            'htmlOptions' => array("style"=>"vertical-align:middle;"),
        ),
        array(
            'header' => '类别数量',
            'value' => '$data->catCount."个"',
            'htmlOptions' => array("style"=>"width:8em;vertical-align:middle;"),
        ),
        array(
            'header' => '状态',
            'value' => 'Common::statusSelected($data->status, Constant::$_STATUS_LIST_ENABLED)',
            'htmlOptions' => array("style"=>"width:6em;vertical-align:middle;"),
        ),        
        array(
            'header' => '创建时间',
            'value' => '$data->createTime',
            'htmlOptions' => array("style"=>"width:12em;vertical-align:middle;"),
        ),
        array(
            'header' => '操作',
            'class' => 'BsButtonColumn',
            'template' => '{weixin} {update} {delete}',
            'htmlOptions' => array("style"=>"width:8em;vertical-align:middle;"),
            'updateButtonLabel' => "编辑",
            'updateButtonIcon' => "fa fa-pencil-square-o",
            //'updateButtonImageUrl'=>"",
            'deleteButtonLabel' => "删除",
            'deleteButtonIcon' => "fa fa-trash-o",
            'deleteConfirmation'=> "您确定要删除此项目么？",
            'buttons'=> array(
                'weixin' => array(
                    'label'=>'微信配置',
                    'url'=>'CHtml::normalizeUrl(array("/weixin/wxConfig/create","site"=>$data->id))', 
                    'icon'=>'fa fa-weixin',
                ),
            )
        ),
    ),
));
?>

<script type="text/javascript">
    $(document).ready(function () {
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
        var $grid = $('#cms-site-grid');
        var url = '/cms/cmsSite/index';
        $grid.yiiGridView('update', {
            type: 'GET',
            url: url,
            data : {'text': text}
        });
    }
</script>