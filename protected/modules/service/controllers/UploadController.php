<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 文件上传服务
 *
 * @author kevinwang
 */
class UploadController extends CController {
    
    public function __construct($id, $module = null) {
        parent::__construct($id, $module);
        
        if (Yii::app()->user->isGuest) {
            $this->showException("对不起，您未登录！");
        }
    }    
    
    /**
     * 获取组件配置信息
     */
    public function getConfig($type=null) {
        $config = array(
            "picture" => array(
                "maxSize" => 2097152,   //2M
                "validTpye" => array('jpg','png','bmp','gif'),
                "savePath" => "/upload/picture/",
                "callback" => "uploadPictureCallback",
                // 20150621/Samuel/上传的方式
                // jsonp，即通过动态生成的iframe提交文件，返回jsonp
                // json，即通过plupload等组件直接上传返回json
                "result" => "json", 
            ),
            "audio" => array(
                "maxSize" => 5242880,   //5M
                "validTpye" => array('mp3'),
                "savePath" => "/upload/audio/",
                "callback" => "uploadAudioCallback",
                // 20150621/Samuel/上传的方式
                // jsonp，即通过动态生成的iframe提交文件，返回jsonp
                // json，即通过plupload等组件直接上传返回json
                "result" => "json", 
            ),    
            "video" => array(
                "maxSize" => 5242880,   //5M
                "validTpye" => array('mp4'),
                "savePath" => "/upload/video/",
                "callback" => "uploadVideoCallback",
                // 20150621/Samuel/上传的方式
                // jsonp，即通过动态生成的iframe提交文件，返回jsonp
                // json，即通过plupload等组件直接上传返回json
                "result" => "json", 
            ),    
            "file" => array(
                "maxSize" => 5242880,   //5M
                "savePath" => "/protected/runtime/file/",
                "callback" => "uploadFileCallback",
                // 20150621/Samuel/上传的方式
                // jsonp，即通过动态生成的iframe提交文件，返回jsonp
                // json，即通过plupload等组件直接上传返回json
                "result" => "json", 
            ),           
        );
        
        if ($type == null) {
            return $config;
        }else {
            return $config[$type];
        }
    }

    /**
     * 图片上传
     */
    public function actionPicture() {
        $retParam = array(
            "attachId" => "",
            "error" => 10,
            "message" => "",
            "url" => "",
        );
        
        try {
            $config = $this->getConfig("picture");
            
            if($config["result"] == "json"){
                $attach = CUploadedFile::getInstanceByName("file"); 
            }else{
                $fileId = $_POST["fileId"];
                $attachId = $_POST["attachId"];
                $retParam["attachId"] = $attachId;
                $attach = CUploadedFile::getInstanceByName($fileId);  
            }
            
            if($attach == null){  
                //文件为空
                $retParam["error"] = 1;
                $retParam["message"] = "提示：不能上传空文件。";
                $this->setCallback($config, $retParam);
                return;                
            }else if($attach->size > $config["maxSize"]){  
                //检查文件大小
                $retParam["error"] = 2;                
                $retParam["message"] = "提示：文件大小不能超过2M。";
                $this->setCallback($config, $retParam);
                return;                
            }else {  
                //创建存储路径
                $storePath = $config["savePath"];
                $path = $this->getPath($config["savePath"]);
                if ($path["error"] != 0) {
                    $retParam["error"] = 3;                    
                    $retParam["message"] = $path["message"];
                    $this->setCallback($config, $retParam);
                    return;
                }
                
                //存储文件
                $name = uniqid(date("Ymd")) . '.jpg';
                $storeUrl = $path["absolutePath"] .$name;
                $visitUrl = $path["relativePath"] .$name;
                if (!$attach->saveAs($storeUrl)) {
                    $retParam["error"] = 4;                    
                    $retParam["message"] = "提示：文件上传失败。";
                    $this->setCallback($config, $retParam);
                    return;                    
                }
                
                //检查存储后的文件类型
                if (isset($config["validTpye"]) && !empty($config["validTpye"])) {
                    $fileType = $this->getFileTypeStrict($storeUrl);
                    if ($fileType["error"] != 0 || !in_array($fileType["msg"], $config["validTpye"])) {
                        $retParam["error"] = 5;
                        $retParam["message"] = "提示：您上传的文件类型非法。";
                        $this->setCallback($config, $retParam);
                        return;                    
                    }      
                }

                //上传成功
                $retParam["message"] = "恭喜，上传成功！";
                $retParam["url"] = $visitUrl;
                $retParam["error"] = 0;      
                $this->setCallback($config, $retParam);
                return;                       
            }
            
            $this->setCallback($config, $retParam);
            return;            
        } catch (Exception $e) {
            //异常捕获
            $retParam["message"] = $e->getMessage();
            $this->setCallback($config, $retParam);
            return;                
        }
    }
    
