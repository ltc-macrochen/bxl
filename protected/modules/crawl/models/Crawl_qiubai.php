<?php
/**
 * Created by PhpStorm.
 * User: macrochen
 * Date: 2017/6/19
 * Time: 11:05
 */

class Crawl_qiubai extends Crawl {
    public static $instance = null;
    private $config = array(
        'fileName' => null,     //存储爬取数据的文件名称
        'baseCrawlUrl' => ''    //爬取的目标URL
    );

    public function __construct()
    {
        parent::__construct();
    }

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
        if(empty(self::$instance->config['fileName'])){
            self::$instance->config['fileName'] = 'vdata-qiubai-' . date('Ymd') . '.txt';
        }

        //爬取URL
//        if(empty(self::$instance->config['baseCrawlUrl'])){
//            throw new CException('crawl url should not be empty');
//        }

        return self::$instance;
    }

    public function doCrawl(){
        $config = $this->getConfig();
        //抓取处理入口
        //phpQuery::newDocumentFile($config['baseCrawlUrl']);

        $lastPageNum = 84;

        //循环爬取
        $count = 1;
        $continuePoint = true;
        for($i = 1; $i <= $lastPageNum; $i++){
            //段子
            //趣图
            //热门
            $spiderUrl = "https://www.qiushibaike.com/gif/6/page_{$i}/";
            $this->dataFormat($spiderUrl, $config['fileName'], $count, $continuePoint);
        }

        return array('err' => 0, 'msg' => 'success');
    }

    /**
     * 数据格式化入库
     * @param $crawlUrl
     * @param $fileName
     */
    public function dataFormat($crawlUrl, $fileName, &$count, &$continuePoint) {
        ob_implicit_flush(1);   //浏览器实时输出

        $file = fopen($fileName, 'a+') or die('unable to open file!');

        phpQuery::newDocumentFile($crawlUrl);
        $ret = pq('a.images');

        foreach ($ret->elements as $item) {
            //处理记录数
            var_dump($count);
            $count++;

            //断点继续
//            if($count == 3194){
//                $continuePoint = false;
//                continue;
//            }
//            if($continuePoint){
//                continue;
//            }

            $info = array(
                'id'            => '',  //记录ID
                'title'         => '',  //标题
                'img'           => '',  //图片
                'content'       => '',  //内容
                'vGood'         => '',  //点赞数
                'vBad'          => '',  //点菜数
                'tags'          => ''   //标签
            );

            $detailUrl = pq($item)->attr('href');

            $info['id'] = '';
            $gif = '';
            $info['img'] = $gif ? $gif : '';
            $info['title'] = pq($item)->find('.imagesText')->text();
            $info['vGood'] = 0;
            $info['vBad'] = 0;
            $info['tags'] = '';
            $info['content'] = '';

            $info['title'] = str_replace(array('"',"'"), '”', $info['title']);
            $info['content'] = str_replace(array('"',"'"), '”', $info['content']);

            //唯一源ID
            $detailUrlInfo = explode('/', $detailUrl);
            $info['id'] = $detailUrlInfo[2];

            $detailUrl = "https://www.qiushibaike.com" . $detailUrl;
            phpQuery::newDocumentFile($detailUrl);
            $info['img'] = pq('#gifImage')->attr('data-src');

            $line = implode('|', $info) . "\n";
            fwrite($file, $line);
        }
        fclose($file);
    }

    protected function formatSrcId($srcBaseurl){
        $srcId = '';
        $pathInfo = explode('/', parse_url($srcBaseurl, PHP_URL_PATH));
        if(isset($pathInfo[2])){
            $srcId = "1717she-tv-{$pathInfo[2]}";
        }

        return $srcId;
    }
}