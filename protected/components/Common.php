<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Common
 *
 * @author kevinwang
 */
class Common {
    
    
    /**
     * 从数组中获取某个值
     * @param type $array
     * @param type $index
     * @return type
     */
    public static function arrayGet($array, $index) { 
        return $array[$index]; 
        
    }
    
    public static function statusSelected($status, $statusList) {
        foreach ($statusList as $item) {
            if ($item["value"] == $status) {
                return $item["show"];
            }
        }

        return "";
    }
    
    public static function valueShow($status) {
        return $status.'积分';
    }
    
    public static function statusSearched($statusName, $statusList) {
        if ($statusName == null) {
            return null;
        }
        
        foreach ($statusList as $item) {
            if ($item["show"] == $statusName) {
                return $item["value"];
            }
        }

        return 10000;
    }    

    public static function statusList($statusList) {
        return CHtml::listData($statusList, 'value', 'show');
    }

    public static function optionListEncode($raw, $delimiter="\n") {
        if ($raw == "") {
            return "";
        }

        $optionList = array();
        $tempList = explode($delimiter, trim($raw, $delimiter));
        foreach ($tempList as $item) {
            $item = trim($item);
            if ($item != "") {
                array_push($optionList, $item);
            }
        }
        return empty($optionList)?"":Utils::array2string($optionList);
    }

    public static function optionListDecode($format, $isArray=false, $delimiter="\n") {
        if ($format == "") {
            return "";
        }
        $tempList = Utils::string2array($format);
        
        if ($isArray) {
            return $tempList;
        }else {
            return implode($delimiter, $tempList);
        }
    }
    
    /**
     *   英文标识校验
     **/
    public static function slugValidate($slug) {
         if($slug === '0' || $slug === '' || $slug === 0){
             return array("error" => 1, "message" => "英文标识不能为空！");
         }
        if (preg_match("/^[0-9]+$/", $slug)) {
            return array("error" => 1, "message" => "英文标识必须包含英文字母！");
        }
        if (!preg_match("/^[0-9a-zA-Z_-]+$/", $slug)) {
            return array("error" => 2, "message" => "英文标识只能由英文字母、数字和下划线组成！");
        }

        return array("error" => 0, "message" => "");
    }
    
    //审核状态展示
    public static function getReviewStatus($status, $statusList) {
        foreach ($statusList as $item) {
            if ($item["value"] == $status) {
                return $item["show"];
            }
        }

        return "";
    }
    
    //Url校验
     public static function urlValidate($url) {
        $pattern = "/^(http|https|ftp):\/\/+./";
        if ($url != null && $url != '' && !preg_match($pattern, $url)) {
            return array("error" => 1, "message" => "外链地址：" . $url . " 不合法！");
        }
        return array("error" => 0, "message" => "");
    }

}
