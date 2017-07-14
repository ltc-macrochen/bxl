<?php

class DefaultController extends CController {
    
    public function __construct($id, $module = null) {
        parent::__construct($id, $module);
        
        if (Yii::app()->user->isGuest) {
            $this->showException("对不起，您未登录！");
        }
    }    

    /**
     * 将中文名称转换为英文标识
     * 
     * POST PARAM，title
     * @return type, JSON
     */
    public function actionGetSlug() {
            try {
                $letter = Utils::gbkToPinyin($_POST["title"]);
            } catch (Exception $e) {
                echo json_encode(array("err" => 1, "msg" => $e->getMessage()));
                return;
            }

            echo CJSON::encode(array("err" => 0, "msg" => $letter));
            return;
     }
     
    /**
     * 生成二维码
     */
    public function actionGetQrcode(){
        $url = urldecode($_GET["data"]);
        require_once Yii::app()->basePath . '/extensions/phpqrcode/phpqrcode.php';
        QRcode::png($url);
    }     
}
