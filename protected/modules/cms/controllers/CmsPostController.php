<?php

class CmsPostController extends Controller {

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
            'postOnly + updateStatus', // we only allow deletion via POST request
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
                'actions' => array('index', 'draft', 'view', 'create', 'update', 'updateStatus', 'updateSortTime'),
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
        $this->title = "查看内容";
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        //项目列表只有一个项目时，直接重定向到该项目下
        $sites = $this->getUserSites();
        $querySiteId = Yii::app()->request->getQuery('siteId');
        if($querySiteId == null && count($sites) == 1){
            $this->redirect('create?siteId=' . $sites[0]->id);
        }

        $this->title = "新建内容";
        $model = new CmsPost;
        $model->siteId = isset($_GET["siteId"])?$_GET["siteId"]:0;
        $model->catId = isset($_GET["catId"])?$_GET["catId"]:0; 
        if ($model->catId != 0) {
            $cat = CmsCategory::model()->findByPk($model->catId);
            if (empty($cat)) {
                throw new CHttpException(500, '您选择项目类别不存在！');
            }
            $picSize = Constant::$_CATEGORY_TEMPLATES_CONFIG[$cat->template]["thumbSize"];
        }else {
            $picSize = "";
        }
        
        // Uncomment the following line if AJAX validation is needed
        //$this->performAjaxValidation($model);
        
        if (isset($_POST['CmsPost'])) {
            $model->attributes = $_POST['CmsPost'];
            if ($model->siteId==0 || $model->catId==0) {
                throw new CHttpException(500, '请您选择项目和类别！');
            }
            $modelRepeat = CmsPost::model()->findByAttributes(array('catId' => $_POST['CmsPost']["catId"], "title"=> $_POST['CmsPost']["title"]));
            if ($modelRepeat) {
                throw new CHttpException("404","对不起，您填写的内容标题重复！");
                return;
            }            
            if ($model->link=="" && $model->content=="") {
                throw new CHttpException(500, '内容不能为空！');
            }
            
            //权限检查
            if (Yii::app()->user->roleId == Constant::ADMIN_ROLE_MANAGER) {
                if (!$this->checkUserCategoryPrivilege($model->siteId)) {
                    return;
                }
            }
            if (Yii::app()->user->roleId == Constant::ADMIN_ROLE_EDITOR) {
                if (!$this->checkUserCategoryPrivilege($model->catId)) {
                    return;
                }
            }
            
            $date = date('Y-m-d H:i:s');
            //$model->status = Constant::POST_STATUS_DRAFT;
            $model->editorId = Yii::app()->user->id;
            $model->createTime = $date;
            $model->updateTime = $date;
            $model->sortTime   = $date;
            
            if ($model->save()) {
                CmsCategory::model()->updateCounters(array("leafCount"=>1),"id=".$model->catId);
                $this->redirect(array('view', 'id' => $model->id));
            }
        }
        
        $this->render('create', array('model'=>$model,'picSize'=>$picSize));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $this->title = "编辑内容";
        $model = $this->loadModel($id);
        
        //权限检查
        if (Yii::app()->user->roleId == Constant::ADMIN_ROLE_MANAGER) {
            if (!$this->checkUserCategoryPrivilege($model->siteId)) {
                return;
            }
        }
        if (Yii::app()->user->roleId == Constant::ADMIN_ROLE_EDITOR) {
            if (!$this->checkUserCategoryPrivilege($model->catId)) {
                return;
            }
        }

        $picSizePromot = "缩略图（". Constant::$_CATEGORY_TEMPLATES_CONFIG[$model->category->template]["thumbSize"] ."）";

