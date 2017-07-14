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
class Validate {
    public static function slug($slug) {
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
    
    //Url校验
     public static function url($url) {
        $pattern = "/^(http|https|ftp):\/\/+./";
        if (!preg_match($pattern, $url)) {
            return array("error" => 1, "message" => "外链文章地址：".$url." 不合法！");
        }     
        
        return array("error" => 0, "message" => "");
    }
    
    /**
     * 手机号码合法性检查
     * @return boolean true表示合法，false表示非法
     */
    public static function isMobile($mobile) {
        return preg_match("/^1[3458]\d{9}$/", $mobile) ? true : false;
    }
}
