<?php

/**
 * This is the model class for table "t_cms_post".
 *
 * The followings are the available columns in table 't_cms_post':
 * @property string $id
 * @property string $catId
 * @property string $userId
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $link
 * @property string $imgUrl
 * @property string $audioUrl
 * @property string $videoUrl
 * @property integer $status
 * @property string $viewCount
 * @property string $commentCount
 * @property string $vGood
 * @property string $vBad
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
		return 't_cms_post';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('catId, title, status', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('catId, userId, viewCount, commentCount, vGood, vBad', 'length', 'max'=>10),
			array('title, description, link, imgUrl, audioUrl, videoUrl', 'length', 'max'=>255),
            array('description, content, createTime, updateTime, sortTime, imgUrl', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, catId, userId, title, description, content, link, imgUrl, audioUrl, videoUrl, status, viewCount, commentCount, vGood, vBad, createTime, updateTime', 'safe', 'on'=>'search'),
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
		    'user' => array(self::BELONGS_TO, 'User', 'userId')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '记录ID',
			'catId' => '分类',
			'userId' => '用户',
			'title' => '标题',
			'description' => '描述',
			'content' => '文章内容',
			'link' => '外链',
			'imgUrl' => '图片地址',
			'audioUrl' => '音频地址',
			'videoUrl' => '视频地址',
			'status' => '审核状态',
			'viewCount' => '浏览数',
			'commentCount' => '评论数',
			'vGood' => '点赞数',
			'vBad' => '点踩数',
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
		$criteria->compare('catId',$this->catId,true);
		$criteria->compare('userId',$this->userId,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('link',$this->link,true);
		$criteria->compare('imgUrl',$this->imgUrl,true);
		$criteria->compare('audioUrl',$this->audioUrl,true);
		$criteria->compare('videoUrl',$this->videoUrl,true);
		$criteria->compare('status',Common::statusSearched($this->status, Constant::$_STATUS_LIST_SHOW), true);
		$criteria->compare('viewCount',$this->viewCount,true);
		$criteria->compare('commentCount',$this->commentCount,true);
		$criteria->compare('vGood',$this->vGood,true);
		$criteria->compare('vBad',$this->vBad,true);
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

	const POST_CATEGORY_PIC     = '1';  //图片分类
	const POST_CATEGORY_CONTENT = '2';  //段子分类

    const POST_ORDER_HOT = 'hot';   //热门排序
    const POST_ORDER_NEW = 'new';   //新鲜排序

    /**
     * 随机获取列表
     * @param string $type
     * @param int $count
     * @return array
     */
	public static function getRandomList($type = self::POST_CATEGORY_PIC, $count = 4, $cacheTime = Constant::CACHE_TIME_VARY_SHORT){
        $order = 'rand()';
        $data = self::getArticleList($order, $type, $count, $cacheTime);

        return $data;
    }

    /**
     * 获取文章列表
     * @param string $order
     * @param string $type
     * @param int $count
     * @return array
     */
    public static function getArticleList($order = 'viewCount desc', $type = self::POST_CATEGORY_PIC, $count = 6, $cacheTime = Constant::CACHE_TIME_LONG){
        $cache = Yii::app()->cache;
        if($cache){
            $extStr = "_{$type}_{$order}";
            $cacheKey = MCCacheKeyManager::buildCacheKey(MCCacheKeyManager::CK_GET_TOP_LIST . $extStr);
            $data = $cache->get($cacheKey);
            if($data === false){
                $condition = "catId = {$type} and status = " . Constant::STATUS_SHOW;
                $ret = self::model()->getListFromDb($condition, $order, $count);
                $data = self::formatPostData($ret);

                $cache->set($cacheKey, $data, $cacheTime);
            }

            return $data;
        }

        $condition = "catId = {$type} and status = " . Constant::STATUS_SHOW;
        $ret = self::model()->getListFromDb($condition, $order, $count);
        $data = self::formatPostData($ret);
        return $data;
    }

    /**
     * 根据条件从数据库拉取文章列表
     * @param $condition    string
     * @param $order    'viewCount desc' or 'rand()'
     * @param $limit    int 20 or other
     * @return CActiveRecord[]
     */
    protected function getListFromDb($condition, $order, $limit){
        $criteria = new CDbCriteria();
        $criteria->order = $order;
        $criteria->limit = $limit;
        $criteria->condition = $condition;
        $ret = CmsPost::model()->findAll($criteria);
        return $ret;
    }

    /**
     * 文章数据格式化
     * @param $ret
     * @return array
     */
    public static function formatPostData($ret){
        $data = array();
        foreach ($ret as $item) {
            $d = array(
                'id' => $item->id,
                'title' => $item->title,
                'content' => $item->content,
                'imgUrl' => $item->imgUrl,
                'vGood' => $item->vGood,
                'vBad' => $item->vBad,
                'commentCount' => $item->commentCount,
                'contentDetailUrl' => Yii::app()->createUrl('/web/content/id/' . $item->id)
            );

            //用户信息
            if($item->userId == 0){
                $d['userDetailUrl'] = Yii::app()->createUrl('/');
                $d['userHead'] = "/web/images/head-".rand(1, 19).".jpg";
                $d['userNick'] = "驴传说";
            }else{
                $d['userDetailUrl'] = Yii::app()->createUrl('/web/user/id/' . $item->userId);
                $d['userHead'] = $item->user->head;
                $d['userNick'] = $item->user->nick;
            }

            //是否需要消除防盗链
            $d['killrefer'] = 'false';
            if(strpos($d['imgUrl'], 'http://') !== false){
                $d['killrefer'] = 'true';
            }

            $data[] = $d;
        }

        return $data;
    }
}
