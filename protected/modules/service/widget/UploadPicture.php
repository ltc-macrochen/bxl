<?php

class UploadPicture extends CWidget {
    public $attachId = "";  //表单附件地址ID
    public $thumbUrl = "";              //缩略图地址
    public $thumbWidth = "100px";       //缩略图宽度
    public $thumbHeight = "100px";      //缩略图高度


    public function __construct($owner = null) {
        parent::__construct($owner);
    }

    public function init() {
    }

    public function run() {
        $this->renderField();
        $this->renderScript();
    }
    
    private function renderField() {
       $showThumb = $this->thumbUrl==""?"none":"block";
       $content =  "<div class='widget_fileupload'>
                        <input type='file' name='{$this->attachId}_file' id='{$this->attachId}_file' /><!-- file标签 --> 
                        <input type='button' name='submitBtn' value='立即上传' onclick='uploadPicture(\"{$this->attachId}\");' /><!-- 上传按钮 --> 
                        <input type='button' name='clearBtn' value='删除图片' onclick='clearPicture(\"{$this->attachId}\");' /><!-- 删除按钮 --> 
                        <p style='color:red;' id='{$this->attachId}_error' >请您选择图片！</p><!-- 错误提示 -->  
                        <p><img style='display:{$showThumb};max-width:{$this->thumbWidth};max-height:{$this->thumbHeight};' id='{$this->attachId}_thumb' src='{$this->thumbUrl}' /></p>                            
                    </div>";
       echo $content;
    }

    private function renderScript() {
        
    }    
}

?>

<script>
function uploadPicture(attachId){
    inputId = attachId + "_file";
    // Upload
    var action = "<?php echo CHtml::normalizeUrl(array("/service/upload/picture"));?>";

    // Prepage upload iframe and form
    var iframe = document.getElementById('fileupload-iframe');
    if(iframe) document.body.removeChild(iframe);
    try {
        iframe = document.createElement('<iframe name="fileupload-iframe" id="fileupload-iframe" src="about:blank">');  
    } catch (ex) {  
        iframe = document.createElement('iframe');
    }	
    iframe.id = 'fileupload-iframe';
    iframe.name = 'fileupload-iframe';
    iframe.src = 'about:blank';
    iframe.style.display = 'none';
    document.body.appendChild(iframe);

    var form = document.getElementById("mihe-up-form");    
    if(form) document.body.removeChild(form);
    form = document.createElement("form");
    form.action = action;
    form.target = "fileupload-iframe";
    form.encoding = "multipart/form-data";
    form.method = "post";
    form.id = "mihe-up-form";
    form.style.display = "none";  
    form.innerHTML = '<input name="fileId" type="hidden" value="'+inputId+'"/>';
    form.innerHTML += '<input name="attachId" type="hidden" value="'+attachId+'"/>';

    // Clone upload content
    var node = document.getElementById(inputId);
    var elclone = node.cloneNode(true);
    node.parentNode.insertBefore(elclone, node);
    node.name = inputId;
    form.appendChild(node);
    document.body.appendChild(form);
    form.submit();
}
function clearPicture(attachId) {
    hit = document.getElementById(attachId+"_error");
    thumb = document.getElementById(attachId+"_thumb");
    field = document.getElementById(attachId);
    hit.innerHTML="";
    thumb.src="";
    thumb.style.display = "none";
    field.value="";
}
function uploadPictureCallback(result) {
    hit = document.getElementById(result.attachId+"_error");
    thumb = document.getElementById(result.attachId+"_thumb");
    field = document.getElementById(result.attachId);
    hit.innerHTML=result.message;
    if (result.error == 0) {
        hit.innerHTML=result.url;
        thumb.src=result.url;
        thumb.style.display = "block";
        field.value=result.url;
    }else {
        hit.innerHTML=result.message;
    }
}
</script>