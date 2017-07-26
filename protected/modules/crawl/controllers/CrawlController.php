<?php
/**
 * Created by PhpStorm.
 * User: macrochen
 * Date: 2017/6/19
 * Time: 11:12
 */
include_once Yii::app()->basePath . '/extensions/phpQuery/phpQuery.php';
class CrawlController extends Controller {

    public function __construct()
    {
        ini_set('xdebug.max_nesting_level', 600);
        ini_set('user_agent', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.96 Safari/537.36');
        ob_implicit_flush(1);
    }

    /**
     * 5xss1.com
     */
    public function actionCrawl5xss1(){
        ob_end_flush();
        ob_implicit_flush(1);
        $startTime = time();

        //http://www.5xss1.com/videos?page=1
        $config = array(
            'baseCrawlUrl' => 'compress.zlib://http://www.5xss1.com/videos?page=1'
        );
        $m = Crawl_5xss1::getInstance($config);
        $ret = $m->doCrawl();
        var_dump($ret);


        $endTime = time();
        var_dump('used time:' . ($endTime-$startTime));
    }

    /**
     * 1717she.club
     */
    public function actionCrawl1717she(){
        ob_end_flush();
        ob_implicit_flush(1);
        $startTime = time();

        //http://www.1717she.club
        $config = array(
            'fileName' => 'vdata-1717she-' . date('Ymd') . '.txt',
            'baseCrawlUrl' => 'compress.zlib://http://www.1717she.club'
        );
        $m = Crawl_1717she::getInstance($config);
        $ret = $m->doCrawl();
        var_dump($ret);


        $endTime = time();
        var_dump('used time:' . ($endTime-$startTime));
    }

    /**
     * qyle2.com
     */
    public function actionCrawlqyle2(){
        ob_end_flush();
        ob_implicit_flush(1);
        $startTime = time();

        $config = array(
            'fileName' => 'vdata-qyle2-' . date('Ymd') . '.txt',
            'baseCrawlUrl' => 'http://www.qylsp4.com/recent/2'
        );
        $m = Crawl_qyle2::getInstance($config);
        $ret = $m->doCrawl();
        var_dump($ret);


        $endTime = time();
        var_dump('used time:' . ($endTime-$startTime));
    }

    /**
     * aotu17.com
     */
    public function actionCrawlaotu17(){
        ob_end_flush();
        ob_implicit_flush(1);
        $startTime = time();

        $config = array(
            'fileName' => 'vdata-aotu17-' . date('Ymd') . '.txt',
            'baseCrawlUrl' => 'http://www.aotu17.com/recent/2'
        );
        $m = Crawl_aotu17::getInstance($config);
        $ret = $m->doCrawl();
        var_dump($ret);


        $endTime = time();
        var_dump('used time:' . ($endTime-$startTime));
    }

    /**
     * video4sex.com
     */
    public function actionCrawlv4sex(){
        //var_dump(time());return;
        ob_end_flush();
        ob_implicit_flush(1);
        $startTime = time();

        $config = array(
            'baseCrawlUrl' => 'http://www.video4sex.com/online/vip'
        );
        $m = Crawl_video4sex::getInstance($config);
        $ret = $m->doCrawl();
        var_dump($ret);


        $endTime = time();
        var_dump('used time:' . ($endTime-$startTime));
    }

    /**
     * 抓取收录外链
     */
    public function actionA(){
        ob_implicit_flush(1);

        $startTime = time();
        $db = DBService::getInstance()->getDB();
        $catId = 7; //当前分类
        $table = 't_cms_content';
        $createTime = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $description = '抓取收录';
        $baseSql = "insert into {$table} (`catId`, `title`, `description`, `link`, `from_count_date`, `createTime`) values ";

        $url = 'http://dh.19xa.pw/';
        phpQuery::newDocumentFile($url);
        $ret = pq('#movie ~ ul:first li');
        //var_dump($ret);return;
        foreach ($ret as $item) {
            //保证链接有协议头，没有则跳过
            $url = pq($item)->find('a')->attr('href');
            $pattern = '/^(http|https)/';
            if(!preg_match($pattern, $url)){
                continue;
            }

            //链接信息
            $siteInfo = array(
                'url' => $url,
                'name' => pq($item)->text()
            );

            //失效链接则跳过
            if (@fopen($siteInfo['url'],'r') == false) {
                var_dump("url {$siteInfo['url']} is unvalid, continue");
                continue;
            }

            //地址处理，去掉www，以便模糊查询是否已经收录改链接
            $urlInfo = parse_url($siteInfo['url']);
            if(!isset($urlInfo['host']) || empty($urlInfo['host'])){
                continue;
            }
            $pattern = '/^www./';
            if(preg_match($pattern, $urlInfo['host'])){
                $urlInfo['host'] = str_replace('www.', '', $urlInfo['host']);
            }

            //查询是否已经收录过
//            $sql = "select * from {$table} where `link` like '%{$urlInfo['host']}%'";
//            $dbRet = mysql_query($sql, $db);
//            if($dbRet && mysql_fetch_assoc($dbRet)){
//                var_dump("url {$siteInfo['url']} is exists");
//                continue;
//            }

            //写入数据库
//            $isql = $baseSql . "({$catId}, '{$siteInfo['name']}', '{$description}', '{$siteInfo['url']}', '{$date}', '{$createTime}')";
//            $insertRet = mysql_query($isql, $db);
//            if($insertRet){
//                var_dump("success: " . $siteInfo['url'] . " -- " . $siteInfo['name']);
//            } else {
//                var_dump("fail: " . $siteInfo['url'] . " -- " . $siteInfo['name']);
//            }

            var_dump($siteInfo['url']);
        }


        $endTime = time();
        var_dump('used time:' . ($endTime-$startTime));
    }

    //远程入库
    public function actionToDB(){
        ob_end_flush();
        ob_implicit_flush(1);

        $config = array(
            'fileName' => 'vdata-upload.txt',//vdata-1717she-20170621.txt
            'delimiter' => '|', //字段分隔符
            'fieldCount' => 7,  //文件中每行的字段数
            'videoIndex' => 5   //视频地址的索引
        );
        $data = file($config['fileName']) or die('can not open file '. $config['fileName']);

        $table = Video::model()->tableName();
        $createTime = date('Y-m-d H:i:s');

        $continuePoint = true;
        $dataLength = count($data);
        $j = 0; //统计有效插入数据
        $baseSql = "insert into `{$table}` (`title`, `src_baseurl`, `src_id_encrypt`, `video_url`, `video_domain`, `duration`, `thumb`, `status`, `view_count`, `createTime`) values";
        foreach ($data as $line) {
            $lineData = explode($config['delimiter'], $line);

            if(count($lineData) != $config['fieldCount'] || empty($lineData[$config['videoIndex']])){
                //数据不全，跳过
                var_dump("{$lineData[0]} data unvalid");
                continue;
            }

            //断点继续
            if($lineData[0] == '1717she-tv-2191'){
                $continuePoint = false;
                continue;
            }
            if($continuePoint){
                var_dump('has handled');
                continue;
            }

            //排重插入数据库
            $db = Yii::app()->db_v;
            //$db = Yii::app()->db;
            $checkSql = "select * from {$table} where `src_id_encrypt` = '{$lineData[0]}'";
            $repeatCheck = $db->createCommand($checkSql)->execute();
            if($repeatCheck){
                var_dump("{$lineData[0]} repeat");
                continue;
            }

            $vUrlInfo = parse_url($lineData[$config['videoIndex']]);

            //视频信息
            $title = str_replace("'", '', $lineData[2]);
            $src_baseurl = '1717she-tv';//$lineData[1]
            $src_id_encrypt = $lineData[0];
            $video_url = $lineData[$config['videoIndex']];
            $video_domain = $vUrlInfo['host'];
            $duration = $lineData[3];
            $thumb = $this->trimStr($lineData[6]);  //缩略图
            $status  =0;
            $view_count = $this->trimStr($lineData[4]);//浏览数

            $sql = $baseSql . "('{$title}', '{$src_baseurl}', '{$src_id_encrypt}', '{$video_url}', '{$video_domain}', '{$duration}', '{$thumb}', {$status}, {$view_count}, '{$createTime}');";
            /*
            var_dump($sql);
            break;
            */

            //写入数据库
            $command = $db->createCommand($sql);
            $result = $command->execute();

            $j++;
            var_dump("insert success {$j}");
        }

        var_dump('datalength:' . $dataLength . '，insertLength:' . $j);
    }

    /**
     * 读取文件写入DB
     */
    public function actionFileToDB(){
        ob_end_flush();
        ob_implicit_flush(1);

        $config = array(
            'fileName' => 'vdata-upload.txt',//vdata-1717she-20170621.txt
            'delimiter' => '|', //字段分隔符
            'fieldCount' => 7,  //文件中每行的字段数
            'videoIndex' => 5   //视频地址的索引
        );
        $data = file($config['fileName']) or die('can not open file '. $config['fileName']);

        $table = Video::model()->tableName();
        $createTime = date('Y-m-d H:i:s');

        $dataLength = count($data);
        $i = 1; //方便拼长SQL语句
        $j = 1; //统计有效插入数据
        $sql = "insert into `{$table}` (`title`, `src_baseurl`, `src_id_encrypt`, `video_url`, `video_domain`, `duration`, `thumb`, `status`, `view_count`, `createTime`) values";
        foreach ($data as $line) {
            $lineData = explode($config['delimiter'], $line);

            $i++;
            if(count($lineData) != $config['fieldCount'] || empty($lineData[$config['videoIndex']])){
                //数据不全，跳过
                continue;
            }
            $vUrlInfo = parse_url($lineData[$config['videoIndex']]);

            $title = $lineData[2];
            $src_baseurl = 'qyle2_101_500';//$lineData[1]
            $src_id_encrypt = $lineData[0];
            $video_url = $lineData[$config['videoIndex']];
            $video_domain = $vUrlInfo['host'];
            $duration = $lineData[3];
            $thumb = $this->trimStr($lineData[6]);  //缩略图
            $status  =0;
            $view_count = $lineData[4];//浏览数

            $sql .= "('{$title}', '{$src_baseurl}', '{$src_id_encrypt}', '{$video_url}', '{$video_domain}', '{$duration}', '{$thumb}', {$status}, {$view_count}, '{$createTime}'),";
            if(($i%100 == 0) || ($i == $dataLength)){
                var_dump($i);

                //去掉最后一句的逗号，附上分号，结束sql语句拼装
                $sql = substr($sql, 0, strlen($sql) - 1);
                $sql .= ';';

                //写入数据库
                $db = Yii::app()->db_v;
                $command = $db->createCommand($sql);
                $result = $command->execute();
                $sql = "insert into `{$table}` (`title`, `src_baseurl`, `src_id_encrypt`, `video_url`, `video_domain`, `duration`, `thumb`, `status`, `view_count`, `createTime`) values";
            }
            $j++;
        }

        var_dump('datalength:' . $dataLength . '，insertLength:' . $j);
        return;
    }

    /**
     * ftp连接测试
     */
    public function actionFtp(){
        $conn = ftp_connect("ftp.caoliusese.com") or die("Could not connect");
        $ret = ftp_login($conn,"root_ftp@caoliusese.com","macro2017");
        $path = 'public_html';
        echo ftp_pwd($conn);

        //利用ftp选择进入目录
        ftp_chdir($conn,$path);

        echo ftp_pwd($conn);

        echo ftp_get($conn, 'index.txt', 'index.html', FTP_ASCII);
        return;

        //利用ftp创建目录
        //make_directory($conn,$path);

        //开始上传
        if(ftp_put($conn,$info[0]['savename'],getcwd().$upload->savePath.$info[0]['savename'],FTP_BINARY)){
            unlink(getcwd().$upload->savePath.$info[0]['savename']);
        }
        ftp_close($conn);
        var_dump($ftp);return;
    }

    /**
     * 去掉所有空格
     * @param $str
     * @return mixed
     */
    public function trimStr($str){
        return preg_replace('/\s/', '', $str);
    }

    public function actionB(){
        //ob_end_flush();
        ob_implicit_flush(1);
        header("Content-type: text/html; charset=utf-8");

        $url = 'https://www.pengfu.com/qutu_2.html';
        phpQuery::newDocumentFile($url);
        $ret = pq('.list-item');
        foreach ($ret as $item) {
            $articleInfo = array();
            $articleInfo['id'] = pq($item)->attr('id');
            $gif = pq($item)->find('.content-img img')->attr('gifsrc');
            $articleInfo['img'] = $gif ? $gif : pq($item)->find('.content-img img')->attr('src');
            $articleInfo['title'] = pq($item)->find('dl dd h1 a')->text();
            $articleInfo['vGood'] = pq($item)->find('.action .ding em')->text();
            $articleInfo['vBad'] = pq($item)->find('.action .cai em')->text();
            $articleInfo['tags'] = array();

            $tagItem = pq($item)->find('.action .fr a');
            foreach ($tagItem as $tag) {
                $articleInfo['tags'][] = pq($tag)->text();
            }

            var_dump($articleInfo);
        }
        return;

        //------------------更新视频源ID-------------------
//        $minId = 17425;
//        $maxId = 17720;
//        $db = Yii::app()->db_v;
//
//        for($i = $minId; $i<=$maxId;$i++) {
//            $sql = "select * from t_video where video_domain = 'www.1717she.club' and id = {$i}";
//            $ret = $db->createCommand($sql)->queryAll();
//
//            if($ret){
//                if($ret[0]['src_baseurl'] == '1717she.club'){
//                    //已经处理过，则跳过
//                    continue;
//                }
//
//                $srcBaseurl = $ret[0]['src_baseurl'];
//                $pathInfo = explode('/', parse_url($srcBaseurl, PHP_URL_PATH));
//                $srcId = "1717she-club-{$pathInfo[2]}";
//                $sql = "update t_video set src_baseurl = '1717she.club', src_id_encrypt = '{$srcId}' where id = {$i}";
//                $db->createCommand($sql)->execute();
//                var_dump("$i");
//            }
//        }
//
//        var_dump('end');
//        return;

        //------------------视频采集-------------------
//        $url = "http://www.porna5.com/index.php?s=vod-search-p-1.html";
//        phpQuery::newDocumentFile($url);
//        $ret = pq('.slideboxfhg');
//        foreach ($ret as $v) {
//            $vInfo = array(
//                'id'            => '',  //视频记录ID
//                'playUrl'       => '',  //播放页地址
//                'title'         => '',  //标题
//                'duration'      => '',  //时长
//                'view_count'    => '',  //浏览量
//                'vUrl'          => '',  //视频地址
//                'picUrl'        => ''   //图片地址
//            );
//            $user = pq($v)->find('.infotext a:eq(0)')->text();
//            $vInfo['id'] = '';
//            $vInfo['playUrl'] = pq($v)->find('a:eq(0)')->attr('href');
//            $vInfo['title'] = pq($v)->find('.slideboximg')->attr('alt');
//            $vInfo['duration'] = pq($v)->find('.datetag')->text();
//            $vInfo['view_count'] = pq($v)->find('.views')->text();
//            $vInfo['vUrl'] = '';
//            $vInfo['picUrl'] = pq($v)->find('.slideboximg')->attr('data-src');
//            $vInfo['user'] = $user;
//            print_r($vInfo);
//        }

//        $playUrl = "http://www.porna5.com/index.php?s=vod-read-id-20286.html";
//        phpQuery::newDocumentFile($playUrl);
//        $ret = pq('iframe')->attr('src');
//        var_dump($ret);

        //$v = phpQuery::newDocumentFile($ret);
        //$v = pq('video')->attr('src');
//        return;


        //------------------视频地址解析-------------------
//        $mp4 = "http://www.qyle2.com/file/26378/3/093c1c8795816aba0c72/1499399000/mp4/26378.mp4";
//        var_dump(parse_url($mp4, PHP_URL_HOST));return;
//        $c = new Crawl();
//        $ret = $c->curl_file_get_contents($mp4, 'http://www.qyle2.com');
//
//        $remoteVurl = "";
//        if($ret){
//            $retData = explode("\r\n", $ret);
//            foreach ($retData as $item) {
//                if(strpos($item, 'Location') !== false){
//                    $remoteVurl = str_replace("Location: ", "", $item);
//                }
//            }
//        }
//        var_dump($remoteVurl);
//        var_dump('end');
    }
}