<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
	$this->module->id,
);
?>
<h1><?php echo $this->uniqueId . '/' . $this->action->id; ?></h1>

<!-- 单个文件上传表单 --> 
<div id="upForms">  
    <form id="fileitemdiv1" action="<?php echo $this->createUrl('/service/upload/picture'); ?>" method="post" enctype="multipart/form-data" target="upload_target">  
        <img style="display:block;max-height:100px;" id="preview_id1" src="" />
        <input type="hidden" name="selectedIndex" value="1" /><!-- 当前文件序号 --> 
        <input type="file" name="attached_file1" /><!-- file标签 --> 
        <input type="submit" name="submitBtn" value='立即上传' /><!-- 上传按钮 --> 
        <span id="upload_repairinfo_success1" style="color:red;"></span><!-- 错误提示 -->  
        <!-- 记录上传成功后的url -->  
        <input type="hidden" name="upload_save_to_db_id" id="upload_save_to_db_id1" value="0" /> 
    </form>
</div>  

<!-- 新增文件表单按钮 -->
<div>  
    <input type="button" value="增加附件" onclick="addfile();">  
    <input type="hidden" id="up_success_file_ids" />  
</div>  

<!-- 用于提交表单的iframe页面 -->
<iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe> 

<script>
var filecount=1;  
// 新增一个上传文件控件  
function addfile(){  
    var filediv = document.getElementById("upForms");  
    var fileitemdiv = document.createElement("form");  
    filecount++;  
    var content = "<img style='display:block;max-height:100px;' id='preview_id"+filecount+"' src='' />" + "<input type=file name=attached_file"+  
    filecount + ">  <input type=submit name=submitBtn value='立即上传' />  <a href='javascript:removefile("+  
    filecount + ");'>删除</a>  <span id='upload_repairinfo_success"+  
    filecount + "' style='color:red;'></span><input type=hidden value="+  
    filecount + " name=selectedIndex /> <input type=hidden name=upload_save_to_db_id id=upload_save_to_db_id"+  
    filecount + " value=0 />";  
  
    fileitemdiv.id       = "fileitemdiv"+filecount;  
    fileitemdiv.method   = "post";  
    fileitemdiv.enctype  = "multipart/form-data";  
    fileitemdiv.target   = "upload_target";  
    fileitemdiv.action   = "<?php echo $this->createUrl('/service/upload/picture'); ?>";  
    fileitemdiv.innerHTML = content;  
    filediv.appendChild(fileitemdiv);  
}  
  
//删除指定上传文件控件  
function removefile(fileIndex){  
    var filediv = document.getElementById("upForms");  
    var fileitemdiv = document.getElementById("fileitemdiv"+fileIndex);  
    filediv.removeChild(fileitemdiv);  
}  
  
//回调成功  
function successUpload(responseText,id,fileIndex){  
    // 1,获取值  
    var ids = document.getElementById("up_success_file_ids").value;  
    if(ids){  
        document.getElementById("up_success_file_ids").value = ids+','+id;  
    }else{  
        document.getElementById("up_success_file_ids").value = id;  
    }  
      
    // 2,本次上传成功，则覆盖之前上传成功的文件  
    document.getElementById("upload_save_to_db_id"+fileIndex).value = id;  
    document.getElementById("preview_id"+fileIndex).src=id;  
      
    // 3,提示上传成功  
    var spanObj = document.getElementById("upload_repairinfo_success"+fileIndex);  
    //spanObj.innerHTML = "上传成功";  
    spanObj.innerHTML = responseText;  
}  
  
//回调失败  
function stopUpload(responseText,fileIndex){  
    // 提示  
    var spanObj = document.getElementById("upload_repairinfo_success"+fileIndex);  
    spanObj.innerHTML = responseText;  
}  

function uploadCallback(index, error, message, url) {
    if (error==0) {
        successUpload(message, url, index);
    }else {
        stopUpload(message, index);
    }
}
</script>