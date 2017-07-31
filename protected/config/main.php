<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'theme' => 'bootstrap',
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => '爆笑驴后台',
    'aliases' => array(
        //http://bootstrap3.pascal-brewing.de/
        'bootstrap' => dirname(__FILE__) . '/../vendor/yii-bootstrap-3-module',
    ),
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.library.*',
        'application.modules.admin.models.*',
        'application.modules.cms.models.*',
        'application.modules.user.models.*',
        'bootstrap.behaviors.*',
        'bootstrap.helpers.*',
        'bootstrap.widgets.*',
    ),
    'modules' => array(
        // uncomment the following to enable the Gii tool

        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'admin',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1','119.137.34.179'),
            'generatorPaths' => array(
                'bootstrap.gii'
            )
        ),
        'admin',
        'service',
        'user',
        'cms'
    ),

    // application components
    'components' => array(
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
        ),
        // uncomment the following to enable URLs in path-format
        'urlManager' => array(
            'urlFormat' => 'path',
            //http://www.yiiframework.com/doc/guide/1.1/zh_cn/topics.url
            'showScriptName' => false, //do not include index.php in url
            'rules' => array(
                //"" => 'web/index',  //@macrochen 一个标准的URL规则，将 '/' 对应到 'site/index'
                'admin' => 'cms/cmsPost/index',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
            'urlSuffix' => '.html'       //@macrochen 网址后缀
        ),
        //数据库
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=db_baoxiaolv',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'macro',
            'charset' => 'utf8',
        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                    'categories' => 'system.web.*'
                ),
                array(//微信模块日志
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning, info, trace',
                    'categories' => 'system.weixin.*',
                    'logFile' => 'weixin.log',
                ),
            ),
        ),
//        'cache' => array(
//            'class' => 'system.caching.CMemCache',
//            'servers' => array(
//                array('host' => '127.0.0.1', 'port' => 11211),
//            ),
//        ),
        'cacheFile' => array(
            //配置好缓存类
            'class' => 'system.caching.CFileCache',
            //缓存文件被保存在runtime/abc目录
            'cachePath' => "protected/runtime/cache",
            //缓存文件会进行分级目录存储(也可以设置其他数字，例如1或2或3)，避免一个文件目录存放的内容过多
            'directoryLevel' => 1,
        ),
        'bootstrap' => array(
            'class' => 'bootstrap.components.BsApi'
        ),
        'clientScript' => array(
            'scriptMap' => array(
                'jquery.js' => false,
                'jquery.min.js' => false, //不加载系统自带的jquery
            ),
            'coreScriptPosition' => CClientScript::POS_END,
        ),
    ),
    'controllerMap' => array(
        'ueditor' => array(
            'class' => 'ext.ueditor.UeditorController',
            'config' => array(), //参考config.json的配置，此处的配置具备最高优先级
            'thumbnail' => true, //是否开启缩略图
            'watermark' => '', //水印图片的地址，使用相对路径
            'locate' => 9, //水印位置，1-9，默认为9在右下角
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'service@baoxiaolv.com',
    ),
    'defaultController' => 'web'    //@macrochen 默认controller
);