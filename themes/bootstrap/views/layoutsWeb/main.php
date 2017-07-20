<?php
/**
 * Created by PhpStorm.
 * User: macrochen
 * Date: 2017/7/18
 * Time: 17:46
 */
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <!--[if IE 9]> <meta http-equiv="X-UA-Compatible" content="IE=8,chrome=1"> <![endif]-->
    <!--[if !IE 9]> <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> <![endif]-->
    <!--[if IE]>
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <![endif]-->
    <!--[if !IE]>
    <meta name="viewport" content="width=device-width,initial-scale=.3"/>
    <![endif]-->
    <meta name="robots" content="index, follow">
    <meta name="revisit-after" content="1 days">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta name="keywords" content="爆笑驴啦啦啦啦">
    <meta name="description" content="爆笑驴啦啦啦啦">
    <link rel="icon" type="image/vnd.microsoft.icon" href="/web/img/icon.ico">
    <title><?php echo isset($title) ? $title : '爆笑驴::爆笑笑话_糗事笑话_爆笑GIF_内涵段子_冷笑话_专注幽默搞笑网站！';?></title>

    <!-- Bootstrap -->
    <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <?php
    $cs        = Yii::app()->clientScript;
    $themePath = Yii::app()->theme->baseUrl;

    // 基础CSS
    $cs->registerCssFile($themePath . '/css/bootstrap.min.css');
    $cs->registerCssFile($themePath . '/font-awesome/css/font-awesome.css');

    // 主题本身的CSS
    $cs->registerCssFile($themePath . '/css/animate.css');
    $cs->registerCssFile($themePath . '/css/style.css');
    ?>
    <link rel="stylesheet" type="text/css" href="/web/css/project.css">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script>alert("您的浏览器版本过低，请使用IE9及以上版本！");</script>
    <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body class="top-navigation  pace-done">
    <div id="wrapper">
        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom white-bg">
                <div class="container" style="padding: 0;">
                    <nav class="navbar navbar-static-top" role="navigation">
                        <div class="navbar-header">
                            <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
                                <i class="fa fa-reorder"></i>
                            </button>
                            <a href="http://www.baoxiaolv.cn" class="navbar-brand">爆笑驴</a>
                            <a class="navbar-toggle bxl-slogan">开心一整天~</a>
                        </div>
                        <div class="navbar-collapse collapse" id="navbar">
                            <ul class="nav navbar-nav">
                                <li class="active">
                                    <a aria-expanded="false" role="button" href="/">首页</a>
                                </li>
                                <li class="">
                                    <a aria-expanded="false" role="button" href="#" >热门</a>
                                </li>
                                <li class="">
                                    <a aria-expanded="false" role="button" href="#" >新鲜</a>
                                </li>
                                <li class="">
                                    <a aria-expanded="false" role="button" href="#" >神回复</a>
                                </li>
                                <li class="">
                                    <a aria-expanded="false" role="button" href="#" >趣图</a>
                                </li>
                                <li class="">
                                    <a aria-expanded="false" role="button" href="#" >段子</a><!--<i class="fa fa-angle-right"></i>-->
                                </li>
                                <!-- demo
                                <li class="dropdown">
                                    <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> 段子 <span class="caret"></span></a>
                                    <ul role="menu" class="dropdown-menu">
                                        <li><a href="">菜单列表</a>
                                        </li>
                                        <li><a href="">菜单列表</a>
                                        </li>
                                        <li><a href="">菜单列表</a>
                                        </li>
                                        <li><a href="">菜单列表</a>
                                        </li>
                                    </ul>
                                </li>
                                -->
                            </ul>
                            <ul class="nav navbar-top-links navbar-right">
                                <li>
                                    <a href="login.html" tppabs="http://www.zi-han.net/theme/hplus/login.html">
                                        <i class="fa fa-sign-out"></i> 退出
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
            <?php echo $content; ?>
            <div class="footer">
                <div class="pull-right">
                    By：<a href="javascript:if(confirm('http://www.zi-han.net/  \n\nτݾҢδӀ Teleport Pro ȡܘìӲΪ ̼քԲܲ·޶Ӭڽߪʼθַאʨ׃ք׶Χc  \n\nţҪՓؾϱǷʏղߪ̼ðÿ'))window.location='http://www.zi-han.net/'" tppabs="http://www.zi-han.net/" target="_blank">zihan's blog</a>
                </div>
                <div>
                    <strong>Copyright</strong> H+ &copy; 2014
                </div>
            </div>

        </div>
    </div>
</body>
</html>
