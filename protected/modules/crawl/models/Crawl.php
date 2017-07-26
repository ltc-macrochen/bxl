<?php
/**
 * Created by PhpStorm.
 * User: macrochen
 * Date: 2017/6/19
 * Time: 11:07
 */

include_once Yii::app()->basePath . '/extensions/phpQuery/phpQuery.php';

class Crawl {
    public $userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.96 Safari/537.36';
    public function __construct()
    {
        ini_set('xdebug.max_nesting_level', 600);
        ini_set('user_agent', $this->userAgent);
    }

    function curl_file_get_contents($durl, $refer = '', $cookie = ''){
        $header = array();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $durl);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
        curl_setopt($ch, CURLOPT_REFERER,$refer);
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_HEADER, true);
        $r = curl_exec($ch);
        curl_close($ch);
        return $r;
    }
}