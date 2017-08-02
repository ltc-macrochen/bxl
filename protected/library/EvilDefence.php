<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of validate
 *
 * @author kevinwang
 */
class EvilDefence {

    public static function getRefer() {
        $referer = isset($_SERVER['HTTP_REFERER']) ? str_replace(array("&amp;"), array("&"), htmlspecialchars($_SERVER['HTTP_REFERER'], ENT_QUOTES)) : '';
        if (!empty($referer) && !preg_match("/^http(s?):\/\//", $referer)) {
            $referer = '';
        }

        return $referer;
    }

    public static function getClientIp() {
        if (getenv("HTTP_CLIENT_IP")) {
            $ip = getenv("HTTP_CLIENT_IP");
        }else if (getenv("HTTP_X_FORWARDED_FOR")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        }else if (getenv("REMOTE_ADDR")) {
            $ip = getenv("REMOTE_ADDR");
        }else {
            $ip = "Unknow";
        }
        return $ip;
    }
    
    function getServerIp() { 
        if (isset($_SERVER)) { 
            if($_SERVER['SERVER_ADDR']) {
                $server_ip = $_SERVER['SERVER_ADDR']; 
            } else if ($_SERVER['LOCAL_ADDR']) { 
                $server_ip = $_SERVER['LOCAL_ADDR']; 
            }else {
                //如果是通过自动化脚本执行，请在脚本中定义$_ENV["SERVER_ADDR"]
                $server_ip = $_ENV["SERVER_ADDR"];
            }
        } else { 
            $server_ip = getenv('SERVER_ADDR');
        } 
        return $server_ip; 
    }    
    
    /**
     * 判断客户端是否为移动设备
     * @return boolean
     */
    public static function isMobileDevice() {
        if(preg_match('/(alcatel|amoi|android|avantgo|blackberry|benq|cell|cricket|docomo|elaine|htc|iemobile|iphone|ipad|ipaq|ipod|j2me|java|midp|mini|mmp|mobi|motorola|nec-|nokia|palm|panasonic|philips|phone|playbook|sagem|sharp|sie-|silk|smartphone|sony|symbian|t-mobile|telus|up\.browser|up\.link|vodafone|wap|webos|wireless|xda|xoom|zte)/i', $_SERVER['HTTP_USER_AGENT'])) {
            return true;
        } else {
            return false;
        }
    }    

    /**
     * 检查referer
     * @param type $domain，可以传入单个合法域名或合法域名列表
     *                       
     * @return boolean  true,   检查通过
     *                   false， 检查未通过
     */
    public static function isRefererPermit($domain) {
        $domainList = is_string($domain) ? array($domain) : $domain;
        if (!is_array($domainList)) {
            return true;
        }
        $refer = self::getRefer();
        if ($refer=="") {
            return false;
        }
        $referArr = parse_url($refer);
        foreach ($domainList as $d) {
            if ($referArr["host"] != $d) {
                return false;
            }
        }
        return true;
    }

    /**
     * 访问频率限制
     * 
     * @param type $action， 调用该接口的controller/action，可以直接传入__METHOD__
     * @param type $key，    访问者的uid或ip地址
     * @param type $extKey   访问者的其它业务标识
     * @param type $interval 统计时间间隔,最长不超过1小时
     * @param type $limit    访问次数限制
     * @return boolean       true，  已经达到访问次数
     *                        false， 未达到访问次数
     */
    public static function isAccessTimesLimit($action, $key, $extKey, $interval=60, $limit=60) {
        if ($interval<=0 || $interval>3600) {
            //恶意检查的时间最长设定为1小时
            $interval = 3600;
        }
        $cacheKey = Yii::app()->request->hostInfo . "/" . implode("_", array(strval($action), strval($key), strval($extKey)));
        $cacheClass = get_class(Yii::app()->cache);
        if($cacheClass == 'CMemCache'){
            $memcache = Yii::app()->cache->getMemCache();
            $count = $memcache->get($cacheKey);
            if ($count === false) {  //没有带到限制值
                $memcache->set($cacheKey, 1, 0, $interval);
                return false;
            } else {  //达到限制值，不处理这个请求
                $memcache->increment($cacheKey, 1);
                return ($count < $limit) ? false : true;
            }
        }else if($cacheClass == 'CRedisCache'){
            //Redis计数器
            $redisConnect = Yii::app()->cache;
            $count = $redisConnect->executeCommand('INCRBY',array($cacheKey,1));
            $redisConnect->executeCommand('EXPIRE',array($cacheKey,$interval));
            $ttl = $redisConnect->executeCommand('TTL',array($cacheKey));
            return ($count < $limit) ? false : true;
        }else{
            throw new CHttpException(404, "未配置MemCache或Redis！");
        }

    }

}