    /**
     * 音频上传
     */
    public function actionAudio() {
        $retParam = array(
            "attachId" => "",
            "error" => 10,
            "message" => "",
            "url" => "",
        );
        
        try {
            $config = $this->getConfig("audio");
            
            if($config["result"] == "json"){
                $attach = CUploadedFile::getInstanceByName("file"); 
            }else{
                $fileId = $_POST["fileId"];
                $attachId = $_POST["attachId"];
                $retParam["attachId"] = $attachId;
                $attach = CUploadedFile::getInstanceByName($fileId);  
            }
            
            if($attach == null){  
                //文件为空
                $retParam["error"] = 1;
                $retParam["message"] = "提示：不能上传空文件。";
                $this->setCallback($config, $retParam);
                return;                
            }else if($attach->size > $config["maxSize"]){  
                //检查文件大小
                $retParam["error"] = 2;                
                $retParam["message"] = "提示：文件大小不能超过2M。";
                $this->setCallback($config, $retParam);
                return;                
            }else {  
                //创建存储路径
                $storePath = $config["savePath"];
                $path = $this->getPath($config["savePath"]);
                if ($path["error"] != 0) {
                    $retParam["error"] = 3;                    
                    $retParam["message"] = $path["message"];
                    $this->setCallback($config, $retParam);
                    return;
                }
                
                //存储文件
                $name = uniqid(date("Ymd")) . '.mp3';
                $storeUrl = $path["absolutePath"] .$name;
                $visitUrl = $path["relativePath"] .$name;
                if (!$attach->saveAs($storeUrl)) {
                    $retParam["error"] = 4;                    
                    $retParam["message"] = "提示：文件上传失败。";
                    $this->setCallback($config, $retParam);
                    return;                    
                }
                
                //检查存储后的文件类型
                if (isset($config["validTpye"]) && !empty($config["validTpye"])) {
//                    $fileType = $this->getFileTypeStrict($storeUrl);
//                    if ($fileType["error"] != 0 || !in_array($fileType["msg"], $config["validTpye"])) {
//                        $retParam["error"] = 5;
//                        $retParam["message"] = "提示：您上传的文件类型非法。";
//                        $this->setCallback($config, $retParam);
//                        return;                    
//                    }      
                }

                //上传成功
                $retParam["message"] = "恭喜，上传成功！";
                $retParam["url"] = $visitUrl;
                $retParam["error"] = 0;      
                $this->setCallback($config, $retParam);
                return;                       
            }
            
            $this->setCallback($config, $retParam);
            return;            
        } catch (Exception $e) {
            //异常捕获
            $retParam["message"] = $e->getMessage();
            $this->setCallback($config, $retParam);
            return;                
        }        
    }
    
