<?php
/**
 * Created by PhpStorm.
 * User: macrochen
 * Date: 2017/7/17
 * Time: 11:24
 */
class WebController extends Controller {

    public $layout = '//layoutsWeb/main';

    public function __construct($id){
        parent::__construct($id);
        $this->title = "爆笑驴::爆笑笑话_糗事笑话_爆笑GIF_内涵段子_冷笑话_专注幽默搞笑网站！";
    }

    public function actionIndex(){
        $order = Yii::app()->request->getQuery('order');
        if($order == CmsPost::POST_ORDER_HOT){
            $defaultOrder = 'viewCount desc';
        } else {
            $order = CmsPost::POST_ORDER_NEW;
            $defaultOrder = 'id desc';
        }

        //分类
        $catId = Yii::app()->request->getQuery('catId');
        $condition = '1';
        if(!empty($catId) && is_numeric($catId)){
            $condition = "catId = {$catId}";
            if(!in_array($catId, array(CmsPost::POST_CATEGORY_PIC, CmsPost::POST_CATEGORY_CONTENT))){
                $this->redirect('/');
            }
        }

        $page = Yii::app()->request->getQuery('page');
        if(!is_numeric($page)){
            $page = 1;
        }

        //TODO 缓存
        $cache = Yii::app()->cache;
        if ($cache) {
            $extStr = "_{$order}_{$page}";
            $cacheKey = MCCacheKeyManager::buildCacheKey(MCCacheKeyManager::CK_GET_POST_LIST . $extStr);
            $cacheData = $cache->get($cacheKey);
        } else {
            $cacheData = false;
        }

        if($cacheData === false){
            $criteria = new CDbCriteria();
            $criteria->order = $defaultOrder;
            $criteria->addCondition("status=" . Constant::STATUS_SHOW);      //根据条件查询
            $criteria->addCondition($condition);
            $count = CmsPost::model()->count($criteria);
            $pager = new CPagination($count);
            $pager->pageSize=10;
            $pager->applyLimit($criteria);
            $postRet = CmsPost::model()->findAll($criteria);
            $postdata = CmsPost::formatPostData($postRet);

            $cacheData = array(
                'pager' => $pager,
                'postdata' => $postdata
            );
            if($cache && !empty($vdata)){
                $cache->set($cacheKey, $cacheData, Constant::CACHE_TIME_LONG);
            }
        }

        $this->render('index',
            array(
                'pages'=>$cacheData['pager'],
                'postdata'=>$cacheData['postdata'],
                'order' => $order,
                'top4' => CmsPost::getRandomList(CmsPost::POST_CATEGORY_PIC, 4, Constant::CACHE_TIME_SHORT)
            )
        );
    }

    /**
     * 内容页
     * @param $id
     */
    public function actionContent($id){
        if(!is_numeric($id)){
            $this->redirect('/');
        }

        $post = CmsPost::model()->findAllByAttributes(array('id' => $id));
        if(empty($post) || $post[0]['status'] != Constant::STATUS_SHOW){
            $this->redirect('/');
        }
        $post[0]['viewCount'] += 1;
        $post[0]->save();

        $postData = CmsPost::formatPostData($post);

        if($postData[0]['title'] != Constant::POST_DEFAULT_TITLE){
            $this->title = "爆笑驴 - {$postData[0]['title']}";
        }

        $this->render('content',
            array(
                'post' => $postData,
                'guess4' => CmsPost::getRandomList(),
                'top6' => CmsPost::getArticleList(),
                'top4' => CmsPost::getRandomList(CmsPost::POST_CATEGORY_PIC, 4, Constant::CACHE_TIME_SHORT)
            )
        );
    }

    /**
     *  投稿页
     */
    public function actionNewAdd(){
        $this->render('newAdd');
    }

    /**
     * 审核页
     */
    public function actionReview(){
        $criteria = new CDbCriteria();
        $criteria->condition = 'status = ' . Constant::STATUS_HIDE;
        $criteria->limit = 1;
        $criteria->order = 'rand()';
        $post = CmsPost::model()->findAll($criteria);
        if(empty($post)){
            header("Content-type: text/html; charset=utf-8");
            $noticeJs = Utils::getAlertBackString('没有需要审核的稿子啦~不如去分享点儿笑料吧~~', Yii::app()->createUrl('/web/newAdd'));
            echo $noticeJs;
            return;
        }

        $postData = CmsPost::formatPostData($post);

        $this->render('review',array(
            'post' => $postData
        ));
    }

    /**
     * 投票
     * @throws CHttpException
     */
    public function actionDoLike() {
        if (!Yii::app()->request->isAjaxRequest){
            $this->redirect('/');
        }
        if (!Validate::checkRefer()) {
            throw new CHttpException(500, 'fail');
        }

        $action = Yii::app()->request->getPost('action');
        if(!in_array($action, array('like', 'unlike'))){
            echo CJSON::encode(array('err' => -1, 'msg' => 'unvalid action'));
            return;
        }

        //vid合法性检查
        $id = Yii::app()->request->getPost('id');
        if(!is_numeric($id)){
            echo CJSON::encode(array('err' => -1, 'msg' => 'unvalid id'));
            return;
        }

        //访问控制
//        $key = Utils::getClientIp();
//        $isAccessLimit = EvilDefence::isAccessTimesLimit(__METHOD__, $key, '');
//        if($isAccessLimit){
//            echo CJSON::encode(array('err' => -1, 'msg' => '您的操作过于频繁，请稍后再试~'));
//            return;
//        }

        $postRet = CmsPost::model()->findByPk($id);
        if(empty($postRet) || $postRet->status != Constant::STATUS_SHOW){
            echo CJSON::encode(array('err' => -1, 'msg' => 'article not exist'));
            return;
        }

        if($action == 'like'){
            $postRet->vGood += 1;
        }
        if($action == 'unlike'){
            $postRet->vBad += 1;
        }
        $postRet->save();

        echo CJSON::encode(array('err' => 0, 'msg' => 'success'));
        return;
    }

