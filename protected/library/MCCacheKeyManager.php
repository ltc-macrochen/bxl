<?php
/**
 * Created by PhpStorm.
 * User: macrochen
 * Date: 2017/7/31
 * Time: 11:44
 */

class MCCacheKeyManager {

    //缓存key前缀
    static public $prefix = 'baoxiaolv_';

    //缓存key
    const CK_GET_ALL_CATEGORY = 'get_all_category'; //获取所有分类
    const CK_GET_POST_LIST = 'get_post_list';   //获取文章列表

    /**
     * 构建缓存key
     * @param $key
     * @return string
     */
    static public function buildCacheKey($key){
        $cacheKey = '';
        if(empty($key) || !is_string($key)){
            $cacheKey .= self::$prefix;
            return $cacheKey;
        }

        return self::$prefix . $key;
    }

    /**
     * 清除缓存
     * @param $cacheKey 缓存key
     */
    static public function clearCache($cacheKey){
        $cache = Yii::app()->cache;
        if($cache){
            $cache->delete($cacheKey);
        }
    }
}