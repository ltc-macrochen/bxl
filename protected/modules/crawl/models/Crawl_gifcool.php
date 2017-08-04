<?php
/**
 * Created by PhpStorm.
 * User: macrochen
 * Date: 2017/6/19
 * Time: 11:05
 */

class Crawl_gifcool extends Crawl {
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
            self::$instance->config['fileName'] = 'vdata-gifcool-' . date('Ymd') . '.txt';
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

        $lastPageNum = 393;

        //循环爬取
        $count = 1;
        $continuePoint = true;
        for($i = 1; $i <= $lastPageNum; $i++){
            var_dump('page-------------------->' . $i);
            //段子
            //首页
            //热门
            $spiderUrl = "http://www.gifcool.com/gif/list_12_{$i}.html";
            $this->dataFormat($spiderUrl, $config['fileName'], $count, $continuePoint);
            //break;
            sleep(1);
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
        $ret = pq('.main .list > li');

        foreach ($ret->elements as $item) {
            //处理记录数
            var_dump($count);
            $count++;

            //断点继续
//            if($count == 191){
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

            $detailUrl = pq($item)->find('.title a')->attr('href');

            //todo ----> index
            $info['id'] = '';
            $gif = pq($item)->find('.con img')->attr('src');
            $info['img'] = $gif ? $gif : pq($item)->find('.con img')->attr('src');
            $info['title'] = pq($item)->find('.title a')->text();//
            $info['vGood'] = 0;
            $info['vBad'] = 0;
            $info['tags'] = '';
            $info['content'] = Utils::trimStr(pq($item)->find('.con td')->text());

            //图片URL转换
            $search = array('small', 'thumbnail', 'www.sinaimg.cn/dy/slidenews/77_t160/');
            $replace = array('bmiddle', 'mw1024', 'storage.slide.news.sina.com.cn/slidenews/77_ori/');
            $info['img'] = str_replace($search, $replace, $info['img']);

            //文案特殊字符处理
            $info['title'] = str_replace(array('"',"'"), '”', $info['title']);
            $info['content'] = str_replace(array('"',"'"), '”', $info['content']);
            $info['content'] = str_replace('play', '', $info['content']);

            //标签处理
            $tagItem = pq($item)->find('.tag a');
            $tagArr = array();
            foreach ($tagItem as $tag) {
                $tagArr[] = pq($tag)->text();
            }
            $info['tags'] = implode('-', $tagArr);

            //唯一源ID
            $detailUrlInfo = explode('/', $detailUrl);
            $info['id'] = 'gifcool-' . str_replace('.html', '', $detailUrlInfo[2]);

            //var_dump($info);continue;

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