        // Uncomment the following line if AJAX validation is needed
        //$this->performAjaxValidation($model);        
        if (isset($_POST['CmsPost'])) {
            $modelRepeat = CmsPost::model()->findByAttributes(array('catId' => $model->catId, "title"=> $_POST['CmsPost']["title"]));
            if ($_POST['CmsPost']["title"]!=$model->title && $modelRepeat) {
                throw new CHttpException("404","对不起，您填写的内容标题重复！");
                return;
            }
            
            $model->attributes = $_POST['CmsPost'];
            if (!isset($_POST['CmsPost']['link'])) {
                $model->link = "";
            }
            $model->updateTime = date('Y-m-d H:i:s');

            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        // 如果指定了项目和分类，则读取
        $addition = array();
        $addition["site"] = CmsSite::model()->findByPk($model->siteId);
        $addition["category"] = CmsCategory::model()->findByPk($model->catId);
        $addition["picSizePromot"]=$picSizePromot;

        $this->render('update', array_merge(array(
            'model' => $model,
                        ), $addition));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionUpdateStatus($id) {
        if (!isset($_POST["status"])) {
            throw new CHttpException(404, '对不起，您的请求不正确！');
        }
        
        $model = $this->loadModel($id);
        //权限检查
        if (Yii::app()->user->roleId == Constant::ADMIN_ROLE_MANAGER) {
            if (!$this->checkUserCategoryPrivilege($model->siteId)) {
                return;
            }
        }
        if (Yii::app()->user->roleId == Constant::ADMIN_ROLE_EDITOR) {
            if (!$this->checkUserCategoryPrivilege($model->catId)) {
                return;
            }
        }
        if ($_POST["status"] == "publish") {
            $model->status = Constant::POST_STATUS_SHOW;
            if ($model->save()) {
                echo CJSON::encode(array("error"=>0,"message"=>"发布成功"));return;
            }else {
                echo CJSON::encode(array("error"=>1,"message"=>"系统繁忙，请稍后再试！"));return;
            }              
        }else if ($_POST["status"] == "unpublish") {
            $model->status = Constant::POST_STATUS_DRAFT;
            if ($model->save()) {
                echo CJSON::encode(array("error"=>0,"message"=>"内容被放回草稿箱"));return;
            }else {
                echo CJSON::encode(array("error"=>1,"message"=>"系统繁忙，请稍后再试！"));return;
            }               
        }else if ($_POST["status"] == "delete") {
            if ($model->delete()) {
                CmsCategory::model()->updateCounters(array("leafCount"=>-1),"id=".$model->catId." and leafCount>0");
                echo CJSON::encode(array("error"=>0,"message"=>"删除成功"));return;
            }else {
                echo CJSON::encode(array("error"=>1,"message"=>"系统繁忙，请稍后再试！"));return;
            }
        }else {
            throw new CHttpException(404, '对不起，您的请求不正确！');
        }      
    }
    
    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionUpdateSortTime($id) {
        $model = $this->loadModel($id);
        //权限检查
        if (Yii::app()->user->roleId == Constant::ADMIN_ROLE_MANAGER) {
            if (!$this->checkUserCategoryPrivilege($model->siteId)) {
                return;
            }
        }
        if (Yii::app()->user->roleId == Constant::ADMIN_ROLE_EDITOR) {
            if (!$this->checkUserCategoryPrivilege($model->catId)) {
                return;
            }
        }
        
        $model->sortTime = date('Y-m-d H:i:s');
        $model->save();
        echo CJSON::encode(array("error"=>0,"message"=>"置顶成功"));return;
    }    

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $this->title = "内容列表";
        $siteId = 0;
        $reviewUrl = "";
        $catId = isset($_GET["catId"])?$_GET["catId"]:0;
        if ($catId != 0) {
            $category = CmsCategory::model()->findByPk($catId);
            if (!$category) {
                throw new CHttpException(404, "对不起，您查询的项目类别不存在！");
            }
            $reviewUrl = CmsCategory::model()->getCategoryRealUrl($category);
        }
        $siteId = $category->siteId;
                
        $param = array(
            'criteria' => array(
                'order' => 't.sortTime DESC',
                'condition' => "t.catId=".$catId . " and t.status=".Constant::POST_STATUS_SHOW,
            ),
            'countCriteria' => array('condition' => "t.catId=".$catId . " and t.status=".Constant::POST_STATUS_SHOW),
            'pagination' => array('pageSize' => 10),
        );  

        $dataProvider = new CActiveDataProvider('CmsPost',$param);
        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'sites' => $this->getUserSites(),
            'cats' => $this->getUserCategoryPrivilege(),
            'siteId' => $siteId,
            'catId' => $catId,
            'reviewUrl' => $reviewUrl,
            'category' => $category,
        ));
    }
    
    /**
     * Lists all models.
     */
    public function actionDraft() {
        $this->title = "草稿箱";
        $siteId = 0;
        $catId = isset($_GET["catId"])?$_GET["catId"]:0;
        $param = array(
            'criteria' => array(
                'order' => 't.sortTime DESC',
                'condition' => "t.status=".Constant::POST_STATUS_DRAFT,
            ),
            'countCriteria' => array('condition' => "t.status=".Constant::POST_STATUS_DRAFT,),
            'pagination' => array('pageSize' => 10),
        );
        
        if ($catId != 0) {
            $catgory = CmsCategory::model()->findByPk($catId);
            if (!$catgory) {
                throw new CHttpException(404, "对不起，您查询的项目类别不存在！");
            }
            $siteId = $catgory->siteId;
            $param["criteria"]["condition"] .= " and t.catId=".$catId;
            $param["countCriteria"]["condition"] .= " and t.catId=".$catId;
        }else {
            $param["criteria"]["condition"] .= " and t.editorId=".Yii::app()->user->id . " and t.catId=".$catId;
            $param["countCriteria"]["condition"] .= " and t.editorId=".Yii::app()->user->id . " and t.catId=".$catId;            
        }
        
        $dataProvider = new CActiveDataProvider('CmsPost',$param);
        $this->render('draft', array(
            'dataProvider' => $dataProvider,
            'sites' => $this->getUserSites(),
            'cats' => $this->getUserCategoryPrivilege(),
            'siteId' => $siteId,
            'catId' => $catId,
        ));
    }    
    
    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new CmsPost('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['CmsPost'])) {
            $model->attributes = $_GET['CmsPost'];
        }

        $this->render('admin', array(
            'model' => $model,
        ));
    }
    
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return CmsPost the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = CmsPost::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CmsPost $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'cms-post-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}