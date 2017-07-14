<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AclFilter
 *
 * @author kevinwang
 */
class AclFilter {
    public static $instance = null;
    private $_compare = array("AclFilter","compare");


    private function __construct() {
        
    }

        /**
     * 初始化
     */
    public static function getInstance() {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    public function setCallBack($callback) {
        $this->_compare = $callback;
        return $this;
    }
    
    public function doFilter($input, $filter, $defaultDenyAll=true) {
        foreach ($filter as $node => $list) {
            //逐个过滤permit或deny节点
            $isNodeMatch = $this->isNodeMatch($input, $list);
            $isPermit = ($node=="deny")?(!$isNodeMatch):$isNodeMatch;
            
            //被deny节点命中的，直接被过滤掉。其他情况继续进行后面的过滤。
            if ($node == "deny" && $isNodeMatch) {
                break;
            }
        }
        
        //开启defaultDenyAll，最后一个节点如果是permit，默认增加deny all处理。
        if ($defaultDenyAll && $node == "permit" && !$isNodeMatch) {
            $isPermit = false;
        }

        return $isPermit;
    }    
    
    /**
     * 是否匹配到节点列表
     * @param type $input
     * @param type $list
     * @return boolean
     */
    public function isNodeMatch ($input, $list) {
        //没有设置permit过滤器，认为不能访问
        if ($list == null) {
            return false;
        }

        //设置permit过滤器，允许所有
        if ($list == "*") {
            return true;
        }

        //设置permit过滤器，格式错误，不是数组
        if (!is_array($list)) {
            return false;
        }

        //设置permit过滤器，检查过滤器中的路由（controller/action或controller）是否和navi中的url匹配
        foreach ($list as $item) {
            if (call_user_func($this->_compare, $input, $item)) {
                return true;
            }
        }

        return false;
    }
    
    /**
     * 比较函数（比较两个path是否相同）
     * @param type $path, url请求中的路径(如/card/cardSeller/index)
     * @param type $listItem，权限配置中的路径如(/cardSeller/index)或者controller名字(如cardSeller)
     * @return type
     */
    public static function compare($path, $listItem) {
        if (strpos($listItem, "/")===false) {
            //url路径和controller名字比较
            return stristr($path, "/".$listItem."/");            
        }else if (strpos($listItem, "/")===0) {
            //url路径和/controller/action比较
            return stristr($path, $listItem);            
        }else {
            //url路径和controller/action名字比较
            return stristr($path, "/".$listItem);  
        }
        
        
//        $pathAr = array_slice(explode("/", trim($path, "/")), -2, 2);
//        $nodeAr = array_slice(explode("/", trim($listItem, "/")),0,2);
//        if (isset($nodeAr[0]) && strtolower($nodeAr[0]) != strtolower($pathAr[0])) {
//            return false;
//        }
//        if (isset($nodeAr[1]) && strtolower($nodeAr[1]) != strtolower($pathAr[1])) {
//            return false;
//        } 
//        return true;
        
//        if (strpos($listItem, "/")===0) {
//            //url路径和/controller/action比较
//            return stristr($path, $listItem);
//        }else {
//            //url路径和controller名字比较
//            return stristr($path, "/".$listItem."/");
//        }
    }
}
