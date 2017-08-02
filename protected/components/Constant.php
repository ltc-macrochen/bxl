<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Constant
{
    /**************************************************************************/
    /*******************************  用户相关  ******************************/
    /**************************************************************************/ 
    
    //用户审核状态
    const USER_STATUS_ENABLE = 0;    //启用    
    const USER_STATUS_DISABLE = 1;   //禁用
    //（下拉菜单使用）
    static $_USER_STATUS_LIST = array(
        array("value"=> self::USER_STATUS_ENABLE, "show" => "启用"),        
        array("value"=> self::USER_STATUS_DISABLE, "show" => "禁用"),
    );    
    
    //默认组群
    const ADMIN_ROLE_GUEST = 0;             //访客
    const ADMIN_ROLE_ADMINISTRATOR = 1;     //系统管理员
    const ADMIN_ROLE_MANAGER = 2;           //普通管理员
    
    /**************************************************************************/
    /*******************************     其他    ******************************/
    /**************************************************************************/ 
    
    //通用状态
    const STATUS_SHOW = 0;      //展示  
    const STATUS_HIDE = 1;      //不展示
    const STATUS_DELETE = 2;    //删除
    const STATUS_REJECT = 3;    //审核拒绝

    //（下拉菜单使用）

    static $_STATUS_LIST = array(
        array("value" => self::STATUS_SHOW, "show" => "上架"),
        array("value" => self::STATUS_HIDE, "show" => "下架"),
            //array("value"=> self::STATUS_DELETE, "show" => "已删除"),
    );
    static $_STATUS_LIST_SHOW = array(
        array("value" => self::STATUS_SHOW, "show" => "审核通过"),
        array("value" => self::STATUS_HIDE, "show" => "等待审核"),
        array("value"=> self::STATUS_DELETE, "show" => "已删除"),
        array("value"=> self::STATUS_REJECT, "show" => "审核拒绝"),
    );
    static $_STATUS_LIST_ENABLE = array(
        array("value" => self::STATUS_SHOW, "show" => "启用"),
        array("value" => self::STATUS_HIDE, "show" => "禁用"),
            //array("value"=> self::STATUS_DELETE, "show" => "已删除"),
    );
    static $_MODEL_LANGUAGE_MAP = array(
        "AdminRole" => "角色配置",
        "AdminUser" => "系统管理员",
    );

    //缓存时间
    const CACHE_TIME_VARY_SHORT = 10;    //10秒
    const CACHE_TIME_SHORT = 10;         //1分钟 
    const CACHE_TIME_LONG = 3600;        //1小时
    const CACHE_TIME_VARY_LONG = 36000;  //10小时
    const PEEK_COUNT_MAX_SHOW = 10000; //最大偷听显示数量

    const POST_DEFAULT_TITLE = '分享我的糗事笑话';

    //计数ID
    const COUNT_ID_SUBMITHAPPY = 1;
}
