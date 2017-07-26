<?php
/**
 * Created by PhpStorm.
 * User: macrochen
 * Date: 2017/6/22
 * Time: 11:42
 */
class DBService {
    public static $instance = null;
    protected $db = null;
    private $config = array(
        'host' => null,     //数据服务器IP
        'user' => null,     //用户名
        'password' => null, //密码
        'dbname' => null    //数据库名
    );

    public function getConfig() {
        return $this->config;
    }

    public static function getInstance($config = array()){
        if(!(self::$instance instanceof self)){
            self::$instance = new self();
        }

        if(!empty($config)){
            self::$instance->config = array_merge(self::$instance->config, $config);
        }

        //数据存储文件
        if(empty($config)){
            self::$instance->config = array(
                'host' => '',
                'user' => '',
                'password' => '',
                'dbname' => ''
            );
        } else {
            self::$instance->config = array_merge(self::$instance->config, $config);
        }

        return self::$instance;
    }

    public function getDB(){
        $config = $this->getConfig();
        $conn = mysql_connect($config['host'], $config['user'], $config['password']);
        if(!$conn){
            die('Could not connect: ' . mysql_error());
        }

        $selectDb = mysql_select_db($config['dbname'], $conn);
        if(!$selectDb){
            die ("Can't use {$config['dbname']} : " . mysql_error());
        }

        mysql_query('set names utf8');

        $this->db = $conn;
        return $this->db;
    }
}