<?php

class SiteController extends Controller {

    public $layout = '//layouts/one';
    
    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', 
                'actions' => array('index'),
                'users' => array('@'),
            ),
            array('allow', // deny all users
                'users' => array('*'),
            ),
        );
    }
    
    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xd6d6d6,
                'foreColor'=>0x2040A0,      //字体颜色
                'offset'=>5,                //设置字符偏移量
                'maxLength'=>'4',           //最多生成几个字符
                'minLength'=>'4',           //最少生成几个字符           
                'width'=>100,                //默认120
                'height'=>30,               //默认50            
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * MVC首页重定向
     */
    public function actionIndex() {
        $model = new LoginForm;
        $this->render('index', array('model' => $model));
    }

    /**
     * 错误页面
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * 登录
     */
    public function actionLogin() {
        $model = new LoginForm;
        $model->rememberMe = 1;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()){
                $this->redirect(CHtml::normalizeUrl(array("/admin")));
            }
        }
        
        // display the login form
        $this->render('index', array('model' => $model));
    }

    /**
     * 退出
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(CHtml::normalizeUrl(array("/site/login")));
    }

}