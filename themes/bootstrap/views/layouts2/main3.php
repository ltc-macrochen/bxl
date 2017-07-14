<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>    

        <?php 
        
        $cs        = Yii::app()->clientScript;
        $themePath = Yii::app()->theme->baseUrl;
        
        // 基础CSS
        $cs->registerCssFile($themePath . '/css/bootstrap.min.css');
        $cs->registerCssFile($themePath . '/font-awesome/css/font-awesome.css');
        
        // 第三方库需要使用的CSS
        $cs->registerCssFile($themePath . '/css/plugins/toastr/toastr.min.css');   
        
        // 主题本身的CSS
        $cs->registerCssFile($themePath . '/css/animate.css');
        $cs->registerCssFile($themePath . '/css/style.css');
        
        $cs->registerScriptFile($themePath . '/js/jquery-1.9.1.min.js');
        $cs->registerScriptFile($themePath . '/js/bootstrap.min.js', CClientScript::POS_END);
        $cs->registerScriptFile($themePath . '/js/plugins/metisMenu/jquery.metisMenu.js', CClientScript::POS_END);
        $cs->registerScriptFile($themePath . '/js/plugins/slimscroll/jquery.slimscroll.min.js', CClientScript::POS_END);
        $cs->registerScriptFile($themePath . '/js/plugins/dragsort/jquery.dragsort-0.5.2.min.js', CClientScript::POS_END);
        $cs->registerScriptFile($themePath . '/js/plugins/pace/pace.min.js', CClientScript::POS_END);
        $cs->registerScriptFile($themePath . '/js/plugins/toastr/toastr.min.js', CClientScript::POS_END);
        $cs->registerScriptFile($themePath . '/js/common.js', CClientScript::POS_END);        
        
        ?>
        
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="<?php echo Yii::app()->theme->baseUrl . '/js/html5shiv.js';?>"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl . '/js/respond.min.js';?>"></script>
        <![endif]-->
        
    </head>
    <body class="fixed-sidebar full-height-layout">
        <div id="wrapper">
            <nav class="navbar-default navbar-static-side" role="navigation">
                <div class="sidebar-collapse">
                    <ul class="nav metismenu" id="side-menu">
                        <li class="nav-header">
                            <span>
                                <img class="center-block" style="margin:0 auto;width:80%;" alt="image" src="/images/manage_01.png"/>
                            </span>
                            <?php if(!Yii::app()->user->isGuest):?>
                            <!--<span>
                                <img class="img-circle center-block" style="width:70px;height:70px;" alt="image" src="/images/userface.png"/>
                            </span>-->
                            <p class="text-center" style="margin-top:10px">
                                <a>角色：<?php echo Yii::app()->user->realName;?></a> <br/> 
                                <a>账号：<?php echo Yii::app()->user->name;?></a> <br/> 
                                <a href="<?php echo CHtml::normalizeUrl(array("/site/logout"));?>">退出</a>
                            </p>
                            <?php else:?>
                            <p class="text-center" style="margin-top:10px">
                                <a href="<?php echo CHtml::normalizeUrl(array("/site/login"));?>">请登录</a>
                            </p>
                            <?php endif;?>
                        </li> 
                        <?php 
                            foreach ($this->navi as $item) {
                                $menu = $subMenu = "";

                                if (!isset($item["sub"])) {
                                    $subMenuShow = $this->isNaviActive($item);
                                    $active = $subMenuShow?"class='active'":"";
                                
                                    $menu = "<li {$active}><a href='".CHtml::normalizeUrl($item["url"])."'><i class='fa fa-th-large'></i> <span class='nav-label'>{$item["label"]}</span></a></li>";
                                }else {
                                    $subMenuShow = false;
                                    foreach ($item["sub"] as $subItem) {
                                        $isNaviActive = $this->isNaviActive($subItem);
                                        $subActive = $isNaviActive?"class='active'":"";
                                        $subMenuShow = $subMenuShow || $isNaviActive;
                                        $active = $subMenuShow?"class='active'":"";
                                        
                                        $subMenu .= "<li {$subActive}><a href='".CHtml::normalizeUrl($subItem['url'])."'>{$subItem['label']}</a></li>";
                                    }    
                                    $menu = "<li {$active}><a href='".CHtml::normalizeUrl($item["url"])."'><i class='fa fa-th-large'></i> <span class='nav-label'>{$item["label"]}</span> <span class='fa arrow'></span></a>";
                                    $menu .= "<ul class='nav nav-second-level'>".$subMenu."</ul></li>";
                                }
                                echo $menu;
                            }
                        ?>
                    </ul>
                </div>
            </nav>
            
            <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li>
                            <span class="m-r-sm text-muted welcome-message"><a href="index" title="返回首页"><i class="fa fa-home"></i></a>欢迎使用<?php echo CHtml::encode(Yii::app()->name); ?></span>
                        </li>
                        <!--<li class="dropdown">
                            <a class="dropdown-toggle count-info" data-toggle="dropdown" href="index.html#">
                                <i class="fa fa-bell"></i>  <span class="label label-primary">8</span>
                            </a>
                            <ul class="dropdown-menu dropdown-alerts">
                                <li>
                                    <a href="mailbox.html">
                                        <div>
                                            <i class="fa fa-envelope fa-fw"></i> 您有16条未读消息
                                            <span class="pull-right text-muted small">4分钟前</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="profile.html">
                                        <div>
                                            <i class="fa fa-qq fa-fw"></i> 3条新回复
                                            <span class="pull-right text-muted small">12分钟钱</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <div class="text-center link-block">
                                        <a href="notifications.html">
                                            <strong>查看所有 </strong>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>-->
                        <li>
                            <?php if(!Yii::app()->user->isGuest):?>
                            <a href="<?php echo CHtml::normalizeUrl(array("/site/logout"));?>">
                                <i class="fa fa-sign-out"></i> 退出
                            </a>
                            <?php else:?>
                            <a href="<?php echo CHtml::normalizeUrl(array("/site/login"));?>">
                                <i class="fa fa-sign-in"></i> 登录
                            </a>                            
                            <?php endif;?>
                        </li>
                    </ul>

                </nav>
            </div>
                
            <?php echo $content; ?>
            
            <div class="footer">
                <div class="pull-right">
                    By <a href="“www.mihecn.com”" target="_blank">北京米和科技有限公司</a>
                </div>
                <div>
                    <strong>Copyright</strong> Mihe Ltd. &copy; 2015
                </div>
            </div>

        </div>
        </div>
    <?php 
        foreach ($this->dialogBox as $item) {
            echo $item;
        }
    ?>
    </body>
</html>