    /**
     * 投稿
     * @throws CHttpException
     */
    public function actionSubmitHappy(){
        if (!Yii::app()->request->isAjaxRequest){
            $this->redirect('/');
        }
        if (!Validate::checkRefer()) {
            throw new CHttpException(500, 'fail');
        }

        $pic = Yii::app()->request->getPost('pic');
        $content = Yii::app()->request->getPost('content');
        if(empty($pic) && empty($content)){
            echo CJSON::encode(array('err' => -1, 'msg' => '请上传好玩的图片或者用文字描述搞笑好玩的事儿'));
            return;
        }

        //图片校验
        if(mb_strlen($pic) > 255){
            echo CJSON::encode(array('err' => -1, 'msg' => '比长城还长的图片我们看不过来~~'));
            return;
        }
        $pic = htmlentities($pic, ENT_COMPAT|ENT_HTML401, "UTF-8");

        //文章校验
        if(mb_strlen($content) > 300){
            echo CJSON::encode(array('err' => -1, 'msg' => '请长话短说~~'));
            return;
        }
        $content = htmlentities($content, ENT_COMPAT|ENT_HTML401, "UTF-8");

        //访问控制
//        $key = Utils::getClientIp();
//        $isAccessLimit = EvilDefence::isAccessTimesLimit(__METHOD__, $key, '');
//        if($isAccessLimit){
//            echo CJSON::encode(array('err' => -1, 'msg' => '您的操作过于频繁，请稍后再试~'));
//            return;
//        }

        //保存
        $postModel = new CmsPost();
        $postModel->catId = empty($pic) ? CmsPost::POST_CATEGORY_CONTENT : CmsPost::POST_CATEGORY_PIC;
        $postModel->title = '';
        $postModel->status = Constant::STATUS_HIDE; //默认待审核
        $postModel->content = $content;
        $postModel->imgUrl = $pic;
        $postModel->createTime = date('Y-m-d H:i:s');
        if(!$postModel->save(false)){
            echo CJSON::encode(array('err' => -1, 'msg' => '提交失败，请稍后再试哦~'));
            return;
        }

        echo CJSON::encode(array('err' => 0, 'msg' => '分享成功！审核通过后展示~'));
        return;
    }

    /**
     * 审稿
     * @throws CHttpException
     */
    public function actionDoReview(){
        if (!Yii::app()->request->isAjaxRequest){
            $this->redirect('/');
        }
        if (!Validate::checkRefer()) {
            throw new CHttpException(500, 'fail');
        }

        $id = Yii::app()->request->getPost('id');
        $action = Yii::app()->request->getPost('action');
        if(!in_array($action, array(CmsPost::REVIEW_ACTION_BAD, CmsPost::REVIEW_ACTION_HARD, CmsPost::REVIEW_ACTION_GOOD))){
            echo CJSON::encode(array('err' => -1, 'msg' => 'param error'));
            return;
        }
        if(!is_numeric($id)){
            echo CJSON::encode(array('err' => -1, 'msg' => 'param error'));
            return;
        }

        //访问控制
//        $key = Utils::getClientIp();
//        $isAccessLimit = EvilDefence::isAccessTimesLimit(__METHOD__, $key, '');
//        if($isAccessLimit){
//            echo CJSON::encode(array('err' => -1, 'msg' => '您的操作过于频繁，请稍后再试~'));
//            return;
//        }

        $ret = CmsPost::model()->findByPk($id);
        if(empty($ret) || $ret->status != Constant::STATUS_HIDE){
            echo CJSON::encode(array('err' => -1, 'msg' => 'not found'));
            return;
        }

        //更新审核状态
        $ret->reviewTimes += 1;
        if($action == CmsPost::REVIEW_ACTION_GOOD){
            $ret->reviewGood += 1;
        }
        if($ret->reviewGood >= CmsPost::REVIEW_GOOD){
            //审核好评达到2次即审核通过
            $ret->status = Constant::STATUS_SHOW;
        }else if($ret->reviewTimes >= CmsPost::REVIEW_TIMES || ($ret->reviewTimes >= CmsPost::REVIEW_GOOD && $ret->reviewGood == 0)){
            //审核到达3次或者审核2次都没有好评，则审核不通过
            $ret->status = Constant::STATUS_REJECT;
        }
        if(!$ret->save(false)){
            echo CJSON::encode(array('err' => -1, 'msg' => '审核失败，请稍后再试~'));
            return;
        }

        echo CJSON::encode(array('err' => 0, 'msg' => '审核成功，人品+1'));
        return;
    }
}