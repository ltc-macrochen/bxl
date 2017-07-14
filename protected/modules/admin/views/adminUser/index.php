<?php

/* @var $this AdminUserController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    '管理员列表',
);

$this->menu = array(
    array('icon' => 'glyphicon glyphicon-list', 'label' => '全部', 'url' => array('index')),
    array('icon' => 'glyphicon glyphicon-plus-sign', 'label' => '创建', 'url' => array('create')),
);
?>

<?php

$this->widget('bootstrap.widgets.BsGridView', array(
    'id' => 'adminUser-grid',
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
            'htmlOptions' => array("style" => "width:4em;","name"=>"id"),
        ),
        array(
            'class' => 'CLinkColumn',
            'header' => '所属角色',
            'labelExpression' => '$data->role->name',
            'urlExpression' => 'CHtml::normalizeUrl(array("/admin/adminRole/Index"))',
            'linkHtmlOptions' => array(
                'title' => '点击查看全部角色', 'target' => '_blank',
            ),
             'htmlOptions' => array("style" => "width:10em;"),
        ),
        array(
            'header' => '真实姓名',
            'value' => '$data->realName',
            'htmlOptions' => array("style" => "width:10em;","name"=>"name"),
        ),
        array(
            'header' => '用户名',
            'value' => '$data->name',
            'htmlOptions' => array("style" => "width:10em;"),
        ),
//        array(
//            'header' => '密码',
//            'value' => '$data->password'
//        ),
//        array(
//            'header' => '手机号',
//            'value' => '$data->mobile'
//        ),
        array(
            'header' => '邮箱',
            'value' => '$data->email',
            'htmlOptions' => array("name"=>"email"),
        ),
        array(
            'header' => '状态',
            'value' => 'Common::statusSelected($data->status, AdminUser::$_USER_STATUS_LIST)',
            'htmlOptions' => array("style" => "width:5em;","name"=>"status"),
        ),
//        array(
//            'header' => '最近登录IP',
//            'value' => '$data->lastLoginIp'
//        ),
        array(
            'header' => '最近登录时间',
            'value' => '$data->lastLoginTime',
            'htmlOptions' => array("style" => "width:11em;"),
        ),
        array(
            'class' => 'BsButtonColumn',
            'header' => '操作',
            'template' => '{view} {bind} {update} {block}',
            'viewButtonLabel' => "查看",
            'viewButtonIcon' => "fa fa-eye",
            'updateButtonLabel' => "更新",
            'updateButtonIcon' => "fa fa-pencil",
            'htmlOptions' => array("style" => "width:7em;padding-right:0;"),
            'buttons' => array(
                'bind' => array(
                    'label' => '绑定',
                    'url' => 'CHtml::decode("javascript:void(0);")',
                    'imageUrl' => '',
                    'icon' => 'fa fa-envelope-o',
                    'options' => array('class' => "bind", "data-toggle" => "modal", "data-target" => "#bindModal", "style"=>"display:none;"),
                ),
                'block' => array(
                    'label' => '禁用',
                    'icon' => 'fa fa-ban',
                    'options' => array('class' => "block", "data-toggle" => "modal", "data-target" => "#blockModal"),
                ),                  
            ),            
        ),
    ),
));
?>
<div class="modal fade" id="bindModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">绑定微信号</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <p style="text-align:center;line-height: 40px;">对已经绑定的账号重新发送绑定邮件，将会解除已绑定的微信号<br/>您确定要给该账号发送绑定邮件吗？</p>
                </div>          
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" name="bind">确定</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="blockModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">禁止用户登录</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <p style="text-align:center;line-height: 40px;"></p>
                </div>          
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" name="block">确定</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<script>
    
//发送绑定邮件
function bind(event) {
    var dialog = $(event.target);
    var button = $(event.relatedTarget);
    var id = button.parents("tr").find("td[name='id']").html();
    
    jQuery.ajax({
        url: "/site/mail",
        type: "POST",
        dataType: "json",
        async: true,
        data: {'id': id},
        success: function (result) {
            if (result.err == 0) {
                dialog.modal("hide");
            }
            info(result.msg);
            window.location.reload();            
        },
        error: function (XHR) {
            info(XHR.responseText);
        }

    });
}

//发送绑定邮件
function block(event) {
    var dialog = $(event.target);
    var button = $(event.relatedTarget);
    var id = button.parents("tr").find("td[name='id']").html();
    
    jQuery.ajax({
        url: "/admin/adminUser/block",
        type: "POST",
        dataType: "json",
        async: true,
        data: {'id': id},
        success: function (result) {
            if (result.err == 0) {
                dialog.modal("hide");
            }
            info(result.msg);
            window.location.reload();
        },
        error: function (XHR) {
            info(XHR.responseText);
        }

    });
}

$(function(){
    //发送绑定邮件弹层加载事件
    $('#bindModal').on('show.bs.modal', function (event) {
        var dialog = $(event.target);
        var button = $(event.relatedTarget);
        $("#bindModal button[name='bind']").unbind('click')
        
        var id = button.parents("tr").find("td[name='id']").html();  
        var name = button.parents("tr").find("td[name='name']").html();
        var email = button.parents("tr").find("td[name='email']").html();
        var status = button.parents("tr").find("td[name='status']").html();
        if (email == "") {
            dialog.find('.modal-body p').html("用户 "+name+" 未设置邮箱地址，无法发送绑定邮件！");
        }else if (status == "已禁用") {
            dialog.find('.modal-body p').html("用户 "+name+" 已被禁用，无法发送绑定邮件！");
        }else if (status == "已绑定") {
            dialog.find('.modal-body p').html("用户 "+name+" 已绑定微信，系统将删除已绑定微信账号然后发送新的绑定邮件，<br/>确定邀请"+email+"重新绑定微信么？");
            $("#bindModal button[name='bind']").unbind('click').bind('click', function () {
                bind(event);
            });
        }else {
            dialog.find('.modal-body p').html("用户 "+name+" 尚未绑定微信账号，<br/>确定邀请"+email+"绑定微信么？");
            $("#bindModal button[name='bind']").unbind('click').bind('click', function () {
                bind(event);
            });            
        }
    });
    
    //禁用弹层加载事件
    $('#blockModal').on('show.bs.modal', function (event) {console.log("111");
        var dialog = $(event.target);
        var button = $(event.relatedTarget);
        $("#blockModal button[name='block']").unbind('click')
        
        var id = button.parents("tr").find("td[name='id']").html();   
        var name = button.parents("tr").find("td[name='name']").html();
        var status = button.parents("tr").find("td[name='status']").html();
        if (status == "已禁用") {
            dialog.find('.modal-title').html("取消用户禁用");
            dialog.find('.modal-body p').html("用户 "+name+" 的禁用将被取消，<br/>确定要这么做么？");
        }else {
            dialog.find('.modal-title').html("用户禁用");
            dialog.find('.modal-body p').html("用户 "+name+" 将被禁用，禁用后用户的微信绑定状态也将被删除<br/>确定要这么做么？");            
        }
        $("#blockModal button[name='block']").unbind('click').bind('click', function () {
            block(event);
        });        
    });    
})
</script>
