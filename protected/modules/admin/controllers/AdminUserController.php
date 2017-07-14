<?php

class AdminUserController extends Controller {

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
                'actions' => array('index', 'create', 'update', 'view', 'block'),
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
        $model = new AdminUser;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['AdminUser'])) {
            if (in_array($_POST['AdminUser']['name'], array_keys(UserIdentity::$_InternalAdminList))) {
                throw new CHttpException(500, "用户名已经存在！");
            }
            $model->attributes = $_POST['AdminUser'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
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
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['AdminUser'])) {
            $_POST['AdminUser']['name'] = $model->name;
            $model->attributes = $_POST['AdminUser'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    public function actionBlock() {
        
        //TODO检查账户是否有效
        $id = Yii::app()->request->getPost('id');
        if ($id == null) {
            echo CJSON::encode(array('err' => 2, 'msg' => '用户ID不能为空！'));
            return;
        }
        $user = AdminUser::model()->findByPk($id);
        if ($user == null) {
            echo CJSON::encode(array('err' => 3, 'msg' => '用户不存在！'));
            return;
        }
        
        if ($user->status == AdminUser::USER_STATUS_DISABLED) {
            //取消禁用
            $user->status = AdminUser::USER_STATUS_INIT;
            $user->openId = "";
            $user->unionId = "";
            $user->save();    
            echo CJSON::encode(array('err' => 0, 'msg' => '用户被成功解除禁用！'));
            return;            
        }else {
            //禁用
            $user->status = AdminUser::USER_STATUS_DISABLED;
            $user->save();
            echo CJSON::encode(array('err' => 0, 'msg' => '用户被成功禁用！'));
            return;            
        }
    }        
    
    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('AdminUser');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new AdminUser('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['AdminUser']))
            $model->attributes = $_GET['AdminUser'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return AdminUser the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = AdminUser::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param AdminUser $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'admin-user-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
