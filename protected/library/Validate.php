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

    /**
     * 获取refer
     * @return string
     */
    public static function getRefer() {
        $pathArray = $_SERVER;

        $referer = isset($pathArray['HTTP_REFERER']) ? str_replace(array("&amp;"), array("&"), htmlspecialchars($pathArray['HTTP_REFERER'], ENT_QUOTES)) : '';

        if (!empty($referer) && !preg_match("/^http(s?):\/\//", $referer)) {
            $referer = '';
        }

        return $referer;
    }

    /**
     * 获取domain
     * @return type
     */
    public static function getDomain() {
        return str_replace('http://', '', Yii::app()->request->hostInfo);
    }

    /**
     * 校验refer
     * @return boolean
     */
    static public function checkRefer() {
        $refer = self::getRefer();

        if ($refer == '') {
            return false;
        } else {
            $res = parse_url($refer);
            $domain = self::getDomain();
            return ($res['host'] == $domain) ? true : false;
        }
    }

    /**
     * 检测一个用户名的合法性
     *
     * @param string $str 需要检查的用户名字符串
     * @param int $chkType 要求用户名的类型，
     * @		  1为英文、数字、下划线，2为任意可见字符，3为中文(GBK)、英文、数字、下划线，4为中文(UTF8)、英文、数字，缺省为1
     * @return bool 返回检查结果，合法为true，非法为false
     */
    public static function chkUserName($str, $chkType = 1) {
        switch ($chkType) {
            case 1 :
                $result = preg_match("/^[a-zA-Z0-9_]+$/i", $str);
                break;
            case 2 :
                $result = preg_match("/^[\w\d]+$/i", $str);
                break;
            case 3 :
                $result = preg_match("/^[_a-zA-Z0-9\0x80-\0xff]+$/i", $str);
                break;
            case 4 :
                $result = preg_match("/^[_a-zA-Z0-9\u4e00-\u9fa5]+$/i", $str);
                break;
            default :
                $result = preg_match("/^[a-zA-Z0-9_]+$/i", $str);
                break;
        }
        return $result;
    }

    /**
     * email地址合法性检测
     */
    public static function isEmail($value) {
        return preg_match("/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/", $value);
    }

    /**
     * URL地址合法性检测
     */
    public static function isUrl($value) {
        return preg_match("/^http:|https:\/\/[\w-]+\.[\w]+[\S]*/", $value);
    }

    /**
     * 是否是一个合法域名
     */
    public static function isDomainName($str) {
        return preg_match("/^[a-z0-9]([a-z0-9-]+\.){1,4}[a-z]{2,5}$/i", $str);
    }

    /**
     * 检测IP地址是否合法
     */
    public static function isIpAddr($ip) {
        return preg_match("/^[\d]{1,3}(?:\.[\d]{1,3}){3}$/", $ip);
    }

    /**
     * 邮编合法性检测
     * @return boolean true表示合法，false表示非法
     */
    public static function isPostalCode($postal_code) {
        return (is_numeric($postal_code) && (strlen($postal_code) == 6));
    }

    /**
     * 电话(传真)号码合法性检测
     * @return boolean true表示合法，false表示非法
     */
    public static function isPhone($value) {
        return preg_match("/^(\d){2,4}[\-]?(\d+){6,9}$/", $value);
    }

    /**
     * 手机号码合法性检查
     * @return boolean true表示合法，false表示非法
     */
    public static function isMobile($mobile) {
        return preg_match("/^1[3458]\d{9}$/", $mobile) ? true : false;
    }

    /**
     * 身份证号码合法性检测
     */
    public static function isIdCard($value) {
        return preg_match("/^(\d{15}|\d{17}[\dx])$/i", $value);
    }

    /**
     * 严格的身份证号码合法性检测(按照身份证生成算法进行检查)
     */
    public static function chkIdCard($value) {
        if (strlen($value) != 18) {
            return false;
        }
        $wi = array(
            7,
            9,
            10,
            5,
            8,
            4,
            2,
            1,
            6,
            3,
            7,
            9,
            10,
            5,
            8,
            4,
            2
        );
        $ai = array(
            '1',
            '0',
            'X',
            '9',
            '8',
            '7',
            '6',
            '5',
            '4',
            '3',
            '2'
        );
        $value = strtoupper($value);
        $sigma = '';
        for ($i = 0; $i < 17; $i++) {
            $sigma += ((int) $value{$i}) * $wi[$i];
        }
        $parity_bit = $ai[($sigma % 11)];
        if ($parity_bit != substr($value, -1)) {
            return false;
        }
        return true;
    }

    /**
     * 检测是否包含特殊字符
     * @return boolean true表示含有特殊字符，false表示不含有特殊字符
     */
    public static function checkSpecialWord($string) {
        return (bool) preg_match('/>|<|,|\[|\]|\{|\}|\?|\/|\+|=|\||\'|\\|\"|:|;|\~|\!|\@|\*|\$|\%|\^|\&|\(|\)|`/i', $string);
    }

    /**
     * 过滤特殊字符
     */
    public static function filterSpecialWord($value) {
        return preg_replace('/>|<|,|\[|\]|\{|\}|\?|\/|\+|=|\||\'|\\|\"|:|;|\~|\!|\@|\#|\*|\$|\%|\^|\&|\(|\)|`/i', "", $value);
    }

    /**
     * 过滤SQL注入攻击字符串
     */
    public static function filterSqlInject($str) {
        if (!get_magic_quotes_gpc()) {
            return addslashes($str);
        }
        return $str;
    }

    /**
     * 过滤HTML标签
     *
     * @param string text - 传递进去的文本内容
     * @param bool $strict - 是否严格过滤（严格过滤将把所有已知HTML标签开头的内容过滤掉）
     * @return string 返回替换后的结果
     */
    public static function stripHtmlTag($text, $strict = false) {
        $text = strip_tags($text);
        if (!$strict) {
            return $text;
        }
        $html_tag = "/<[\/|!]?(html|head|body|div|span|DOCTYPE|title|link|meta|style|p|h1|h2|h3|h4|h5|h6|strong|em|abbr|acronym|address|bdo|blockquote|cite|q|code|ins|del|dfn|kbd|pre|samp|var|br|a|base|img|area|map|object|param|ul|ol|li|dl|dt|dd|table|tr|td|th|tbody|thead|tfoot|col|colgroup|caption|form|input|textarea|select|option|optgroup|button|label|fieldset|legend|script|noscript|b|i|tt|sub|sup|big|small|hr)[^>]*>/is";
        return preg_replace($html_tag, "", $text);
    }

    /**
     * 转换HTML的专有字符
     */
    public static function filterHtmlWord($text) {
        if (function_exists('htmlspecialchars')) {
            return htmlspecialchars($text);
        }
        $search = array(
            "&",
            '"',
            "'",
            "<",
            ">"
        );
        $replace = array(
            "&amp;",
            "&quot;",
            "&#039;",
            "&lt;",
            "&gt;"
        );
        return str_replace($search, $replace, $text);
    }

    /**
     * 剔除JavaScript、CSS、Object、Iframe
     */
    public static function filterScript($text) {
        $text = preg_replace("/(javascript:)?on(click|load|key|mouse|error|abort|move|unload|change|dblclick|move|reset|resize|submit)/i", "&111n\\2", $text);
        $text = preg_replace("/<style.+<\/style>/iesU", '', $text);
        $text = preg_replace("/<script.+<\/script>/iesU", '', $text);
        $text = preg_replace("/<iframe.+<\/iframe>/iesU", '', $text);
        $text = preg_replace("/<object.+<\/object>/iesU", '', $text);
        return $text;
    }

    /**
     * 过滤JAVASCRIPT不安全情况
     */
    public static function escapeScript($string) {
        $string = preg_replace("/(javascript:)?on(click|load|key|mouse|error|abort|move|unload|change|dblclick|move|reset|resize|submit)/i", "&111n\\2", $string);
        $string = preg_replace("/<script(.*?)>(.*?)<\/script>/si", "", $string);
        $string = preg_replace("/<iframe(.*?)>(.*?)<\/iframe>/si", "", $string);
        $string = preg_replace("/<object.+<\/object>/iesU", '', $string);
        return $string;
    }
}
