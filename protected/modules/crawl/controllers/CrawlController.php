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
    }


    /**
     * pengfu.com
     * gifcool.com
     * gaoxiaogif.com
     * https://www.qiushibaike.com/gif/6/page_3/
     */
    public function actionCrawlpengfu(){
        ob_end_flush();
        ob_implicit_flush(1);
        $startTime = time();

        $config = array(
            'fileName' => 'vdata-pengfu-' . date('Ymd') . '.txt',
        );
        $m = Crawl_pengfu::getInstance($config);
        $ret = $m->doCrawl();
        var_dump($ret);


        $endTime = time();
        var_dump('used time:' . ($endTime-$startTime));
    }

    /**
     * qiushibaike.com
     * https://www.qiushibaike.com/gif/6/page_3/
     */
    public function actionCrawlqiubai(){
        ob_end_flush();
        ob_implicit_flush(1);
        $startTime = time();

        $config = array(
            'fileName' => 'vdata-qiubai-' . date('Ymd') . '.txt',
        );
        $m = Crawl_qiubai::getInstance($config);
        $ret = $m->doCrawl();
        var_dump($ret);


        $endTime = time();
        var_dump('used time:' . ($endTime-$startTime));
    }

    /**
     * gaoxiaogif.com
     *
     */
    public function actionCrawl_gaoxiaogif(){
        ob_end_flush();
        ob_implicit_flush(1);
        $startTime = time();

        $config = array(
            'fileName' => 'vdata-gaoxiaogif-index-' . date('Ymd') . '.txt',
        );
        $m = Crawl_gaoxiaogif::getInstance($config);
        $ret = $m->doCrawl();
        var_dump($ret);


        $endTime = time();
        var_dump('used time:' . ($endTime-$startTime));
    }

    /**
     * gifcool.com
     *
     */
    public function actionCrawl_gifcool(){
        ob_end_flush();
        ob_implicit_flush(1);
        $startTime = time();

        $config = array(
            'fileName' => 'vdata-gifcool-' . date('Ymd') . '.txt',
        );
        $m = Crawl_gifcool::getInstance($config);
        $ret = $m->doCrawl();
        var_dump($ret);


        $endTime = time();
        var_dump('used time:' . ($endTime-$startTime));
    }

    //远程入库
    public function actionToDB(){
        ob_end_flush();
        ob_implicit_flush(1);

        $config = array(
            'fileName' => 'vdata-upload.txt',//vdata-1717she-20170621.txt
            'prefix' => '',
            'domain' => 'gaoxiaogif.com',
            'delimiter' => '|',     //字段分隔符
            'fieldCount' => 7,      //文件中每行的字段数
            'indexId' => 0,         //源网站ID索引
            'indexTitle' => 1,      //标题索引
            'indexImg' => 2,        //图片索引
            'indexContent' => 3,    //文章索引
            'indexVgood' => 4,
            'indexVbad' => 5,
            'indexTags' => 6
        );
        $data = file($config['fileName']) or die('can not open file '. $config['fileName']);

        $table = CmsPost::model()->tableName();
        $createTime = date('Y-m-d H:i:s');

        $continuePoint = true;
        $dataLength = count($data);
        $j = 0; //统计有效插入数据
        $baseSql = "insert into `{$table}` (`catId`, `title`, `srcId`, `imgUrl`, `audioUrl`, `content`, `vGood`, `vBad`, `status`, `createTime`) values";
        foreach ($data as $line) {
            $lineData = explode($config['delimiter'], $line);

            if(count($lineData) != $config['fieldCount'] || (empty($lineData[$config['indexImg']]) && empty($lineData[$config['indexContent']]))){
                //数据不全，跳过
                var_dump("{$lineData[0]} data unvalid");
                continue;
            }

            //断点继续
//            if($lineData[0] == '1718317'){
//                $continuePoint = false;
//                continue;
//            }
//            if($continuePoint){
//                var_dump('has handled');
//                continue;
//            }

            $catId = CmsPost::POST_CATEGORY_CONTENT;
            $title = $lineData[$config['indexTitle']];
            $srcId = $config['prefix'] . $lineData[$config['indexId']];
            $imgUrl = $lineData[$config['indexImg']];
            $content = $lineData[$config['indexContent']];
            $vGood = intval($this->trimStr($lineData[$config['indexVgood']]));
            $vBad = intval($this->trimStr($lineData[$config['indexVbad']]));
            $status = 0;
            if(!empty($imgUrl)){
                $catId = CmsPost::POST_CATEGORY_PIC;
            }

            //排重插入数据库
            //$db = Yii::app()->db_v;
            $db = Yii::app()->db;
            $checkSql = "select * from {$table} where `srcId` = '{$srcId}'";
            $repeatCheck = $db->createCommand($checkSql)->execute();
            if($repeatCheck){
                var_dump("{$srcId} repeat");
                continue;
            }
            $sql = $baseSql . "({$catId}, '{$title}', '{$srcId}', '{$imgUrl}', '{$config['domain']}', '{$content}', $vGood, $vBad, {$status}, '{$createTime}');";

            //写入数据库
            $command = $db->createCommand($sql);
            $result = $command->execute();

            $j++;
            var_dump("insert success {$j}");
        }

        var_dump('datalength:' . $dataLength . '，insertLength:' . $j);
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
        $url = "http://wx3.sinaimg.cn/thumbnail/70f86863gy1fi58c6j4jsg2057054e81.gif";
        $search = array('small', 'thumbnail', 'www.sinaimg.cn/dy/slidenews/77_t160/');
        $replace = array('bmiddle', 'mw1024', 'storage.slide.news.sina.com.cn/slidenews/77_ori/');
        var_dump(str_replace($search, $replace, $url));

        return;
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
    }
}