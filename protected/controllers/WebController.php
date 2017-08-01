<?php
/**
 * Created by PhpStorm.
 * User: macrochen
 * Date: 2017/7/17
 * Time: 11:24
 */
class WebController extends Controller {

    public $layout = '//layoutsWeb/main';

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
            $pager->pageSize=20;
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
        $pattern = '/^[\d]$/i';
        if(!preg_match($pattern, $id)){
            echo CJSON::encode(array('err' => -1, 'msg' => 'unvalid id'));
            return;
        }

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
     *
     */
    public function actionNewAdd(){
        $this->render('newAdd');
    }
}