<?php

/**
 * This is the model class for table "cms_site".
 *
 * The followings are the available columns in table 'cms_site':
 * @property string $id
 * @property string $title
 * @property string $catCount
 * @property string $logo
 * @property string $adminId
 * @property integer $status
 * @property string $createTime
 */
class CmsSite extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'cms_site';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title, logo, status', 'required'),
            array('status', 'numerical', 'integerOnly' => true),
            array('title, logo', 'length', 'max' => 255),
            array('title', 'length', 'max' => 35),
            array('catCount, adminId', 'length', 'max' => 10),
            array('createTime', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, title, catCount, logo, adminId, status, createTime', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'admin' => array(self::BELONGS_TO, 'AdminUser', 'adminId'),
            'categories' => array(self::HAS_MANY, 'CmsCategory', 'siteId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => '项目ID',
            'title' => '项目名称',
            'catCount' => '类别数量',
            'logo' => '项目图标',
            'adminId' => '管理员',
            'status' => '审核状态',
            'createTime' => '创建时间',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('catCount', $this->catCount, true);
        $criteria->compare('logo', $this->logo, true);
        $criteria->compare('adminId', $this->adminId, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('createTime', $this->createTime, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    
/**
     * 获取缓存的类别属性
     * @param type $id
     * @return array
     */
    public function getCachedSite($id) {
        $cacheKey = __CLASS__ . "_" . $id;
        $value = Yii::app()->cacheFile->get($cacheKey);
        if ($value === false) {
            $site = CmsSite::model()->findByPk($id);
            if (!$site) {
                return false;
            }
            $value = $site->attributes;
            Yii::app()->cacheFile->set($cacheKey, $value);
        }

        return $value;
    }

    /**
     * 清空缓存的类别属性
     * @param type $id
     * @return array
     */
    public function flushCachedSite($id = "") {
        $cacheKey = __CLASS__ . "_" . $id;
        Yii::app()->cacheFile->delete($cacheKey);
    }    

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return CmsSite the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function deleteWithCatAndPost($siteId) {
        $site = self::model()->findByPk($siteId);
        if (!$site) {
            return false;
        }
        
        //删除权限
        CmsPrivilege::model()->deleteAllByAttributes(array("siteId" => $siteId));
        //删除内容
        CmsPost::model()->deleteAllByAttributes(array("siteId" => $siteId));
        //删除类别
        CmsCategory::model()->deleteAllByAttributes(array("siteId" => $siteId));
        //删除微信配置
        WxConfig::model()->deleteAllByAttributes(array("siteId" => $siteId));
        //删除微信自定义菜单
        WxMenu::model()->deleteAllByAttributes(array("siteId" => $siteId));
        //删除管理员
        AdminUser::model()->deleteAllByAttributes(array("siteId" => $siteId));        
        //删除项目
        self::model()->deleteByPk($siteId);
        
        return true;
    }
    
    //删除项目，仅隐藏禁用数据
    public function deleteSite($siteId) {
        $siteModel = self::model()->findByPk($siteId);
        if (!$siteModel) {
            return false;
        }

        //删除项目
        $siteModel->status = Constant::STATUS_DELETE;
        $siteModel->save();

        $attributes = array('status' => Constant::STATUS_DELETE);
        $condition = "siteId = {$siteId}";
        //删除类别
        $categoryModel = CmsCategory::model()->findAllByAttributes(array('siteId' => $siteId));
        if(!empty($categoryModel)){
            CmsCategory::model()->updateAll($attributes, $condition);
        }

        //删除内容
        $postModel = CmsPost::model()->findAllByAttributes(array('siteId' => $siteId));
        if(!empty($postModel)){
            CmsPost::model()->updateAll(array('status' => Constant::POST_STATUS_DELETE), $condition);
        }

        //删除微信配置
        $configModel = WxConfig::model()->findAllByAttributes(array('siteId' => $siteId));
        if(!empty($configModel)){
            WxConfig::model()->updateAll($attributes, $condition);
        }

        //删除微信自定义菜单
        /*菜单表没有 status 字段，不需要处理 macro/16/02/26
        $menuModel = WxMenu::model()->findAllByAttributes(array('siteId' => $siteId));
        if(!empty($menuModel)){
            WxMenu::model()->updateAll($attributes, $condition);
        }
         */

        //删除管理员
        $adminUserModel = AdminUser::model()->findAllByAttributes(array('siteId' => $siteId));
        if(!empty($adminUserModel)){
            AdminUser::model()->updateAll(array('status' => Constant::ADMIN_STATUS_DISABLED), $condition);
        }
        
        return true;
    }
    
    /**
     * 检查项目是否被删除。
     * @param type $siteId 项目ID
     * @return boolean 项目被删除或不存在，则返回false 否则返回true
     */
    public function isSiteExist($siteId) {
        $site = self::model()->findByPk($siteId);
        if ($site == null) {
            return false;
        }

        if ($site->status != Constant::STATUS_SHOW) {
            return false;
        }

        return true;
    }

}
