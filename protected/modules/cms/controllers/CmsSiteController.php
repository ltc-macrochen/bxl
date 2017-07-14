<?php

class CmsSiteController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'view', 'create', 'update', 'admin', 'delete'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $this->title = "新建项目";
        $model = new CmsSite;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['CmsSite'])) {
            $modelRepeat = CmsSite::model()->findByAttributes(array("title"=> $_POST['CmsSite']["title"]));
            if ($modelRepeat) {
                throw new CHttpException("404","对不起，您填写的项目名称已被使用！");
                return;
            }
            
            $model->attributes = $_POST['CmsSite'];
            // Set create time
            $model->status = Constant::STATUS_SHOW;
            $model->createTime = date('Y-m-d H:i:s');
            
            if ($model->save()) {
                $this->redirect(array('index'));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $this->title = "编辑项目";
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['CmsSite'])) {
            $modelRepeat = CmsSite::model()->findByAttributes(array("title"=> $_POST['CmsSite']["title"]));
            if ($_POST['CmsSite']["title"]!=$model->title && $modelRepeat) {
                throw new CHttpException("404","对不起，您填写的项目名称已被使用！");
                return;
            }
            
            $model->title = $_POST['CmsSite']['title'];
            $model->logo = $_POST['CmsSite']['logo'];
            $model->status = $_POST['CmsSite']['status'];
            if ($model->save()) {
                CmsSite::model()->flushCachedSite($id);
                $this->redirect(array('index'));
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            $model = $this->loadModel($id);
            
            if ($model->deleteSite($id)) {
                CmsSite::model()->flushCachedSite($id);
                $this->redirect(array('index'));
            }
        } else {
            throw new CHttpException(404, '对不起，您的请求非法！');
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $this->title = "项目列表";
        $text = Yii::app()->request->getQuery('text', '');
        
        $criteria=new CDbCriteria;    
        $criteria->order = "t.id asc";
        //不显示已经删除的类别
        $criteria->compare('status','<'.Constant::STATUS_DELETE);            
        if($text){
            $criteria->compare('title',$text,true);
        }
        
        $param = array(
            'criteria' => $criteria,
            'countCriteria' => $criteria,
            'pagination' => array('pageSize' => 10),
        );
        
        $dataProvider = new CActiveDataProvider('CmsSite', $param);
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }
    
    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new CmsSite('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['CmsSite'])) {
            $model->attributes = $_GET['CmsSite'];
        }

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return CmsSite the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = CmsSite::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CmsSite $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'cms-site-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}