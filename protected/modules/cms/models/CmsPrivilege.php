<?php

/**
 * This is the model class for table "cms_privilege".
 *
 * The followings are the available columns in table 'cms_privilege':
 * @property string $id
 * @property string $adminId
 * @property integer $siteId
 * @property string $catId
 */
class CmsPrivilege extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cms_privilege';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('adminId, catId', 'required'),
			array('siteId', 'numerical', 'integerOnly'=>true),
			array('adminId, catId', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, adminId, siteId, catId', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
                    'cat'=>array(self::BELONGS_TO, 'CmsCategory', 'catId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '权限ID',
			'adminId' => '管理员ID',
			'siteId' => '项目ID',
			'catId' => '类别ID',
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('adminId',$this->adminId,true);
		$criteria->compare('siteId',$this->siteId);
		$criteria->compare('catId',$this->catId,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CmsPrivilege the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        /**
         * 新增或更新编辑的权限
         * @param type $adminId 编辑的账户ID
         * @param type $siteId 编辑所属站点ID
         * @param type $categoryList 编辑的权限列表 array(1,2,3)
         * @return type
         */
        public function insertOrUpdatePrivilege($adminId, $siteId, $categoryList) {
            if(empty($categoryList)){
                return array('err' => 101, 'msg' => '权限列表不能为空！');
            }
            
            $privilegeList = CmsPrivilege::model()->findAllByAttributes(array('adminId' => $adminId));
            if (empty($privilegeList)) {
                //编辑未设置过权限，则新增
                foreach ($categoryList as $item) {
                    $privilegeModel = new CmsPrivilege;
                    $privilegeModel->adminId = $adminId;
                    $privilegeModel->siteId = $siteId;
                    $privilegeModel->catId = $item;
                    if (!$privilegeModel->save(false)) {
                        return array('err' => 102, '系统繁忙，请稍后再试！');
                    }
                }
                return array('err' => 0, 'msg' => 'success!');
            } else {
                $oldArray = array();
                //如果已存在的栏目权限不在新的权限列表里，则删除相应权限
                foreach ($privilegeList as $item) {
                    array_push($oldArray, $item->catId);
                    if (!in_array($item->catId, $categoryList)) {
                        $item->delete();
                    }
                }

                foreach ($categoryList as $item) {
                    if (!in_array($item, $oldArray)) {
                        $privilegeModel = new CmsPrivilege;
                        $privilegeModel->adminId = $adminId;
                        $privilegeModel->siteId = $siteId;
                        $privilegeModel->catId = $item;
                        if (!$privilegeModel->save(false)) {
                            return array('err' => 103, '系统繁忙，请稍后再试！');
                        }
                    }
                }
                return array('err' => 0, 'msg' => 'success!');
            }
        }
        
        /**
         * 获取编辑权限列表
         * @param type $userId 编辑账户ID
         * @return type
         */
        public function getUserCatList($userId) {
            $userCatList = array();
            $userCatModel = CmsPrivilege::model()->findAllByAttributes(array('adminId' => $userId));
            if(!empty($userCatModel)){
                foreach ($userCatModel as $item) {
                    $userCatList[] = $item->catId;
                }
            }
            return $userCatList;
        }

}
