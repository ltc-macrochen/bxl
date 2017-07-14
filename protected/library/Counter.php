<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Counter {
    static public function addDayCounter($counterId,$key,$addVal=1,$limit=1) {
        if (!is_int($addVal) || $addVal<1) {
            throw new CHttpException(500, __METHOD__."入参addVal只能是大于1的整数");
        }
        if (!is_int($limit) || $limit<1) {
            throw new CHttpException(500, __METHOD__."入参limit只能是大于1的整数");
        }
        
        $date = date("Y-m-d");
        $expired = max(strtotime($date." 23:59:59") - time(),1);
        $cacheKey = self::buildKey($date,$counterId,$key); 
        $cacheClass = get_class(Yii::app()->cache);
        if ($cacheClass == "CMemCache") {
            //MemCache计数器
            $memcache = Yii::app()->cache->getMemCache();
            $count = $memcache->get($cacheKey);
            if ($count === false) {  //没有带到限制值
                $memcache->set($cacheKey, $addVal, 0, $expired);
                $count = $addVal;
            } else {  //达到限制值，不处理这个请求
                $count = $memcache->increment($cacheKey, $addVal);
            }  
            if ($count <= $limit) {
                return array("error"=>0,"message"=>"success","value"=>$count);
            }else {
                return array("error"=>10001,"message"=>"current value excceed max limit.","value"=>$count);
            }            
        }else if ($cacheClass == "CRedisCache") {
            //Redis计数器
            $redisConnect = Yii::app()->cache;
            $count = $redisConnect->executeCommand('INCRBY',array($cacheKey,$addVal));
            $redisConnect->executeCommand('EXPIRE',array($cacheKey,$expired));
            $ttl = $redisConnect->executeCommand('TTL',array($cacheKey));
            if ($count <= $limit) {
                return array("error"=>0,"message"=>"success","value"=>$count,"ttl"=>$ttl);
            }else {
                return array("error"=>10001,"message"=>"current value excceed max limit.","value"=>$count,"ttl"=>$ttl);
            }            
        }else {
            throw new CHttpException(404, "未配置MemCache或Redis！");
        }
    }
    
    static public function queryDayCounter($counterId,$key) {
        $date = date("Y-m-d");
        $cacheKey = self::buildKey($date,$counterId,$key); 
        $cacheClass = get_class(Yii::app()->cache);
        if ($cacheClass == "CMemCache") {
            $memcache = Yii::app()->cache->getMemCache();
            $count = $memcache->get($cacheKey);
            if ($count==false) {
                $count=0;
            }
            return array("error"=>0,"message"=>"success","value"=>$count);
        }else if ($cacheClass == "CRedisCache") {
            $redisConnect = Yii::app()->cache;
            $count = $redisConnect->executeCommand('GET',array($cacheKey));
            if ($count===null) {
                $count=0;
            }
            return array("error"=>0,"message"=>"success","value"=>$count);
        }else{
            throw new CHttpException(404, "未配置MemCache或Redis！");
        }
    }    
    
    static public function buildKey($date,$counterId,$key) {
        return Yii::app()->request->hostInfo . "/" . implode("/",array( __CLASS__, $date, $counterId, $key));
    }
}
