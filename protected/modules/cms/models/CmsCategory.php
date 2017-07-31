<?php

/**
 * This is the model class for table "t_cms_category".
 *
 * The followings are the available columns in table 't_cms_category':
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $thumb
 * @property integer $status
 * @property string $createTime
 * @property string $updateTime
 */
class CmsCategory extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_cms_category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, description', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>64),
			array('description, thumb', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, description, thumb, status, createTime, updateTime', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '记录ID',
			'name' => '分类名称',
			'description' => '描述',
			'thumb' => '缩略图',
			'status' => '审核状态',
			'createTime' => '创建时间',
			'updateTime' => '更新时间',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('thumb',$this->thumb,true);
		$criteria->compare('status',Common::statusSearched($this->status, Constant::$_STATUS_LIST_SHOW), true);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('updateTime',$this->updateTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CmsCategory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * 获取所有分类
     * @return array|mixed|null
     */
	public static function getAllCategorys(){
	    $cache = Yii::app()->cache;
	    if($cache){
	        $cacheKey = MCCacheKeyManager::buildCacheKey(MCCacheKeyManager::CK_GET_ALL_CATEGORY);
	        $categorys = $cache->get($cacheKey);
	        if($categorys !== false){
	            return $categorys;
            }
        }

	    $ret = self::model()->findAllByAttributes(array('status' => Constant::STATUS_SHOW));
	    if(!empty($ret) && $cache){
	        $cache->set($cacheKey, $ret, Constant::CACHE_TIME_LONG);
        }

	    return $ret;
    }
}
