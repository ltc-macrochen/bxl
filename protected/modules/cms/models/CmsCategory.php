<?php

/**
 * This is the model class for table "cms_category".
 *
 * The followings are the available columns in table 'cms_category':
 * @property string $id
 * @property integer $siteId
 * @property string $parentId
 * @property string $parents
 * @property string $childCount
 * @property string $leafCount
 * @property string $title
 * @property string $description
 * @property string $template
 * @property string $banner
 * @property integer $status
 * @property string $createTime
 */
class CmsCategory extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'cms_category';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('siteId, title, template, banner, status', 'required'),
            array('siteId, status', 'numerical', 'integerOnly' => true),
            array('parentId, childCount, leafCount', 'length', 'max' => 10),
            array('parents, template', 'length', 'max' => 255),
            array('title', 'length', 'max' => 35),
            array('description, banner, createTime', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, siteId, parentId, parents, childCount, leafCount, title, description, template, banner, status, createTime', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'site' => array(self::BELONGS_TO, 'CmsSite', 'siteId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => '类别ID',
            'siteId' => '所属项目',
            'parentId' => '父类别ID',
            'parents' => '根节点回溯路径',
            'childCount' => '子类别数量',
            'leafCount' => '内容数量',
            'title' => '类别名称',
            'description' => '类别描述',
            'template' => '显示模板',
            'banner' => '模板头图',
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
        $criteria->compare('siteId', $this->siteId);
        $criteria->compare('parentId', $this->parentId, true);
        $criteria->compare('parents', $this->parents, true);
        $criteria->compare('childCount', $this->childCount, true);
        $criteria->compare('leafCount', $this->leafCount, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('template', $this->template, true);
        $criteria->compare('banner', $this->banner, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('createTime', $this->createTime, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return CmsCategory the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public function deleteWithPost($catId) {
        $cat = self::model()->findByPk($catId);
        if (!$cat) {
            return false;
        }
        //删除权限
        CmsPrivilege::model()->deleteAllByAttributes(array("catId"=>$catId));
        //删除内容
        CmsPost::model()->deleteAllByAttributes(array("catId"=>$catId));
        //删除类别
        self::model()->deleteByPk($catId);
        //修改站点的类别计数
        CmsSite::model()->updateCounters(array("catCount"=>-1),"id=".$cat->siteId);
        return ture;
    }

    /**
     * 获取项目的所有栏目列表
     * @param type $siteId 项目ID
     * @return type
     */
    public function getSiteCatList($siteId) {
        $siteCatList = array();
        $siteCatModel = CmsCategory::model()->findAllByAttributes(array('siteId' => $siteId, 'status' => Constant::STATUS_SHOW));
        if (!empty($siteCatModel)) {
            foreach ($siteCatModel as $item) {
                $siteCatList[$item->id] = $item->title;
            }
        }

        return $siteCatList;
    }

    /**
     * 获取缓存的类别属性
     * @param type $id
     * @return array
     */
    public function getCachedCat($id) {
        $cacheKey = __CLASS__ . "_" . $id;
        $value = Yii::app()->cacheFile->get($cacheKey);
        if ($value === false) {
            $cat = CmsCategory::model()->findByPk($id);
            if (!$cat) {
                return false;
            }
            $value = $cat->attributes;
            Yii::app()->cacheFile->set($cacheKey, $value);
        }

        return $value;
    }

    /**
     * 清空缓存的类别属性
     * @param type $id
     * @return array
     */
    public function flushCachedCat($id = "") {
        $cacheKey = __CLASS__ . "_" . $id;
        Yii::app()->cacheFile->delete($cacheKey);
    }

    public function getCategoryUrl($catId) {
        return Yii::app()->request->hostInfo . "/web/list/id/{$catId}";
    }

    public function getCategoryRealUrl($model) {
        return Yii::app()->request->hostInfo . "/web/" . Constant::WEBPAGE_TEMPLATE_VIEW_LIST . "/id/{$model['id']}";
        //return Yii::app()->request->hostInfo . "/web/{$model['template']}/" . Constant::WEBPAGE_TEMPLATE_LIST . "?id={$model['id']}";//old
    }

    public function getPostUrl($catId, $postId) {
        return Yii::app()->request->hostInfo . "/web/view/id/{$catId},{$postId}";
    }

    public function getPostRealUrl($model, $postId) {
        return Yii::app()->request->hostInfo . "/web/" . Constant::WEBPAGE_TEMPLATE_VIEW_NEWS . "/id/{$postId}";
        //return Yii::app()->request->hostInfo . "/web/{$model['template']}/" . Constant::WEBPAGE_TEMPLATE_NEWS . "?id={$postId}"; //old
    }

}
