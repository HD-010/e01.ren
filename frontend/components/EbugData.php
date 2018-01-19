<?php
namespace app\components;

use Yii;
use yii\base\InvalidParamException;
use app\models\User;
use Facebook\WebDriver\Cookie;


class EbugData
{
    // 事件属性
    public $properties = [];
    // 事件名称
    public $eventName;
    //浏览器对象
    public $browser;
    //用户成功登录
    public $loginSuccess;

    /**
     * 发送神策日志
     *
     * @param array $sensorsData
     *            形式如 : [
     *            'eventName' => 'ename', //事件名称
     *            'properties' => [], //事件属性
     *            'eventType'=>'PageView' //事件类型 如：PageView|UserAgent|WebClick
     *            ]
     * @param boolen $ceche
     *            是否缓存，如果缓存则等下次调用时发送数据 ,默认不缓存0
     *            使用场景：
     *            1.如果埋点只在服务端取数据或者只在客户端埋点取数据（异步传输） ，直接使用方法sensorLog($sensorsData)发送数据
     *            2.如果在服务端或客户端取获取不到完整的数据，可以先获取服务端数据并将其缓存sensorLog($sensorsData,1) 然后在客户端获取数据后通过异步传输，调用sensorLog($sensorsData)发送数据。
     */
    public static function log($sensorsData, $cache = 0)
    {
        $ebug = new EbugData();

        //初始化神策对象
        $ebug->m();

        // 将匿名用户和注册用户做关联
        $ebug->saSingUp();

        // 将日志缓存或直接发送
        if ($cache) {
            $ebug->setCacheData($sensorsData);
        } else {
            // 设置共公属性
            $ebug->setCommonProperties();

            // 设置该事件所属类型对应的事件属性
            $eventType = T::arrayValue('eventType',$sensorsData, "");
            if ($eventType) {
                $ebug->{'set' . $eventType . 'Properties'}();
            }

            // 设置更多（包括服务端或客户端或两者都有）属性
            $ebug->setMorProperties($sensorsData);

            // 合并所有已设置的属性
            $ebug->mergeProperties();

            $eventName = (array_key_exists('eventName', $sensorsData) && $sensorsData['eventName']) ? $sensorsData['eventName'] : $ebug->eventName;
            $ebug->eblog($eventName, $ebug->properties[0]); // 写入ssbiglogdata
        }
    }

    /**
     * 创建神对象
     */
    public function m()
    {
        define('EBUG_SERVER','http://data-analysis.e01.ren/?r=analysis/index/test');
        $logPath = Yii::getAlias(Yii::$app->params["ebugPath"]).'.'.date('Ymd',time());
        $consumer = new \app\components\ebug\FileConsumer($logPath);
        $this->ebug = new \app\components\ebug\EbugAnalysis($consumer);
    }

    /**
     * 共公预置属性，所有事件都会有的预置属性
     */
    public function setCommonProperties()
    {
        $this->properties[] = [
            '$ip' => T::IP(),
            '$screen_height' => '', // 屏幕高度
            '$screen_width' => '', // 屏幕宽度
            '$is_first_day' => '', // 是否首日访问
            '$latest_referrer_host' => '', // 最近一次站外域名
            '$latest_utm_source' => '', // 最近一次付费广告系列来源（只要有来源参数，就会重置）
            '$latest_utm_medium' => '', // 最近一次付费广告系列媒介
            '$latest_utm_term' => '', // 最近一次付费广告系列字词
            '$latest_utm_content' => '', // 最近一次付费广告系列内容
            '$latest_utm_campaign' => '', // 最近一次付费广告系列名称
            '$latest_search_keyword' => '', // 最近一次搜索引擎关键词
            '$latest_traffic_source_type' => '', // 最近一次流量来源类型
                                                 // -----------以下属性因在不同客户端获取有差异，在此手动获取---------------
            '$app_version' => '', // 应用的版本
            '$manufacturer' => '', // 设备制造商，例如Apple
            '$model' => '', // 设备型号，例如iphone6
            '$os' => $this->agent('Platform'), // 操作系统，例如iOS
            '$os_version' => $this->agent('AolVersion'), // 操作系统版本，例如8.1.1
            '$wifi' => '', // 是否使用wifi，例如true
            '$browser' => $this->agent('Browser'), // 浏览器名，例如Chrom
            '$browser_version' => $this->agent('Version'), // 浏览器版本，例如Chrome 45
            '$carrier' => '', // 运营商名称，例如ChinaNe
            '$network_type' => '', // 网络类型，例如4G
            '$utm_matching_type' => 'Cookie数据精准匹配', // iOS渠道追踪匹配模式，Cookie数据精准匹配/设备指纹模糊匹配
            '$referrer' => '',
        ];
    }

