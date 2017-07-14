<?php

/**
 * This is the model class for table "cms_post".
 *
 * The followings are the available columns in table 'cms_post':
 * @property string $id
 * @property integer $siteId
 * @property string $catId
 * @property string $positions
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $link
 * @property string $thumb
 * @property string $audio
 * @property string $video
 * @property string $editorId
 * @property integer $status
 * @property string $createTime
 * @property string $updateTime
 */
class CmsPost extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cms_post';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('siteId, catId, title, description', 'required'),
			array('siteId, status', 'numerical', 'integerOnly'=>true),
			array('catId, editorId', 'length', 'max'=>10),
			array('positions, thumb, audio, video', 'length', 'max'=>255),
            array('link', 'length', 'max'=>512),
            array('title', 'length', 'max'=>35),
			array('description, content, createTime, updateTime, sortTime, thumb', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, siteId, catId, positions, title, description, content, link, thumb, audio, video, editorId, status, createTime, updateTime', 'safe', 'on'=>'search'),
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
             'site'=>array(self::BELONGS_TO, 'CmsSite', 'siteId'),
             'category'=>array(self::BELONGS_TO, 'CmsCategory', 'catId'),
             'editor'=>array(self::BELONGS_TO, 'AdminUser', 'editorId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '内容ID',
			'siteId' => '所属项目',
			'catId' => '所属类别',
			'positions' => '推荐位列表',
			'title' => '内容标题',
			'description' => '内容描述',
			'content' => '文章',
			'link' => '链接',
			'thumb' => '缩略图',
			'audio' => '音频',
			'video' => '视频',
			'editorId' => '编辑',
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
		$criteria->compare('siteId',$this->siteId);
		$criteria->compare('catId',$this->catId,true);
		$criteria->compare('positions',$this->positions,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('link',$this->link,true);
		$criteria->compare('thumb',$this->thumb,true);
		$criteria->compare('audio',$this->audio,true);
		$criteria->compare('video',$this->video,true);
		$criteria->compare('editorId',$this->editorId,true);
		$criteria->compare('status',$this->status);
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
	 * @return CmsPost the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
