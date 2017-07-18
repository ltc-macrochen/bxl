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
    <title><?php echo isset($title) ? $title : $this->pageTitle;?></title>

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

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script>alert("您的浏览器版本过低，请使用IE9及以上版本！");</script>
    <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body class="">

        <?php echo $content; ?>

</body>
</html>