    /**
     * ============================在这里添加更多项预置属性===============================*
     */
    /**
     * $pageview（Web 浏览页面）事件的预置属性
     */
    public function setPageViewProperties()
    {
        $this->properties[] = [
            '$referrer_host' => '', // 前向域名
            '$url' => $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'], // 页面地址
            '$url_path' => $_SERVER['REQUEST_URI'], // 页面路径$url_path(有现用属性名)
            '$title' => '', // 页面标题 $title(有现用属性名)
            '$is_first_time' => '', // 是否首次访问
            '$utm_source' => '', // 广告系列来源
            '$utm_medium' => '', // 广告系列媒介
            '$utm_term' => '', // 广告系列字词
            '$utm_content' => '', // 广告系列内容
            '$utm_campaign' => ''
        ]; // 广告系列名称$utm_campaign(有现用属性名)

    }

    /**
     * $webclick(web点击事件)事件的预置属性
     */
    public function setWebClickProperties()
    {
        $this->properties[] = [
            '$url' => $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URL'], // 页面地址
            '$title' => '', // 页面标题
            '$url_path' => $_SERVER['REQUEST_URI'], // 页面路径
            '$element_id' => '', // 元素id
            '$element_content' => '', // 元素内容
            '$element_name' => '', // 元素名字
            '$element_class_name' => '', // 元素样式名
            '$element_type' => '', // 元素类型
            '$element_selector' => '', // 元素选择器
            '$element_target_url' => '',// 元素链接地址
        ];

    }

    /**
     * 获取用户资料
     *
     * @return array 返回用户资料
     */
    public function getProfiles()
    {
        $sex = [0 => '未知',1 => '男',2 => '女'];
        return [
            '$name' => Yii::$app->user->identity->username, // 用户名
            '$signup_time' => Yii::$app->user->identity->addtime, // 注册时间
            '$utm_source' => '', // 首次广告系列来源
            '$utm_medium' => '', // 首次广告系列媒介
            '$utm_term' => '', // 首次广告系列字词
            '$utm_content' => '', // 首次广告系列内容
            '$utm_campaign' => '',// 首次广告系列名称
        ];

    }

    /**
     * 获取用户首次访问的资料
     *
     * @return NULL[]|string[]
     */
    public function getProfilesOnce()
    {
        return [
            '$first_visit_time' => date('Y-m-d H:m:s', time()), // 首次访问时间
            '$first_referrer' => T::arrayValue('$first_referrer', $_COOKIE, ''), // 首次前向地址
            '$first_referrer_host' => T::arrayValue('$first_referrer_host', $_COOKIE, ''), // 首次前向域名
            '$first_browser_language' => T::arrayValue('$first_browser_language', $_COOKIE, ''), // 首次使用的浏览器语言
            '$first_browser_charset' => '', // 首次浏览器字符类型（1.8支持）
            '$first_search_keyword' => '', // 首次搜索引擎关键词（1.8支持）
            '$first_traffic_source_type' => ''// 首次流量来源类型（1.8支持）
        ] ;
    }

    /**
     * 返回所有合法字段
     * return array
     */
    public function getLegalField()
    {
        return [
            'name',
            'age'
        ];
    }

    /**
     * 设置属性的数据类型,默认为string
     */
    public function getRules()
    {
        return [
            [
                [
                    'name'
                ],
                'floatval',
            ],
            [
                [
                    '$' . 'is_first_time',
                    '$' . 'is_first_day',
                    'is_success',
                    '$' . 'wifi',
                ],
                'boolval',
            ],
            [
                [
                    '$' . 'screen_height',
                    '$' . 'screen_width',
                    'number',
                ],
                'intval',
            ],
        ];
    }

    /**
     * 设置客户端页面获取的预置属性 或 设置服务端获取的预置属性
     */
    public function setMorProperties($sensorsData = '')
    {
        $this->properties[] = T::arrayValue('properties',$sensorsData, "");
        $this->eventName = T::arrayValue('eventName',$sensorsData,"");
        $this->eventType = T::arrayValue('eventType',$sensorsData, "");
    }


