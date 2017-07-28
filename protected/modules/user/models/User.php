<?php

/**
 * This is the model class for table "t_user".
 *
 * The followings are the available columns in table 't_user':
 * @property string $id
 * @property integer $roleId
 * @property string $openId
 * @property string $nick
 * @property string $head
 * @property string $name
 * @property string $title
 * @property string $sex
 * @property string $desc
 * @property string $email
 * @property string $mobile
 * @property integer $status
 * @property string $registerTime
 * @property string $loginTime
 * @property string $blockEndTime
 */
class User extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('openId, nick, head, name, title, sex, desc, email, mobile', 'required'),
			array('roleId, status', 'numerical', 'integerOnly'=>true),
			array('openId, nick, name, title, mobile', 'length', 'max'=>32),
			array('head, desc, email', 'length', 'max'=>255),
			array('sex', 'length', 'max'=>8),
			array('registerTime, loginTime, blockEndTime', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, roleId, openId, nick, head, name, title, sex, desc, email, mobile, status, registerTime, loginTime, blockEndTime', 'safe', 'on'=>'search'),
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
			'roleId' => '角色',
			'openId' => 'OpenId',
			'nick' => '昵称',
			'head' => '头像',
			'name' => '姓名',
			'title' => '头衔',
			'sex' => '性别',
			'desc' => '简介',
			'email' => '邮箱',
			'mobile' => '手机号',
			'status' => '审核状态',
			'registerTime' => '注册时间',
			'loginTime' => '登录时间',
			'blockEndTime' => '禁言时间',
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
		$criteria->compare('roleId',$this->roleId);
		$criteria->compare('openId',$this->openId,true);
		$criteria->compare('nick',$this->nick,true);
		$criteria->compare('head',$this->head,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('sex',$this->sex,true);
		$criteria->compare('desc',$this->desc,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('registerTime',$this->registerTime,true);
		$criteria->compare('loginTime',$this->loginTime,true);
		$criteria->compare('blockEndTime',$this->blockEndTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
