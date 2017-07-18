<?php
/**
 * Created by PhpStorm.
 * User: macrochen
 * Date: 2017/7/17
 * Time: 11:28
 */
?>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/"><?php echo isset($title) ? $title : '';?></a>
</div>
<div id="navbar" class="navbar-collapse collapse">
    <div class="pull-left clearfix">
        <ul class="nav navbar-nav">
            <li role="presentation" class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    标签 <span class="caret"></span>
                </a>
                <ul class="dropdown-menu row" style="">
                    <li class="col-xs-4 col-lg-3">
                        <a href="/">这是个标签</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>

    <div class="pull-right clearfix col-lg-8" style="top: 9px;">
            <div class="col-lg-4">
                <div class="input-group" style="">
                    <input type="text" class="form-control" id="nav-video-search" placeholder="请输入关键词" maxlength="20">
                    <span class="input-group-btn">
                                <button type="button" class="btn btn-default" onclick="searchVideo();return false;">
                                    <i class="glyphicon glyphicon-search"></i>
                                    搜索
                                </button>
                            </span>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="btn-group" role="group">
                    <button type="button" class="btn nav-btn" onclick="addFavorite();return false;">
                        <i class="glyphicon glyphicon-star-empty"></i>
                        收藏书签
                    </button>
                </div>
                <div class="btn-group" role="group">
                    <a href="/site/partner" type="button" class="btn nav-btn">收录申请</a>
                </div>
            </div>
        <div class="col-lg-4">
            <div class="btn-group" role="group">
                <a href="/site/ad" type="button" class="btn nav-btn">广告合作</a>
            </div>
            <div class="btn-group" role="group">
                <a href="https://www.emoneyspace.com/mitaosex" target="_blank" type="button" class="btn nav-btn">地址发布</a>
            </div>
        </div>
    </div>
</div>
</div>
</nav>