    /**
     * 过滤空属性和非法字段并规范属性值的数据类型
     * 默认过滤空值的属性
     * param boolen $tranceNull
     */
    public function filterNull($proterties)
    {
        $temp = [];
        $legalFields = $this->getLegalField();  //合法字段

        foreach ($proterties as $k => $v) {
            if ($v === '' || $v === null){continue;}   //过滤空值
            if (! in_array($k, $legalFields) && (substr($k, 0, 1) != '$')){continue;} //过滤合法字段
            $attr = $this->checkProperties([$k,$v]);   //范属性值的数据类型
            $temp[$attr[0]] = $attr[1];
        }
        return $temp;
    }

    /**
     * 将属性值按规则转换其类型
     */
    public function checkProperties($property)
    {
        $rules = $this->getRules();
        // 设置属性的数据类型
        $attr = [];
        $attr[0] = $property[0];
        for ($i = 0; $i < count($rules); $i ++) {
            if (in_array($property[0], $rules[$i][0])) {
                $attr[1] = $rules[$i][1]($property[1]);
                return $attr;
            }
        }
        // 默认将值设为string
        $attr[1] = strval($property[1]);
        return $attr;
    }

    /**
     * 合并属性并过滤空属性
     */
    public function mergeProperties()
    {
        $properties = [];
        // 合并现有属性,和缓存属性
        $cacheProperties = $this->getCacheData();

        if (! empty($cacheProperties)) {
            $this->properties[] = $cacheProperties['properties'];
            $this->eventName = $cacheProperties['eventName'];
        }

        for ($i = 0; $i < count($this->properties); $i ++) {
            $this->properties[$i] = $this->filterNull($this->properties[$i]); // 过滤空属性和非法属性
            $properties = array_merge($properties, $this->properties[$i]);
        }
        $this->properties = [];
        $this->properties[] = $properties;
    }

    /**
     * 缓存服务端数据
     *
     * @param unknown $value
     */
    public function setCacheData($value)
    {
        $cache = Yii::$app->cache;
        $sensorsUTag = $this->getDistinctId();         //使用该用户标识
        $cache->set('sa_' . $sensorsUTag . $value['eventName'], $value);
        echo '<script> function getSensorsUTag(){return "'.$sensorsUTag.'"}</script>';
    }

    /**
     * 获取缓存数据（后将数据从缓存中删除）
     *
     * @param string $key
     */
    public function getCacheData()
    {
        $sensorsUTag = Yii::$app->request->post('sensorsUTag');
        $said = 'sa_' . $sensorsUTag . $this->eventName;
        $cache = Yii::$app->cache;
        $data = [];
        if ($cache->get($said)) {
            $data = $cache->get($said);
            $cache->delete($said);
        }
        return $data;
    }

    /**
     * 获取用户访问状态
     *
     * return 1表示匿名用户，0表示已经登录
     */
    public function getUserStates()
    {
        return (! is_object(Yii::$app->user->identity)) ? 1 : 0;
    }

    /**
     * 获取用户id
     *
     * return 客户端是控制台返回1。否则，返回用户id或匿名id
     */
    public function getUserId()
    {
        // 兼容控制台埋点（控制台没有catchAll）
        if (! property_exists(Yii::$app, 'catchAll')) {
            return 1;
        }
        if (! is_object(Yii::$app->user->identity)) {
            return $this->getDistinctId();
        }
        // 用户 id
        $userId = Yii::$app->user->identity->id;
        // 登录成功写cookie
        if (! isset($_COOKIE['bgtsyuid'])) {
            setcookie('bgtsyuid', $userId, time() * 1.1);
        }
        return $userId;
    }

    /**
     * 从cookie获取distinct_id
     *
     * return 匿名ID或 生成的匿名ID(避免无值程序报错)
     */
    public function getDistinctId()
    {
        $distinct_id = '';
        if (isset($_COOKIE['sensorsdata2015jssdkcross'])) {
            $sensorsdata = \GuzzleHttp\json_decode($_COOKIE['sensorsdata2015jssdkcross']);
            $distinct_id = $sensorsdata->distinct_id;
        }
        return $distinct_id ? $distinct_id : $this->createDistinct_id();
    }

    /**
     * 事件追踪日志
     *
     * @param unknown $event
     * @param unknown $properties
     */
    public function eblog($event, $properties)
    {
        // 埋点中匿名ID以js SDK生成的为准，如果UID不存在则不写入日志
        if (! $this->getUserId()) {
            return;
        }
        $this->ebug->track($this->getUserId(), $event, $properties);
        $this->ebug->close();
    }