    /**
     * 视频上传
     */
    public function actionVideo() {
        $retParam = array(
            "attachId" => "",
            "error" => 10,
            "message" => "",
            "url" => "",
        );
        
        try {
            $config = $this->getConfig("video");
            
            if($config["result"] == "json"){
                $attach = CUploadedFile::getInstanceByName("file"); 
            }else{
                $fileId = $_POST["fileId"];
                $attachId = $_POST["attachId"];
                $retParam["attachId"] = $attachId;
                $attach = CUploadedFile::getInstanceByName($fileId);  
            }
            
            if($attach == null){  
                //文件为空
                $retParam["error"] = 1;
                $retParam["message"] = "提示：不能上传空文件。";
                $this->setCallback($config, $retParam);
                return;                
            }else if($attach->size > $config["maxSize"]){  
                //检查文件大小
                $retParam["error"] = 2;                
                $retParam["message"] = "提示：文件大小不能超过5M。";
                $this->setCallback($config, $retParam);
                return;                
            }else {  
                //创建存储路径
                $storePath = $config["savePath"];
                $path = $this->getPath($config["savePath"]);
                if ($path["error"] != 0) {
                    $retParam["error"] = 3;                    
                    $retParam["message"] = $path["message"];
                    $this->setCallback($config, $retParam);
                    return;
                }
                
                //存储文件
                $name = uniqid(date("Ymd")) . '.mp4';
                $storeUrl = $path["absolutePath"] .$name;
                $visitUrl = $path["relativePath"] .$name;
                if (!$attach->saveAs($storeUrl)) {
                    $retParam["error"] = 4;                    
                    $retParam["message"] = "提示：文件上传失败。";
                    $this->setCallback($config, $retParam);
                    return;                    
                }
                
                //检查存储后的文件类型
                if (isset($config["validTpye"]) && !empty($config["validTpye"])) {
//                    $fileType = $this->getFileTypeStrict($storeUrl);
//                    if ($fileType["error"] != 0 || !in_array($fileType["msg"], $config["validTpye"])) {
//                        $retParam["error"] = 5;
//                        $retParam["message"] = "提示：您上传的文件类型非法。";
//                        $this->setCallback($config, $retParam);
//                        return;                    
//                    }      
                }

                //上传成功
                $retParam["message"] = "恭喜，上传成功！";
                $retParam["url"] = $visitUrl;
                $retParam["error"] = 0;      
                $this->setCallback($config, $retParam);
                return;                       
            }
            
            $this->setCallback($config, $retParam);
            return;            
        } catch (Exception $e) {
            //异常捕获
            $retParam["message"] = $e->getMessage();
            $this->setCallback($config, $retParam);
            return;                
        }           
    }
    
    /**
     * 视频上传
     */
    public function actionFile() {
        $retParam = array(
            "attachId" => "",
            "error" => 10,
            "message" => "",
            "url" => "",
        );
        
        try {
            $config = $this->getConfig("file");
            
            if($config["result"] == "json"){
                $attach = CUploadedFile::getInstanceByName("file"); 
            }else{
                $fileId = $_POST["fileId"];
                $attachId = $_POST["attachId"];
                $retParam["attachId"] = $attachId;
                $attach = CUploadedFile::getInstanceByName($fileId);  
            }
            
            if($attach == null){  
                //文件为空
                $retParam["error"] = 1;
                $retParam["message"] = "提示：不能上传空文件。";
                $this->setCallback($config, $retParam);
                return;                
            }else if($attach->size > $config["maxSize"]){  
                //检查文件大小
                $retParam["error"] = 2;                
                $retParam["message"] = "提示：文件大小不能超过5M。";
                $this->setCallback($config, $retParam);
                return;                
            }else {  
                //创建存储路径
                $storePath = $config["savePath"];
                $path = $this->getPath($config["savePath"]);
                if ($path["error"] != 0) {
                    $retParam["error"] = 3;                    
                    $retParam["message"] = $path["message"];
                    $this->setCallback($config, $retParam);
                    return;
                }
                
                //存储文件
                $name = $attach->name;
                $storeUrl = $path["absolutePath"] .$name;
                $visitUrl = $path["relativePath"] .$name;
                if (!$attach->saveAs($storeUrl)) {
                    $retParam["error"] = 4;                    
                    $retParam["message"] = "提示：文件上传失败。";
                    $this->setCallback($config, $retParam);
                    return;                    
                }

                //检查存储后的文件类型
                if (isset($config["validTpye"]) && !empty($config["validTpye"])) {
//                    $fileType = $this->getFileTypeStrict($storeUrl);
//                    if ($fileType["error"] != 0 || !in_array($fileType["msg"], $config["validTpye"])) {
//                        $retParam["error"] = 5;
//                        $retParam["message"] = "提示：您上传的文件类型非法。";
//                        $this->setCallback($config, $retParam);
//                        return;                    
//                    }      
                }

                //上传成功
                $retParam["message"] = "恭喜，上传成功！";
                $retParam["url"] = $visitUrl;
                $retParam["error"] = 0;      
                $this->setCallback($config, $retParam);
                return;                       
            }
            
            $this->setCallback($config, $retParam);
            return;            
        } catch (Exception $e) {
            //异常捕获
            $retParam["message"] = $e->getMessage();
            $this->setCallback($config, $retParam);
            return;                
        }           
    }    
    
