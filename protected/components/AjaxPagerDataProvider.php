<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class AjaxPagerDataProvider {
    public $pageVar = null;         //ajax请求时页码变量的名称
    public $pageCurrent = null;     //当前页面，从0开始，默认是0
    public $pageCount = null;       //页数
    public $pageSize = null;        //每页数据量
    public $itemCount = null;       //数据总量
    public $data = null;            //查询结果，CActiveRecord类型数组    
    
    /**
     * 通过Ajax请求获取分页数据
     * Ajax请求，GET方式，页码参数为<Model类名_page>，如果model为Post，则参数为Post_page=3
     * 
     * param $modelClass, string  CActiveRecord类名
     *       $param, array  CActiveDataProvider的param参数
     * 
     * @author kevinwang
     */    
    public function __construct($modelClass, $param=array()) {
        $dataProvider = new CActiveDataProvider($modelClass, $param);
        $this->data = $dataProvider->getData();
        $pagination = $dataProvider->getPagination();
        $pagination->validateCurrentPage = false;        
        $this->pageVar     = $pagination->pageVar;
        $this->pageCurrent = $pagination->getCurrentPage();
        $this->pageCount   = $pagination->getPageCount();
        $this->pageSize    = $pagination->getPageSize();
        $this->itemCount   = $pagination->getItemCount();
    }
}
