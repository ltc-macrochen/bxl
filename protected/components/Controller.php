<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {

    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/column1';
    
    /**
     * 站点导航地图
     * @var type 
     */
    public $naviMap = array(
        array('label' => '用户统计', 'url' => array('/stat/user'), 'icon'=>"fa-user"),
        array('label' => '移动端统计', 'url' => array('/stat/mobile'), 'icon'=>"fa-tablet"),
        array('label' => 'PC端统计', 'url' => array('/stat/pc'), 'icon'=>"fa-desktop"),
        array('label' => '内容播放统计', 'url' => array('/stat/content'), 'icon'=>"fa-video-camera"),
        array('label' => '数据录入', 'url' => array(""), 'sub'=>array(
            array('label' => '用户', 'url' => array('/userStat/index')),
            array('label' => '移动端', 'url' => array('/mobileStat/index')),
            array('label' => 'PC端', 'url' => array('/pcStat/index')),
            array('label' => '内容播放', 'url' => array('/contentStat/index')),
        ), 'icon'=>"fa-database"),    
        array('label' => '系统配置', 'url' => array('/admin/adminUser/index'), 'sub'=>array(
            array('label' => '管理员配置', 'url' => array('/admin/adminUser/index')),
            array('label' => '角色配置', 'url' => array('/admin/adminRole/index')),
        ), 'icon'=>"fa-cog"),        
    );
    
    public $navi = null;
    public $subNavi = null;

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();

    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();
  
    //页面标题
    public $title = "";
    //弹框
    public $dialogBox = array();
    
    
    /**
     * 角色访问权限设置
     * 指定所有控制器"*"，指定整个控制器"site"，或指定具体的Action"site/index" 
     */
    public $roleFilter = array(
        //未登录用户，不能进行任何操作
        AdminRole::ROLE_GUEST => array("deny"=>array("/site/mail","/site/config"),"permit"=>array("site")),
        //系统管理员，可以进行所有操作
        AdminRole::ROLE_ADMINISTRATOR => array("permit"=>"*"),
        /*
         * 其它角色根据ACL配置进行权限过滤
         * 使用场景一：只允许特定action被访问。只配置一个permit节点即可array("permit"=>array())
         * 使用场景二：只允许特定action不被访问。只配置一个deny节点即可array("deny"=>array())
         * 
         * 使用场景三：更加复杂的多重控制。请注意是否开启default deny all模式，以及最后一个节点是否为permit。
         * ACL控制器是deny优先原则。只要特定controller或action有deny节点匹配，就会被过滤掉，前后的permit节点不起作用。
         * ACL控制器默认开启default deny all模式。
         * default deny all模式说明：最后一个节点如果是permit，且特定controller或action没有被permit或deny，则默认行为是deny，防止因遗漏deny造成的权限漏洞。
         * default deny all如何关闭：AclFilter::getInstance()->doFilter()的第三个参数传入false
         */
        AdminRole::ROLE_FINANCE => array("permit"=>array("site","userPay","userIncome","userIncomePay","home/finance","statByDay/prepareData")),
    );
    
    /**
     * 构造函数
     * 每个控制器创建时都会执行
     * @param type $id 控制器名称
     * @param type $module 模块名称
     */
    public function __construct($id, $module = null) {
        parent::__construct($id, $module);
        
        $this->title = ucfirst($id);
		$this->title = isset(Constant::$_MODEL_LANGUAGE_MAP[ucfirst($id)]) ? Constant::$_MODEL_LANGUAGE_MAP[ucfirst($id)] : '后台管理系统';
        
        //强制登录检查
        if ($id !="site" && Yii::app()->user->isGuest) {
            Yii::app()->user->loginRequired();
        }
        
        //将项目管理员通过配置获取的功能放入【角色访问权限控制器roleFilter】中
        $this->adminFunctionFilter();
        
        //登录用户权限检查
        $roleId = Yii::app()->user->isGuest?0:Yii::app()->user->roleId;
        $this->naviMap = $this->filterNavi($this->naviMap, $this->roleFilter[$roleId]);
    }
    
    /**
     * CController基类中的beforeAction重写
     * 每个action执行先YII都会调用这个函数，如果函数返回true则action被执行，否则action不被执行
     * @param type $action action名称
     * @return boolean
     */
    public function beforeAction($action) {
        //初始化导航
        $this->initNavi();
        
        $url = "/" . lcfirst($this->id) . "/" . lcfirst($action->id);
        $roleId = Yii::app()->user->isGuest ? 0 : Yii::app()->user->roleId;
        if (AclFilter::getInstance()->doFilter($url, $this->roleFilter[$roleId])) {
            //调用父类方法，可以让action里面的accessRules filter继续被执行
            return parent::beforeAction($action);
        } else {
            throw new CHttpException(500, "对不起，您所属角色没有访问此页面的权限！");
            return false;
        }
    }

        /**
     * 导航是否被选中
     * @param type $item
     * @return boolean
     */
    public function isNaviActive($item) {
        $exactMatchList = array(
            "home",
        );
        
        if (isset($item['url']) && is_array($item['url'])) {
            $route = array_slice(explode("/", $this->getRoute()), 0, 3);
            $path = array_slice(explode("/", trim($item['url'][0], '/')),0,3);
            if (count($path) == 1) {
                return true;
            }  
            if (!in_array($route[1], $exactMatchList)) {
                array_pop($route);
                array_pop($path);
            }
            
            if(!array_diff($route,$path) && !array_diff($path,$route)){
                return true;
            }
        }
        
        return false;     
    }

    /**
     * 操作是否被选中
     * @param type $item
     * @return boolean
     */
    public function isMenuActive($item) {
        if (isset($item['url']) && is_array($item['url'])) {
            $route = $this->getRoute();
            $path = trim($item['url'][0], '/');  
            if (strncasecmp(strrev($route),strrev($path),strlen($path))==0) {
                return true;
            }
        }
        
        return false;     
    }    
    
    /**
     * 过滤导航内容
     * @param type $naviMap
     * @param type $filter
     * @return array
     */
    protected function filterNavi($naviMap,$filter) {
        $output = array();
        foreach ($naviMap as $item) {
            //没有子菜单
            if (!isset($item["sub"])) {
                if (AclFilter::getInstance()->doFilter($item["url"][0], $filter)) {
                    array_push($output, $item);
                }
                continue;
            }
            
            //有子菜单
            $subNew = array();
            foreach ($item["sub"] as $subItem) {
                if (AclFilter::getInstance()->doFilter($subItem["url"][0], $filter)) {
                    array_push($subNew, $subItem);
                }
            }

            if (!empty($subNew)) {
                $item["sub"] = $subNew;
                array_push($output, $item);
            }
        }
        return $output;
    }
    
    /**
     * 生成一级导航和二级导航
     */
    protected function initNavi() {
        $navi = array();
        foreach ($this->naviMap as $item) {
            $subNavi = array();
            $naviActive = $this->isNaviActive($item);
            if (!empty($item["sub"])) {
                foreach ($item["sub"] as $subItem) {
                    //初始化左侧导航
                    $icon = isset($subItem['icon'])?'<i class="zmdi zmdi-hc-fw '.$subItem['icon'].'"></i>':'<i class="zmdi zmdi-hc-fw"></i>';
                    if (isset($subItem['url'])) {
                        $subNaviActive = $this->isNaviActive($subItem);
                        $naviActive = $naviActive || $subNaviActive;
                        $subNaviActiveClass = $subNaviActive?"class='active'":"";                        
                        $subNavi[] = '<li><a '.$subNaviActiveClass.' href="'.CHtml::normalizeUrl($subItem["url"]).'">'.$icon.'<span>'.$subItem["label"].'</span></a></li>';
                    }else {
                        $subNavi[] = '<li>'.$icon.'<span>'.$subItem["label"].'</span></a></li>';
                    }
                }
            }
            
            //初始化顶部导航
            if ($naviActive) {
                $navi[] = '<li><a class="active" href="'.CHtml::normalizeUrl($item["url"]).'">'.$item["label"].'</a></li>';
                $this->subNavi = implode("", $subNavi);
            }else {
                $navi[] = '<li><a href="'.CHtml::normalizeUrl($item["url"]).'">'.$item["label"].'</a></li>';
            }
        }
        $this->navi = implode("", $navi);
    }
    
    /**
     * 将管理员通过配置获取的功能放入【角色访问权限控制器roleFilter】中
     */
    public function adminFunctionFilter() {

    }    
}