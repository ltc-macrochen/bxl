<?php

class CmsCategoryController extends Controller {

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
        $this->title = "新建类别";
        $model = new CmsCategory;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['CmsCategory'])) {
            $modelRepeat = CmsCategory::model()->findByAttributes(array('siteId' => $_POST['CmsCategory']["siteId"], "title"=> $_POST['CmsCategory']["title"]));
            if ($modelRepeat) {
                throw new CHttpException("404","对不起，您填写的类别名称重复！");
                return;
            }        
            
            $model->attributes = $_POST['CmsCategory'];

            // Set default value
            $model->parentId = 0;
            $model->parents = "0";
            $model->childCount = $model->leafCount = 0;
            $model->createTime = date('Y-m-d H:i:s');
            
            if ($model->save()) {
                CmsSite::model()->updateCounters(array("catCount"=>1),"id=".$model->siteId);
                $this->redirect(array('index'));
            }
        }
        
        $siteId = isset($_GET["siteId"])?$_GET["siteId"]:0;

        $this->render('create', array(
            'model' => $model,
            'siteId' => $siteId,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $this->title = "编辑类别";
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['CmsCategory'])) {
            $modelRepeat = CmsCategory::model()->findByAttributes(array('siteId' => $model->siteId, "title"=> $_POST['CmsCategory']["title"]));
            if ($_POST['CmsCategory']["title"]!=$model->title && $modelRepeat) {
                throw new CHttpException("404","对不起，您填写的类别名称重复！");
                return;
            }
            
            $model->attributes = $_POST['CmsCategory'];
            if ($model->save()) {
                CmsCategory::model()->flushCachedCat($id);
                $this->redirect(array('index?siteId='.$model->siteId));
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
            $model->status = Constant::STATUS_DELETE;
            if ($model->save()) {
                CmsCategory::model()->flushCachedCat($id);
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
        $this->title = "类别列表";
        $siteId = Yii::app()->request->getQuery('siteId', 0);
        $text = Yii::app()->request->getQuery('text', '');
        
        //权限检查 项目管理员只能查看自己对应的项目的信息
        if(Yii::app()->user->roleId != Constant::ADMIN_ROLE_ADMINISTRATOR){
            $user = AdminUser::model()->findByPk(Yii::app()->user->id);
            $siteId = $user->siteId;
        } 
        $sites = $this->getUserSites();
        $querySiteId = Yii::app()->request->getQuery('siteId');
        if($querySiteId == null && count($sites) == 1){
            $siteId = $sites[0]["id"];
            $this->redirect('index?siteId=' . $siteId);
        }
        
        $criteria=new CDbCriteria;
        $criteria->order = "t.id asc";        
        if($text){
            $criteria->compare('title',$text,true);
        }
        $criteria->compare('siteId',$siteId);
        //不显示已经删除的类别
        $criteria->compare('status','<'.Constant::STATUS_DELETE);
          
        $param = array(
            'criteria' => $criteria,
            'countCriteria' => $criteria,            
            'pagination' => array('pageSize' => 10),
        );        
        
        $dataProvider = new CActiveDataProvider('CmsCategory',$param);
        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'sites' => $sites,
            'siteId' => $siteId,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new CmsCategory('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['CmsCategory'])) {
            $model->attributes = $_GET['CmsCategory'];
        }

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return CmsCategory the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = CmsCategory::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CmsCategory $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'cms-category-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}