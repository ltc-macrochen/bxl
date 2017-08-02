<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Utils {
    
    public static function serialize($arr) {
        return implode(",", $arr);
    }

    public static function unserialize($str) {
        return ($str == "") ? array() : explode(",", $str);
    }

    public static function serializeAppend($str, $append) {
        $arr = self::unserialize($str);
        array_push($arr, strval($append));
        return implode(",", $arr);
    }

    public static function serializeRemove($str, $delete) {
        $arr = self::unserialize($str);
        for ($i = 0; $i < count($arr); $i++) {
            if ($arr[$i] == $delete) {
                unset($arr[$i]);
                break;
            }
        }
        return implode(",", $arr);
    }    
    
    function floatcmp($f1,$f2,$precision = 3) {// are 2 floats equal  
        $e = pow(10,$precision);  
        $i1 = intval($f1 * $e);  
        $i2 = intval($f2 * $e);  
        return ($i1 == $i2);  
    }    
    function floatgtr($big,$small,$precision = 3) {// is one float bigger than another  
        $e = pow(10,$precision);  
        $ibig = intval($big * $e);  
        $ismall = intval($small * $e);  
        return ($ibig > $ismall);  
    }  
    function floatgtre($big,$small,$precision = 3) {// is on float bigger or equal to another  
        $e = pow(10,$precision);  
        $ibig = intval($big * $e);  
        $ismall = intval($small * $e);  
        return ($ibig >= $ismall);  
    }    

    /**
     * 将字符串转换为数组
     *
     * @param	string	$data	字符串
     * @return	array	返回数组格式，如果，data为空，则返回空数组
     */
    public static function string2array($data) {
        if ($data == '') {
            return array();
        }
        @eval("\$array = $data;");
        return $array;
    }

    /**
     * 将数组转换为字符串
     *
     * @param	array	$data		数组
     * @param	bool	$isformdata	如果为0，则不使用new_stripslashes处理，可选参数，默认为1
     * @return	string	返回字符串，如果，data为空，则返回空
     */
    public static function array2string($data, $isformdata = 1) {
        if ($data == '') {
            return '';
        }
        if ($isformdata) {
            $data = self::stripslashes($data);
        }
        return var_export($data, TRUE);
    }

    /**
     * 返回经stripslashes处理过的字符串或数组
     * @param $string 需要处理的字符串或数组
     * @return mixed
     */
    public static function stripslashes($string) {
        if (!is_array($string)) {
            return stripslashes($string);
        }
        foreach ($string as $key => $val) {
            $string[$key] = self::stripslashes($val);
        }
        return $string;
    }

    /**
     * UTF8中文编码字符截断
     */
    public static function utf8TextBrief($str, $lenth = 15, $etc = '...') {
        $start = 0;
        $len = strlen($str);
        $r = array();
        $n = 0;
        $m = 0;
        for ($i = 0; $i < $len; $i ++) {
            $x = substr($str, $i, 1);
            $a = base_convert(ord($x), 10, 2);
            $a = substr('00000000' . $a, - 8);
            if ($n < $start) {
                if (substr($a, 0, 1) == 0) {
                    
                } elseif (substr($a, 0, 3) == 110) {
                    $i += 1;
                } elseif (substr($a, 0, 4) == 1110) {
                    $i += 2;
                }
                $n ++;
            } else {
                if (substr($a, 0, 1) == 0) {
                    $r [] = substr($str, $i, 1);
                } elseif (substr($a, 0, 3) == 110) {
                    $r [] = substr($str, $i, 2);
                    $i += 1;
                } elseif (substr($a, 0, 4) == 1110) {
                    $r [] = substr($str, $i, 3);
                    $i += 2;
                } else {
                    $r [] = '';
                }
                if (++$m >= $lenth) {
                    break;
                }
            }
        }
        $trunstr = join('', $r);
        if (strlen($trunstr) < $len) {
            return $trunstr . $etc;
        } else {
            return $trunstr;
        }
    }

    /**
     * gbk转拼音
     * @param $txt
     */
    public static function gbkToPinyin($txt) {
        $txt = iconv('UTF-8', 'GBK', $txt);
        $l = strlen($txt);
        $i = 0;
        $pyarr = array();
        $py = array();
        $filename = Yii::app()->basePath . "/components/encoding/" . 'gb-pinyin.table';
        $fp = fopen($filename, 'r');
        while (!feof($fp)) {
            $p = explode("-", fgets($fp, 32));
            $pyarr[intval($p[1])] = trim($p[0]);
        }
        fclose($fp);
        ksort($pyarr);
        while ($i < $l) {
            $tmp = ord($txt[$i]);
            if ($tmp >= 128) {
                $asc = abs($tmp * 256 + ord($txt[$i + 1]) - 65536);
                $i = $i + 1;
            } else
                $asc = $tmp;
            $py[] = self::ascToPinyin($asc, $pyarr);
            $i++;
        }
        return strtolower(implode('', $py));
    }

    /**
     * Ascii转拼音
     * @param $asc
     * @param $pyarr
     */
    public static function ascToPinyin($asc, &$pyarr) {
        if ($asc < 128)
            return chr($asc);
        elseif (isset($pyarr[$asc]))
            return $pyarr[$asc];
        else {
            foreach ($pyarr as $id => $p) {
                if ($id >= $asc)
                    return $p;
            }
        }
    }
    
    /**
     * 将json_encode编码后的中文在解码回来
     * 
     * php5.4及后续版本，可以通过json_encode("中文", JSON_UNESCAPED_UNICODE)实现
     * @param string $str
     * @return string
     */
    public static  function decodeUnicode($str){
        return preg_replace_callback('/\\\\u([0-9a-f]{4})/i',
                                    create_function(
                                        '$matches',
                                        'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'
                                    ),
                                    $str);
    }        
    
    /**
     * 获取唯一ID
     * @param mix, interger 指定uuid长度，最小为14，最大为24
     *              string   指定uuid前缀
     *              null     获取16位uuid
     */
    public static function getUniqueId ($mix=null) {
        if (is_string($mix)) {
            $prefix = $mix;
        }else if (is_int($mix)) {
            $mix = max(array($mix, 14));
            $mix = min(array($mix, 24));
            $prefixLen = $mix-13;
            $prefix=mt_rand(substr("100000000000", 0, $prefixLen), substr("99999999999", 0, $prefixLen));
        }else {
            $prefix=mt_rand(100,999);
        }
        
        return strtoupper(uniqid($prefix));        
    }    
    
    /**
     * 获取唯一ID
     * @param mix, interger 指定uuid长度，最小为14，最大为24
     *              string   指定uuid前缀
     *              null     获取16位uuid
     */
    public static function getUniqueId16 () {
        $numberMap = array(
            array(1,2,3,4,5,6,7,8,9,0),
            array(1,3,5,7,9,2,4,6,8,0),
            array(2,4,6,8,0,1,3,5,7,9),
            array(6,5,4,3,2,1,7,8,9,0),
            array(0,7,5,2,4,8,3,6,1,9),
            array(7,0,9,6,3,2,5,8,1,4),
            array(0,2,4,6,9,8,7,5,3,1),
            array(1,0,2,9,3,8,4,7,5,6),            
            array(3,8,6,5,0,9,7,1,4,2),            
            array(4,1,3,2,0,5,8,6,9,7),
        );
        $rand6 = strval(mt_rand(100000,999999));
        $time10 = strval(time());
        for($i=0;$i<strlen($time10);$i++) {
            $time10{$i} = $numberMap[$i][intval($time10{$i})];
        }
        $final16 = $rand6{0}.$time10{0}.$rand6{1}.$time10{1}.$rand6{2}.$time10{2}.$rand6{3}.$time10{3}.$rand6{4}.$time10{4}.$rand6{5}.$time10{5}.$time10{6}.$time10{7}.$time10{8}.$time10{9};
        return $final16;        
    }        
    
    /**
     * 获取订单号
     * @param type $orderId，订单ID
     * @param type $payType，支付类型
     * @return type PPPPPPPP YYYYMMDDHHMMSS RRRRRR
     *               前缀(12) 年月日十分秒(14)随机数(6)
     */    
    public static function getOrderNo($prefix='') {
        if ($prefix == '') {
            return date("YmdHis") . str_pad(mt_rand(1,999999), 6, "0", STR_PAD_LEFT);
        }else {
            return substr($prefix,0,12) . date("YmdHis") . str_pad(mt_rand(1,999999), 6, "0", STR_PAD_LEFT);
        }
    }    

    public static function isMobileClient() {
        if (preg_match('/(alcatel|amoi|android|avantgo|blackberry|benq|cell|cricket|docomo|elaine|htc|iemobile|iphone|ipad|ipaq|ipod|j2me|java|midp|mini|mmp|mobi|motorola|nec-|nokia|palm|panasonic|philips|phone|playbook|sagem|sharp|sie-|silk|smartphone|sony|symbian|t-mobile|telus|up\.browser|up\.link|vodafone|wap|webos|wireless|xda|xoom|zte)/i', $_SERVER['HTTP_USER_AGENT'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function refererValidate($domain = null) {
        if (!isset($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER'] == "") {
            return false;
        } else {
            $domain = ($domain == null) ? Yii::app()->request->hostInfo : $domain;
            $res = parse_url($_SERVER['HTTP_REFERER']);
            return ($res['host'] == $domain) ? true : false;
        }
    }

    public static function timeValidate($begin = null, $end = null) {
        $secNow = time();
        if ($begin != null) {
            if ($secNow < strtotime($begin)) {
                return array("begin" => false, "end" => false);
            }
        }
        if ($end != null) {
            if ($secNow > strtotime($end)) {
                return array("begin" => true, "end" => false);
            }
        }
        return array("begin" => true, "end" => true);
    }

    const SECOND_FOR_ONE_MINITE = 60;
    const SECOND_FOR_ONE_HOUR = 3600;
    const SECOND_FOR_ONE_DAY = 86400;
    const SECOND_FOR_ONE_MONTH = 2592000;
    public static function formatExpiredTime($time, $status) {
        if ($status != QuestionAnswer::STATUS_NEW) {
            return Common::statusSelected($status, QuestionAnswer::$_STATUS_LIST);
        }
        
        $expiredTime = strtotime($time) + self::SECOND_FOR_ONE_HOUR * QuestionAnswer::QUESTION_EXPIRED_HOURS;
        $diff = $expiredTime - time();
        if ($diff > self::SECOND_FOR_ONE_HOUR) {             //过期时间大于1小时
            return "还有" . ceil($diff/self::SECOND_FOR_ONE_HOUR)."小时过期";
        }else if ($diff > self::SECOND_FOR_ONE_MINITE) {     //过期时间大于1分钟
            return "还有" . ceil($diff/self::SECOND_FOR_ONE_MINITE)."分钟过期";
        }else {
            return Common::statusSelected($status, QuestionAnswer::$_STATUS_LIST);;
        }
    }
    
    public static function formatShowTime($time) {
        if ($time == "0000-00-00 00:00:00") {
            return "";
        }
        $store = strtotime($time);
        $diff = time() - $store;
        if ($diff < self::SECOND_FOR_ONE_MINITE) {             //几分钟前
            return "刚刚";
        }        
        if ($diff < self::SECOND_FOR_ONE_HOUR) {             //几分钟前
            return ceil((strtotime(date('Y-m-d H:i:00')) - $store)/self::SECOND_FOR_ONE_MINITE)."分钟前";
        }else if ($diff < self::SECOND_FOR_ONE_DAY) {       //几小时前
            return ceil((strtotime(date('Y-m-d H:00:00')) - $store)/self::SECOND_FOR_ONE_HOUR)."小时前";
        }else if ($diff < self::SECOND_FOR_ONE_MONTH) {     //几天前
            return ceil((strtotime(date('Y-m-d 00:00:00')) - $store)/self::SECOND_FOR_ONE_DAY)."天前";
        }else if (date('Y',$store) == date('Y')) {
            return date('n月d日',$store);
        }else {
            return date('Y年',$store);
        }
    }
    
    public static function formatShowTimeAdmin($time) {
        if ($time == "0000-00-00 00:00:00") {
            return "";
        }        
        $store = strtotime($time);
        $diff = time() - $store;
        if ($diff < self::SECOND_FOR_ONE_MINITE) {             //几分钟前
            return "刚刚";
        }        
        if ($diff < self::SECOND_FOR_ONE_HOUR) {             //几分钟前
            return ceil((strtotime(date('Y-m-d H:i:00')) - $store)/self::SECOND_FOR_ONE_MINITE)."分钟前";
        }else if ($diff < self::SECOND_FOR_ONE_DAY) {       //几小时前
            return ceil((strtotime(date('Y-m-d H:00:00')) - $store)/self::SECOND_FOR_ONE_HOUR)."小时前";
        }else if ($diff < self::SECOND_FOR_ONE_MONTH) {     //几天前
            return ceil((strtotime(date('Y-m-d 00:00:00')) - $store)/self::SECOND_FOR_ONE_DAY)."天前";
        }else if (date('Y',$store) == date('Y')) {
            return date('n月d日',$store);
        }else {
            return date('Y年',$store);
        }
    }    
    
    public static function formatShowMoney($fen, $prefix="￥", $suffix="") {
        return $prefix.($fen/100).$suffix;
//        return '￥'.money_format("%.2n", $fen/100);
    }
    
    public static function formatVoiceResouceUrl ($origin) {
        if (Yii::app()->request->hostInfo == "http://wenda.m.beva.com") {
            return "http://ts.beva.cn" . str_replace("upload", "wenda", $origin);
        }else {
            return $origin;
        }
    }

        //分页数据条件
    public static function getPageCritera($condition, $order = '', $with = array(), $page = 20) {
        $param = array(
            'criteria' => array(
                'order' => $order,
                'condition' => $condition,
                'with' => $with
            ),
            'countCriteria' => array(
                'condition' => $condition,
            ),
            'pagination' => array(
                'pageSize' => $page,
            ),
        );
        return $param;
    }
    
    //格式化数字，超过10000时，显示“10000+”
    public static function formatCount($count) {
        return ($count > Constant::PEEK_COUNT_MAX_SHOW) ? Constant::PEEK_COUNT_MAX_SHOW . '+' : $count;
    }
    
    /**
     * 获取alert字符串
     * （后台返回alert信息）
     * @param type $alert
     * @param type $url
     * @return string
     */
    public static function getAlertBackString($alert = "", $url = "") {
        if (!empty($alert)) {
            $alertstr = "alert('" . $alert . "');\n";
        } else {
            $alertstr = "";
        }

        if (empty($url)) {
            $gotoStr = "window.history.back();\n";
        } else {
            $gotoStr = "window.location.href='" . $url . "'\n";
        }

        $content = "\t<script language=javascript>\n\t<!--\n";
        if (!empty($alertstr)) {
            $content .= $alertstr;
        }

        if ($url != "NONE") {
            $content .= $gotoStr;
        }
        $content .= "\t-->\n\t</script>\n";

        return $content;
    }

    /**
     * 过滤特殊字符
     * @param type $string
     * @return type
     */
    public static function filterSpecialChar($string) {
        if (!is_string($string)) {
            return $string;
        } else {
            $formatStr = preg_replace("/[^\w|\x{4e00}-\x{9fa5}|\s]+/iu", '', $string);
            if ($formatStr == '') {
                $formatStr = 'null';
            }
            return trim($formatStr);
        }
    }

    /**
     * 截取字符串
     *
     * @param  string $str     the origin string
     * @param  int    $lenth   the number n1%3 = 0 because utf
     * @param  string $etc     the short tail
     * @return string $str     the changed string
     */
    public static function getShortText($str, $lenth = 80, $etc = '...')
    {
        $start = 0;
        $len = strlen ( $str );
        $r = array ();
        $n = 0;
        $m = 0;
        for($i = 0; $i < $len; $i ++)
        {
            $x = substr ( $str, $i, 1 );
            $a = base_convert ( ord ( $x ), 10, 2 );
            $a = substr ( '00000000' . $a, - 8 );
            if ($n < $start)
            {
                if (substr ( $a, 0, 1 ) == 0)
                {
                }
                elseif (substr ( $a, 0, 3 ) == 110)
                {
                    $i += 1;
                }
                elseif (substr ( $a, 0, 4 ) == 1110)
                {
                    $i += 2;
                }
                $n ++;
            }
            else
            {
                if (substr ( $a, 0, 1 ) == 0)
                {
                    $r [] = substr ( $str, $i, 1 );
                }
                elseif (substr ( $a, 0, 3 ) == 110)
                {
                    $r [] = substr ( $str, $i, 2 );
                    $i += 1;
                }
                elseif (substr ( $a, 0, 4 ) == 1110)
                {
                    $r [] = substr ( $str, $i, 3 );
                    $i += 2;
                }
                else
                {
                    $r [] = '';
                }
                if (++ $m >= $lenth)
                {
                    break;
                }
            }
        }
        $trunstr = join ( '', $r );
        if (strlen ( $trunstr ) < $len){
            return $trunstr . $etc;
        }
        else{
            return $trunstr;
        }
    }

    /**
     * Get client ip address
     *
     * @return string $ip    the client ip address
     */
    public static function getClientIp()
    {
        if (isset ( $_SERVER ['HTTP_QVIA'] ))
        {
            $ip = qvia2ip ( $_SERVER ['HTTP_QVIA'] );
            if ($ip)
            {
                return $ip;
            }
        }

        if (isset ( $_SERVER ['HTTP_CLIENT_IP'] ) and ! empty ( $_SERVER ['HTTP_CLIENT_IP'] ))
        {
            return self::filterIp ( $_SERVER ['HTTP_CLIENT_IP'] );
        }
        if (isset ( $_SERVER ['HTTP_X_FORWARDED_FOR'] ) and ! empty ( $_SERVER ['HTTP_X_FORWARDED_FOR'] ))
        {
            $ip = strtok ( $_SERVER ['HTTP_X_FORWARDED_FOR'], ',' );
            do
            {
                $ip = ip2long ( $ip );

                //-------------------
                // skip private ip ranges
                //-------------------
                // 10.0.0.0 - 10.255.255.255
                // 172.16.0.0 - 172.31.255.255
                // 192.168.0.0 - 192.168.255.255
                // 127.0.0.1, 255.255.255.255, 0.0.0.0
                //-------------------
                if (! (($ip == 0) or ($ip == 0xFFFFFFFF) or ($ip == 0x7F000001) or (($ip >= 0x0A000000) and ($ip <= 0x0AFFFFFF)) or
                    (($ip >= 0xC0A8FFFF) and ($ip <= 0xC0A80000)) or (($ip >= 0xAC1FFFFF) and ($ip <= 0xAC100000))))
                {
                    return long2ip ( $ip );
                }
            }
            while ( $ip = strtok ( ',' ) );
        }
        if (isset ( $_SERVER ['HTTP_PROXY_USER'] ) and ! empty ( $_SERVER ['HTTP_PROXY_USER'] ))
        {
            return self::filterIp ( $_SERVER ['HTTP_PROXY_USER'] );
        }
        if (isset ( $_SERVER ['REMOTE_ADDR'] ) and ! empty ( $_SERVER ['REMOTE_ADDR'] ))
        {
            return self::filterIp ( $_SERVER ['REMOTE_ADDR'] );
        }
        else
        {
            return "0.0.0.0";
        }
    }

    /**
     * Filter the ip string
     *
     * @param  string $key          the ip string
     * @return boolean              whether the ip is correct
     */
    public static function filterIp($key)
    {
        $key = preg_replace("/[^0-9.]/", "", $key);
        return preg_match("/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/", $key) ? $key : "0.0.0.0";
    }

    /**
     * 删除字符串所有空格
     * @param $str
     * @return mixed
     */
    static public function trimStr($str){
        return preg_replace('/\s/', '', $str);
    }
}