    /*
     * 20150620/Samuel/设置返回格式
     */
    private function setCallback($config, $retParam){
        if($config["result"] === "json"){
            echo CJSON::encode($retParam);
        }else{
            $this->setCallbackScript($config["callback"], $retParam);
        }
    }
    
    private function setCallbackScript($funcName, $retParam) {
        echo "<script type='text/javascript'>window.top.window.".$funcName."(".CJSON::encode($retParam).")</script>";
    }


    /**
    * 创建目录
    * 
    * @param	string	$path	路径
    * @param	string	$mode	属性
    * @return	string	如果已经存在则返回true，否则为flase
    */
    private function createDir($path, $mode = 0777) {
        if (is_dir($path)) return true;

        $path = str_replace('\\', '/', $path);
        if(substr($path, -1) != '/') $path = $path.'/';

        $temp = explode('/', $path);
        $cur_dir = '';
        $max = count($temp) - 1;
        for($i=0; $i<$max; $i++) {
                $cur_dir .= $temp[$i].'/';
                if (@is_dir($cur_dir)) continue;
                @mkdir($cur_dir, 0777,true);
                @chmod($cur_dir, 0777);
        }
        return is_dir($path);
    }    
    
    /**
     * 创建完成的文件存储路径
     */
    private function getPath($dir) {
        $relativePath = $dir;
        $absolutePath = dirname(Yii::app()->BasePath) . $relativePath;
        if (!$this->createDir($absolutePath)) {
            return array("error"=>1, "message"=>"创建存储路径" . $absolutePath . "失败！");
        }
        if (!is_dir($absolutePath)) {
            return array("error"=>2, "message"=>"文件存储路径" . $absolutePath . "不是一个目录！");
        }
        @chmod($absolutePath, 0777);
        if (!is_writeable($absolutePath)) {
            return array("error"=>3, "message"=>"文件存储路径" . $absolutePath . "没有写权限！");
        }  
        
        
        return array("error"=>0, "message"=>"创建成功", "absolutePath"=>$absolutePath, "relativePath"=>$relativePath);
    }

    /**
     * 严格判断文件类型,可以使用该方法判断类型
     * 因为我们要求的是视频格式，所以，返回值$return['msg']如果有值，则表示非视频文件
     * 
     * @param string $file 文件的完整路径
     * 
     * @return array $return 
     * $return = array(
     * 	'error' => '错误码', // 0成功，对应的msg是文件类型
     *  'msg' => '错误信息', // 如果有值，则表示非视频文件
     * )
     */
    private function getFileTypeStrict($file) {
        if (!is_file($file)) { // 文件不存在
            return array('error' => 404, 'msg' => "file doesn't exist!");
        }

        $fp = fopen($file, "rb");
        $bin = fread($fp, 2);

        $str_info = @unpack("C2chars", $bin);
        $type_code = intval($str_info['chars1'] . $str_info['chars2']);

        switch ($type_code) {
            case 255251:
                $msg = "mp3";
                break;
            case 255216:
                $msg = "jpg";
                break;
            case 7173:
                $msg = "gif";
                break;
            case 6677:
                $msg = "bmp";
                break;
            case 13780:
                $msg = "png";
                break;
            case 239187:
                $msg = "txt";
                break;
            case 208207:
                $msg = "doc";
                break;
            case 6063:
                $msg = "xml";
                break;
            case 8297:
                $msg = "rar";
                break;
            case 64101:
                $msg = "bat";
                break;
            case 255216:
                $msg = "mp4";
                break;
            case 7076:
                $msg = "flv";
                break;
            default:
                $msg = "unknow";
        }

        $return = array(
            'error' => 0,
            'msg' => $msg,
        );

        return $return;
    }

}