    /**
     * 用户注册或登录成功后将用户id与匿名id时行关联
     *
     * @param unknown $profile
     *            用户资料
     */
    public function saSingUp()
    {
        // 开启session
        $session = Yii::$app->session;
        ! $session->isActive or $session->open();

        // 用户登录成功后，作一次用户联系
        //if(!$this->loginSuccess) return;
        if (! is_object(Yii::$app->user->identity) || isset($session['signup_lock'])) {
            return;
        }
        $this->ebug->track_signup($this->getUserId(), $this->getDistinctId());
        $session['signup_lock'] = 1; // 禁止当前会话过程中重复关联用户id
        $this->saSetProfiles();//记录用户资料
    }

    /**
     * 记录用户属性
     */
    public function saSetProfiles()
    {
        $profiles = $this->filterNull($this->getProfiles()); // 过滤空属性
        $this->ebug->profile_set($this->getUserId(), $profiles);
        $profiles = $this->filterNull($this->getProfilesOnce()); // 过滤空属性
        $this->ebug->profile_set_once($this->getUserId(), $profiles);
    }

    /**
     * ***********************埋点数据采集助手******************************
     */

    /**
     * 获取客户端信息
     * return string 返回客户端信息
     */
    public function agent($name)
    {
        is_object($this->browser) or $this->browser = new Browser();
        return $this->browser->{'get' . $name}();
    }

    /**
     * 生成distinct_id
     *
     * @return string
     */
    public function createDistinct_id()
    {
        $chars = md5(time());
        $str = '';
        $delimiter = [13,28,37,45];
        for ($i = 0; $i < 60; $i ++) {
            $str .= in_array($i, $delimiter) ? '-' : $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $str;
    }


    /**
     * 获取卖家信誉
     * return string 返回卖家信誉值
     */
    public static function sellerIntegrity($sellerid)
    {
        $user = new User();
        $userInfo = $user->getUserinformationByID($sellerid);
        return $userInfo['sellercredit'];
    }

    /**
     * 获取交易类型
     *
     * @param string $id
     *            商品id
     * @return string 返回交易类型
     */
    public static function getTradeModel($goodsid)
    {
        $goodsname = $goodsid ? $goodsid : "全部交易类型";
        $tradeType = [
            1=>"手游帐号",
            2=>"手游帐号",
            20=>"手游帐号",
            3=>"首充号",
            9=>"首充号",
            13=>"首充号",
            5=>"游戏币",
            17=>"游戏币",
            19=>"游戏币",
            10=>"代充",
            11=>"代充",
            12=>"代充",
            14=>"道具",
            15=>"道具",
            16=>"道具",
        ];
        if(array_key_exists($goodsname, $tradeType)){
            return $tradeType[$goodsname];
        }
        return $goodsname;
    }

    /**
     * 获取客户端系统类型
     *
     * @param unknown $sysid
     */
    public static function getOs($sysid)
    {
        $system = [
            '全部手机系统',
            'IOS',
            'Android',
            '其他'
        ];
        return T::arrayValue($sysid, $system, "");
    }

    /**
     * 获取商品类型名称
     *
     * @param [type] $goodsid
     *            [description]
     * @return [type] [description]
     */
    public static function getGoodsName($goodsid)
    {
        $goodsname = "";
        switch ($goodsid) {
            case 0:
                $goodsname = "全部商品类型";
                break;
            case 1:
                $goodsname = "成品号";
                break;
            case 2:
                $goodsname = "开局号";
                break;
            case 3:
                $goodsname = "首充帐号";
                break;
            case 5:
                $goodsname = "金币";
                break;
            case 9:
                $goodsname = "首充号续充";
                break;
            case 10:
                $goodsname = "代充";
                break;
            case 11:
                $goodsname = "苹果代充";
                break;
            case 12:
                $goodsname = "安卓代充";
                break;
            case 13:
                $goodsname = "首充号";
                break;
            case 14:
                $goodsname = "道具";
                break;
            case 15:
                $goodsname = "材料";
                break;
            case 16:
                $goodsname = "装备";
                break;
            case 17:
                $goodsname = "游戏币";
                break;
            case 19:
                $goodsname = "钻石";
                break;
            case 20:
                $goodsname = "手游帐号";
                break;
            default:
                $goodsname = strval($goodsid);
                break;
        }
        return $goodsname;
    }
/**
 * *******************************end*******************************
 */
}