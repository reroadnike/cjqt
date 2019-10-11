<?php

defined('IN_IA') or exit('Access Denied');

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/logs.class.php');
include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');

include_once(IA_ROOT . '/addons/superdesk_shopv2/service/transmit/TransmitShopJdLogsService.class.php');


/**
 * 温馨提示：
（1）新增资质流程：登录京东慧采 > 我的慧采 > 账户中心 > 账户设置 > 增票资质，点击“添加增票资质”按钮 > 填写资质信息 > 上传加盖公章的有效证件扫描件。
（2）您填写完资质信息和上传资质证件以后，我们会在一个工作日内完成审核工作。
（3）注意有效增值税专用发票开票资质仅为一个。
（4）下单资质默认会选择1个已通过审核的资质，只可单选，未通过审核的资质不可选择。
（5）发票常见问题请您查看《增票资质帮助》。
 */

/**
 * 京东VOP SDK
 *
 * Class JDVIPOpenPlatformSDK
 */
class JDVIPOpenPlatformSDK
{
    public $host = "https://bizapi.jd.com";
    public $edition = "";

    public $grant_type = "access_token";
    public $scope = "";



    public $time_stamp = "";

//    public $client_id = 'FlCa4YWCw3m7XcrD2ki0';
//    public $client_secret = 'zWkZHHRc6hwJmCy0XRQq';

    // 企业内购
//    public $username = '中航物业VOP';
//    public $password = '123000';

    // 福利商城
//    public $username = '中航物业员工福利';
//    public $password = '123000';

    public $client_id = '';
    public $client_secret = '';

    public $username = '';
    public $password = '';


    public $access_token = "NIwoLn95BLs0ZdKLyawIiuFPM";

    public $timeout = 30;
    public $connecttimeout = 30;
    public $ssl_verifypeer = FALSE;
    public $useragent = 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2049.0 Safari/537.36';
    public $format = 'form';// form || json
    public $decode_json = true;
    public $debug = false;
    public $params_base = array();

    private $_logsModel;
    private $_redis;
    private $_transmitShopJdLogsService;

    public static $boundary = '';

    function __construct()
    {
        global $_W;

        $this->_logsModel    = new logsModel();
        $this->_transmitShopJdLogsService = new TransmitShopJdLogsService();
        $this->_redis        = new RedisUtil();
//        $this->host = "";
//        $this->edition = "";
//        $this->client_id = '';
//        $this->client_secret = '';
//        $this->api_version = ;

        $this->time_stamp = date('Y-m-d H:i:s', time());//时间 yyyyMMddhhmmss
//        $this->params_base['IMEI']          = '0000';//设备串号（移动终端设备串号，默认0000）
//        $this->params_base['apiVersion']    = $this->api_version;//接口版本号（服务端向下版本兼容控制），默认值为1
//        $this->params_base['SIGN']          = md5($this->params_base['IMEI']."&".$this->client_id."&".$this->params_base['timeStamp']);

//        echo $this->params_base['IMEI']."&".$this->client_id."&".$this->params_base['timeStamp'];





        // 设定default时产生

        //从redis中拿参数..这些参数来自于数据库ims_superdesk_jd_vop_configs表..然后设置默认的时候存到了redis    zjh 2018年8月17日 14:22:06
        $key      = 'superdesk_jd_vop_' . 'web_jd_vop_configs_manage' . ':' . $_W['uniacid'];
        $api_conf = $this->_redis->get($key);

//        echo json_encode($api_conf);

//        socket_log("标记");
//        socket_log(json_encode(json_decode($api_conf),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));

        if (!empty($api_conf)) {
            $api_conf = json_decode($api_conf, true);

            $this->client_id     = $api_conf['client_id'];
            $this->client_secret = $api_conf['client_secret'];
            $this->username      = $api_conf['username'];
            $this->password      = $api_conf['password'];
        }

    }


    function init_access_token()
    {

        global $_W;

        /******************************************************* redis *********************************************************/

        $table__key = 'superdesk_jd_vop_' . 'sdk_access_token' . ':' . $_W['uniacid'];
        $colunm_key = 'jd_vop_access_token';

        //microtime_format('Y-m-d H:i:s.x',microtime())


        $result = $this->_redis->hget($table__key, $colunm_key);

        $result = json_decode($result, true);

//        echo json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

        if (empty($result) || empty($result['result'])) {
            $result = $this->access_token();
            $this->_redis->hset($table__key, $colunm_key, $result);
            $result = json_decode($result, true);
        }

        if (intval(floatval($result['result']['time']) / 1000) + intval($result['result']['expires_in']) <= time()) {

            $result = $this->refresh_token($result['result']['refresh_token']);
            $this->_redis->hset($table__key, $colunm_key, $result);

            // BUG 当要去刷时 $result 变回字符串

            $result = json_decode($result, true);
        }

        $this->access_token = $result['result']['access_token'];

//        echo $this->access_token;
//        echo "<br/>";
//        echo date('Y-m-d H:i:s', floatval($result['result']['time']) / 1000);
//        echo "<br/>";
//        echo "<br/>";

        /******************************************************* redis *********************************************************/
    }

    /**
     * 签名,生成规则如下：
     * 1. 按照以下顺序将字符串拼接起来
     *  client_secret+timestamp+client_id+username+password+grant_type+scope+client_secret
     *  其中
     *  client_secret的值是京东分配的
     *  username使用原文
     *  password需要md5加密后的
     * 2. 将上述拼接的字符串使用MD5加密，加密后的值再转为大写
     */
    function create_sign()
    {

        $sign_str =
            $this->client_secret .
            $this->time_stamp . $this->client_id . $this->username . md5($this->password) . $this->grant_type . $this->scope .
            $this->client_secret;

        $sign_md5    = md5($sign_str);
        $sign_result = strtoupper($sign_md5);

        return $sign_result;

    }

    /**
     * TODO 已测 1.3  获取Access Token
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function access_token_url()
    {
        return $this->host . $this->edition . "/oauth2/accessToken";
    }

    function access_token()
    {

        $params                  = array();
        $params['grant_type']    = $this->grant_type;//该值固定为access_token
        $params['client_id']     = $this->client_id;
        $params['client_secret'] = $this->client_secret;
        $params['timestamp']     = $this->time_stamp;
        $params['username']      = $this->username;
        $params['password']      = md5($this->password);
        $params['scope']         = $this->scope;//非必须 申请权限。（目前推荐为空。为以后扩展用）
        $params['sign']          = $this->create_sign();

        $response = $this->post($this->access_token_url(), $params, true);

//        {
//            "success": true,
//            "resultMessage": "",
//            "resultCode": "0000",
//            "result": {
//                "uid": "6165718786",
//                "refresh_token_expires": 1525071742348,
//                "time": 1509346942348,
//                "expires_in": 86400,
//                "refresh_token": "A23k88ryXJx9QJBay7QEmdPcM0GoJk8uidR2pIjy",
//                "access_token": "NIwoLn95BLs0ZdKLyawIiuFPM"
//            }
//        }
        return $response;

    }


    /**
     * TODO 已测 1.4  使用Refresh Token 刷新 Access Token
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function refresh_token_url()
    {
        return $this->host . $this->edition . "/oauth2/refreshToken";
    }

    function refresh_token($refresh_token)
    {

        $params                  = array();
        $params['refresh_token'] = $refresh_token;
        $params['client_id']     = $this->client_id;
        $params['client_secret'] = $this->client_secret;


        $response = $this->post($this->refresh_token_url(), $params, true);

//        {
//            "success": true,
//            "resultMessage": "",
//            "resultCode": "0000",
//            "result": {
//                "uid": "6165718786",
//                "refresh_token_expires": 1525077000000,
//                "time": 1509352200000,
//                "expires_in": 86400,
//                "refresh_token": "lhC8Btoft3GovRWad71v7b2RZ0ZQ45wQWjNmn46j",
//                "access_token": "yEZ3Ut9m1Np4XAGm8Qaq0f9s2"
//            }
//        }
        return $response;
    }



    /******************************************** 3、 商品API接口 start ********************************************/


    /**
     * TODO 已测 3.1  获取商品池编号接口
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_product_get_page_num_url()
    {
        return $this->host . $this->edition . "/api/product/getPageNum";
    }

    function api_product_get_page_num()
    {

        $params          = array();
        $params['token'] = $this->access_token;


        $response = $this->post($this->api_product_get_page_num_url(), $params, true);

        // 参见:3.1  获取商品池编号接口.json

        return $response;
    }

    /**
     * TODO 已测 3.2  获取池内商品编号接口
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_product_get_sku_url()
    {
        return $this->host . $this->edition . "/api/product/getSku";
    }

    function api_product_get_sku($pageNum)
    {

        $params            = array();
        $params['token']   = $this->access_token;  //授权时获取的access token
        $params['pageNum'] = $pageNum;             //池子编号

        $response = $this->post($this->api_product_get_sku_url(), $params, true);

        // 参见:3.2  获取池内商品编号接口.json

        return $response;
    }

    /**
     * TODO 已测 3.3  获取池内商品编号接口-品类商品池（兼容老接口）
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_product_get_sku_by_page_url()
    {
        return $this->host . $this->edition . "/api/product/getSkuByPage";
    }

    function api_product_get_sku_by_page($pageNum, $pageNo)
    {

        $params            = array();
        $params['token']   = $this->access_token;//授权时获取的access token
        $params['pageNum'] = $pageNum;//池子编号
        $params['pageNo']  = $pageNo;//页码，默认取第一页；每页最多10000条数据，品类商品池可能存在多页数据，具体根据返回的页总数判断是否有下一页数据

        $response = $this->post($this->api_product_get_sku_by_page_url(), $params, true);

        // 参见:3.3  获取池内商品编号接口-品类商品池（兼容老接口）.json

        return $response;
    }

    /**
     * TODO 已测 3.4  获取商品详细信息接口
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_product_get_detail_url()
    {
        return $this->host . $this->edition . "/api/product/getDetail";
    }

    function api_product_get_detail($sku,
                                    $isShow = false, /* 非必须 */
                                    $queryExts = ['appintroduce', 'shouhou', 'taxCode']/* 非必须 */)
    {



        $params           = array();
        $params['token']  = $this->access_token;//授权时获取的access token
        $params['sku']    = $sku;//必须商品编号，只支持单个查询
        $params['isShow'] = $isShow;//false:查询商品基本信息；true:商品基本信息 + 商品售后信息 + 移动商品详情介绍信息
        // update 20180919 该值废弃，老用用户默认="appintroduce,shouhou"

//        appintroduce,shouhou, taxCode,LowestBuy,capacity
//        可选扩展参数，支持单个/多个查询[逗号间隔]：
//        appintroduce | 移动商品详情介绍信息
//        shouhou | 商品售后信息
//        isFactoryShip | 是否厂商直送
//        isEnergySaving | 是否政府节能
//        contractSkuExt | 定制商品池开关
//        ChinaCatalog | 中图法分类号

//        $queryExts = [
//            'appintroduce', // 移动商品详情介绍信息
//            'shouhou', // 商品售后信息
//            'taxCode', // 税务编码
//            'isFactoryShip', // 是否厂商直送
//            'isEnergySaving', // 是否政府节能
//            'contractSkuExt',// 定制商品池开关
//            'ChinaCatalog', // 中图法分类号
//        ];


        if(is_array($queryExts) && sizeof($queryExts) > 0){
            $params['queryExts'] = implode(',',$queryExts);
        }

//        var_dump($params);


        $response = $this->post($this->api_product_get_detail_url(), $params, true);

        /**
         * 返回结果会根据skuid位数而不同。
         * sku小于8位会返回其他sku的格式，
         * sku为8位时，返回图书或者是音像的详情结果。
         **/

        // 参见:3.4  获取商品详细信息接口_sku_lt_8.json
        // 参见:3.4  获取商品详细信息接口_sku_eq_8.json

        // 参见:3.4  获取商品详细信息接口_音像商品详细样例数据.json
        // 参见:3.4  获取商品详细信息接口_图书商品详细样例数据.json
        // 参见:3.4  获取商品详细信息接口_其他实物详细样例数据.json

//注意事项
//其中：
//        param 返回的是 规格参数
//        Introduction 详细介绍
//提供了demo页面
//分别是 规格参数.html,详细介绍.html,样式在demo页面中
//imagePath 为商品的主图片地址。需要在前面添加http://img13.360buyimg.com/n0/
//其中n0(最大图)、n1(350*350px)、n2(160*160px)、n3(130*130px)、n4(100*100px) 为图片大小。
//例如接口返回imagePath为g8/M03/0E/06/rBEHaFCg5wQIAAAAAAGhG73oiLUAACxswH4MBwAAaEz619.jpg拼接后的url为：http://img13.360buyimg.com/n0/g8/M03/0E/06/rBEHaFCg5wQIAAAAAAGhG73oiLUAACxswH4MBwAAaEz619.jpg
//
//移动商品详情查询举例
//请求：https://bizapi.jd.com/api/product/getDetail?token=yourToken&sku=569200&isShow=true
//返回请见文件：

        return $response;
    }

    /**
     * TODO 已测 3.5  商品上下架状态接口
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_product_sku_state_url()
    {
        return $this->host . $this->edition . "/api/product/skuState";
    }

    function api_product_sku_state($sku)
    {

        $params          = array();
        $params['token'] = $this->access_token; //授权时获取的access token
        $params['sku']   = $sku;//商品编号，支持批量，以，分隔  (最高支持100个商品)1为上架，0为下架

        $response = $this->post($this->api_product_sku_state_url(), $params, true);

        // 参见:3.5  获取商品上下架状态接口.json

        return $response;
    }

    /**
     * TODO 已测 3.6  获取所有图片信息
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_product_sku_image_url()
    {
        return $this->host . $this->edition . "/api/product/skuImage";
    }

    function api_product_sku_image($sku)
    {

        $params          = array();
        $params['token'] = $this->access_token;//授权时获取的access token
        $params['sku']   = $sku;//商品编号，支持批量，以，分隔  (最高支持100个商品)

        $response = $this->post($this->api_product_sku_image_url(), $params, true);

        // 参见:3.6  获取所有图片信息.json

        return $response;
    }

    /**
     * TODO 已测 3.7  商品好评度查询
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_product_get_comment_summarys_url()
    {
        return $this->host . $this->edition . "/api/product/getCommentSummarys";
    }

    function api_product_get_comment_summarys($sku)
    {

        $params          = array();
        $params['token'] = $this->access_token;//授权时获取的access token
        $params['sku']   = $sku;//商品编号，支持批量，以，分隔  (最高支持50个商品)

        $response = $this->post($this->api_product_get_comment_summarys_url(), $params, true);

        // 参见:3.7  商品好评度查询.json

//参数名称 | 意义 
//averageScore | 商品评分 (5颗星，4颗星)
//goodRate | 好评度
//generalRate | 中评度
//poorRate | 差评度


        return $response;
    }

    /**
     * TODO 待测 3.8  商品区域购买限制查询
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_product_check_area_limit_url()
    {
        return $this->host . $this->edition . "/api/product/checkAreaLimit";
    }

    function api_product_check_area_limit($skuIds, $province, $city, $county, $town)
    {

        $params             = array();
        $params['token']    = $this->access_token;// 必须 |
        $params['sku']      = $skuIds;//必须 | 商品编号，支持批量，以，分隔  (最高支持50个商品)
        $params['province'] = $province;//必须 | 京东一级地址编号
        $params['city']     = $city;//必须 | 京东二级地址编号
        $params['county']   = $county;//必须 | 京东三级地址编号
        $params['town']     = $town;//必须 | 京东四级地址编号

        $response = $this->post($this->api_product_check_area_limit_url(), $params, true);

        // 参见:3.8  商品区域购买限制查询.json

        return $response;
    }

    /**
     * TODO 待测 3.9  商品区域是否支持货到付款
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_product_get_is_cod_url()
    {
        return $this->host . $this->edition . "/api/product/getIsCod";
    }

    function api_product_get_is_cod($skuIds, $province, $city, $county, $town = 0)
    {

//参数名称 | 参数选项  | 意义 
//token | 必须 |  授权时获取的access token
//skuIds | 必须 | 商品编号，支持批量，以’,’分隔  (最高支持100个商品)
//province | 必须 | 京东一级地址编号
//city | 必须 | 京东二级地址编号
//county | 必须 | 京东三级地址编号
//town | 必须 | 京东四级地址编号，存在四级地址必填，若不存在四级地址，则填0


        $params             = array();
        $params['token']    = $this->access_token;
        $params['skuIds']   = $skuIds;
        $params['province'] = $province;
        $params['city']     = $city;
        $params['county']   = $county;
        $params['town']     = $town;

        $response = $this->post($this->api_product_get_is_cod_url(), $params, true);

//参数名称 | 意义 
//success | 接口调用是否成功：true:成功；false:失败
//resultMessage | 若检查失败，则该字段会显示失败原因
//resultCode | 错误码：
//                PARAM_NOT_NULL：参数不能为空
//                PARAM_VALUE_ERROR：入参非法(sku不在商品池、查询sku数量超过指定数量50个、格式有误等)
//                BIG_NOT_COD：大家电商品不支持货到付款
//                SKU_HUODAOFUKUAN_UNSUPPORT：商品不支持货到付款（如奢侈品商品、厂家直送商品）
//                NOT_COD_ORDER：地址不支持货到付款
//                EXCEPTION:其他异常错误码
//result | 若验证所有商品都支持货到付款，则返回true;除此之外返回false

        // 参见:3.9  商品区域是否支持货到付款.json

        return $response;
    }

    /**
     * TODO 待测 3.10  查询赠品信息接口
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_product_get_sku_gift_url()
    {
        return $this->host . $this->edition . "/api/product/getSkuGift";
    }

    function api_product_get_sku_gift($skuId, $province, $city, $county, $town = 0)
    {


//参数名称 | 参数选项 | 意义 
//token |  必须 |  授权时获取的access token
//skuId | 必须 | 商品编号，只支持单个查询
//province | 必须 | 京东一级地址编号
//city | 必须 | 京东二级地址编号
//county | 必须 | 京东三级地址编号
//town | 必须 | 京东四级地址编号，存在四级地址必填，若不存在，则填0

        $params             = array();
        $params['token']    = $this->access_token;
        $params['skuId']    = $skuId;
        $params['province'] = $province;
        $params['city']     = $city;
        $params['county']   = $county;
        $params['town']     = $town;

        $response = $this->post($this->api_product_get_sku_gift_url(), $params, true);

        // 参见:3.10  查询赠品信息接口.json

        return $response;
    }

    /**
     * TODO 待测 3.11  运费查询接口
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_order_get_freight_url()
    {
        return $this->host . $this->edition . "/api/order/getFreight";
    }

    function api_order_get_freight($sku, $province, $city, $county, $town, $paymentType)
    {


//参数名称 | 类型 | 参数选项  | 描述 
//token | String | 必须 | 授权时获取的access token
//sku | String | 必须 | [{“skuId”:商品编号1,”num”:商品数量1},{“skuId”:商品编号2,”num”:商品数量2}]（最多支持50种商品）
//province | int | 必须 | 一级地址
//city | int | 必须 | 二级地址
//county | int | 必须 | 三级地址
//town | int | 非必须 | 四级地址  (如果该地区有四级地址，则必须传递四级地址，没有四级地址则传0)
//paymentType | int | Y | 京东支付方式

        $params                = array();
        $params['token']       = $this->access_token;
        $params['sku']         = $sku;
        $params['province']    = $province;
        $params['city']        = $city;
        $params['county']      = $county;
        $params['town']        = $town;
        $params['paymentType'] = $paymentType;


        $response = $this->post($this->api_order_get_freight_url(), $params, true);

//参数名称 | 意义 
//freight | 总运费
//baseFreight | 基础运费
//remoteRegionFreight | 偏远运费
//remoteSku | 需收取偏远运费的sku

        // 参见:3.11  运费查询接口.json

        return $response;
    }


    /**
     * TODO 待测 3.12  商品搜索接口
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_search_search_url()
    {
        return $this->host . $this->edition . "/api/search/search";
    }

    function api_search_search($keyword, $catId = 0, $pageIndex = 1, $pageSize = 20, $min = 0, $max = 0, $brands = '')
    {

//字段名称 | 选项 | 类型 | 说明
//token | 必须 | String | 授权时获取的access token
//keyword | 关键字搜索 | String | 搜索关键字，需要编码
//catId | 分类Id搜索 | String | 分类Id,只支持三级类目Id
//pageIndex | 分页 | Integer | 当前第几页
//pageSize | 每页大小 | Integer | 当前页显示
//min | 价格区间，低价 | String | 价格区间搜索，低价
//max | 价格区间，高价 | String | 价格区间搜索，高价
//brands | 品牌 | String | 品牌搜索 多个品牌以逗号分隔，需要编码


//请求参数示例：
//以Post形式提交
//1.关键字搜索
//url : https://bizapi.jd.com/api/search/search?token=xxx&keyword=iphone
//2. 分类Id搜索
//url : https://bizapi.jd.com/api/search/search?token=xxx&catId=898873
//3. 品牌搜索
//url : https://bizapi.jd.com/api/search/search?token=xxx&brands=华为,苹果
//4.带价格的组合搜索(价格区间，低价和高价为必须)
//url: https://bizapi.jd.com/api/search/search?token=xxx&catId=898873&keyword= iphone&min=0&max=10000

        $params              = array();
        $params['token']     = $this->access_token;
        $params['keyword']   = $keyword;//keyword | 关键字搜索 | String | 搜索关键字，需要编码

        if(!empty($catId)){
            $params['catId']     = $catId;//catId | 分类Id搜索 | String | 分类Id,只支持三级类目Id
        }


        $params['pageIndex'] = $pageIndex;//pageIndex | 分页 | Integer | 当前第几页
        $params['pageSize']  = $pageSize;//pageSize | 每页大小 | Integer | 当前页显示


        if(!empty($min)){
            $params['min']       = $min;//min | 价格区间，低价 | String | 价格区间搜索，低价
        }

        if(!empty($max)){
            $params['max']       = $max;//max | 价格区间，高价 | String | 价格区间搜索，高价
        }

        if(!empty($brands)){
            $params['brands']    = $brands;//brands | 品牌 | String | 品牌搜索 多个品牌以逗号分隔，需要编码
        }


        $response = $this->post($this->api_search_search_url(), $params, true);

//参数名称 | 说明
//resultCount | 搜索结果总记录数量
//pageCount | 总页数
//pageSize | 每页大小
//pageIndex | 当前页
//brandAggregate | 品牌汇总信息
//categoryAggregate | 相关分类汇总信息
//hitResult | 搜索命中数据json字符串，返回的图片地址拼接规则与查询商品详情规则一致

        // 参见:3.12  商品搜索接口.json

        return $response;
    }

    /**
     * TODO 已测 3.13  商品可售验证接口
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_product_check_url()
    {
        return $this->host . $this->edition . "/api/product/check";
    }

    function api_product_check($skuIds)
    {

//参数名称 | 参数选项  | 意义 
//token |  必须 |  授权时获取的access token
//skuIds | 必须 | 商品编号，支持批量，以，分隔  (最高支持100个商品)

//请求示例
//Request URL:http://bizapi.jd.com/api/product/check
//Request Method:POST
//Content-Type:application/x-www-form-urlencoded; charset=UTF-8
//FormData:token=x97MYgnLWYeFnKTyhgxeDgreg&skuIds=102194,102193

        $params           = array();
        $params['token']  = $this->access_token;
        $params['skuIds'] = $skuIds;


        $response = $this->post($this->api_product_check_url(), $params, true);

//参数名称 | 参数类型  | 意义 
//skuId |  Long | 商品编号
//name | String | 商品名称
//saleState | Int |  是否可售，1：是，0：否
//isCanVAT | Int |  是否可开增票，1：支持，0：不支持
//is7ToReturn | Int |  是否支持7天无理由退货，1：是，0：否

        // 参见:3.13  商品可售验证接口.json

        return $response;
    }

    /**
     * TODO 待测 3.14  查询商品延保接口
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_product_get_yanbao_sku_url()
    {
        return $this->host . $this->edition . "/api/product/getYanbaoSku";
    }

    function api_product_get_yanbao_sku($skuIds, $province, $city, $county, $town)
    {

//参数名称 | 类型 | 参数选项  | 描述 
//token | String | 必须 | 授权时获取的access token
//skuIds | String | 必须 | 多个用英文逗号隔开，如405075,405079,405099（最多支持50种商品）
//province | int | 必须 | 一级地址
//city | int | 必须 | 二级地址
//county | int | 必须 | 三级地址
//town | int | 非必须 | 四级地址  (如果该地区有四级地址，则必须传递四级地址，没有四级地址则传0)

//        请求样例：http://bizapi.jd.com/api/product/getYanbaoSku?token=myToken&skuIds=2317745&province=1&city=0&county=0&town=0

        $params             = array();
        $params['token']    = $this->access_token;
        $params['skuIds']   = $skuIds;//skuIds | String | 必须 | 多个用英文逗号隔开，如405075,405079,405099（最多支持50种商品）
        $params['province'] = $province;//province | int | 必须 | 一级地址
        $params['city']     = $city;//city | int | 必须 | 二级地址
        $params['county']   = $county;//county | int | 必须 | 三级地址
        $params['town']     = $town;//town | int | 非必须 | 四级地址  (如果该地区有四级地址，则必须传递四级地址，没有四级地址则传0)

        $response = $this->post($this->api_product_get_yanbao_sku_url(), $params, true);

//参数名称 | 类型 | 意义 
//resultCode | String | 响应状态码
//resultMessage | String | resultCode的说明
//result | Map<Long, List<YanBaoVo>>  | 具体的延保信息 Key: 主商品的sku Value:该商品可售的延保商品


//YanBaoVo对象说明
//参数名称 | 类型 | 意义 
//mainSkuId | Long | 主商品的sku
//imgUrl | String | 保障服务类别显示图标url
//detailUrl | String | 保障服务类别静态页详情url
//displayNo | int | 保障服务类别显示排序
//categoryCode | String | 保障服务分类编码
//displayName | String | 保障服务类别名称
//fuwuSkuDetailList | List<YanBaoVoDeatil>  | 保障服务商品详情列表


//YanBaoVoDeatil对象说明：
//参数名称 | 类型 | 意义
//bindSkuId | Long | 保障服务skuId
//bindSkuName | String | 保障服务sku名称（6字内）
//sortIndex | Integer | 显示排序
//price | BigDecimal | 保障服务sku价格
//tip | String | 保障服务说明提示语（20字内）
//favor | Boolean | 是否是优惠保障服务（PC单品页、PC购物车会根据此标识是否展示优惠图标，优惠图标单品页提供）


        // 参见:3.14  查询商品延保接口.json

        return $response;
    }

    /**
     * TODO 待测 3.15  查询分类信息接口
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_product_get_category_url()
    {
        return $this->host . $this->edition . "/api/product/getCategory";
    }

    function api_product_get_category($cid)
    {

//参数名称 | 类型 | 参数选项  | 描述 
//token | String | 必须 | 授权时获取的access token
//cid | Long | 必须 | 分类id（可通过商品详情接口查询）

//        请求样例：http://bizapi.jd.com/api/product/getYanbaoSku?token=myToken&cid=670
        $params          = array();
        $params['token'] = $this->access_token;
        $params['cid']   = $cid;

        $response = $this->post($this->api_product_get_category_url(), $params, true);

//参数名称 | 类型 | 意义 
//resultCode | String | 响应状态码
//resultMessage | String | resultCode的说明
//result | CategoyVo | 分类信息VO，请见CategoryVo字段说明

//CategoryVo对象说明：
//参数名称 | 类型 | 意义
//catId | Integer | 分类ID
//parentId | Integer | 父分类ID
//name | String | 分类名称
//catClass | Integer | 0：一级分类；1：二级分类；2：三级分类；
//state | Integer | 1：有效；0：无效；

        // 参见:3.15  查询分类信息接口.json

        return $response;
    }

    /**
     * TODO 待测 3.16  查询分类列表信息接口
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_product_get_categorys_url()
    {
        return $this->host . $this->edition . "/api/product/getCategorys";
    }

    function api_product_get_categorys($pageNo, $pageSize, $parentId, $catClass)
    {

//参数名称 | 类型 | 参数选项  | 描述 
//token | String | 必须 | 授权时获取的access token
//pageNo | Integer | 必须 | 页号，从1开始；
//pageSize | Integer | 必须 | 页大小，最大值5000；
//parentId | Integer | 非必须 | 父ID
//catClass | Integer | 非必须 | 分类等级（0:一级； 1:二级；2：三级）

//        请求样例：http://bizapi.jd.com/api/product/getCategorys?token=myToken&pageNo=1&pageSize=4&parentId=670&catClass=1
        $params             = array();
        $params['token']    = $this->access_token;
        $params['pageNo']   = $pageNo;//pageNo | Integer | 必须 | 页号，从1开始；
        $params['pageSize'] = $pageSize;//pageSize | Integer | 必须 | 页大小，最大值5000；
        $params['parentId'] = $parentId;//parentId | Integer | 非必须 | 父ID
        $params['catClass'] = $catClass;//catClass | Integer | 非必须 | 分类等级（0:一级； 1:二级；2：三级）

        $response = $this->post($this->api_product_get_categorys_url(), $params, true);

//参数名称 | 类型 | 意义 
//resultCode | String | 响应状态码
//resultMessage | String | resultCode的说明
//result | Map<String, Object>  | key | value
//                                totalRows | 条目总数
//                                pageNo | 当前页号
//                                pageSize | 页大小
//                                categorys | List<CategoryVo>:分类列表信息，详见CategoryVo字段说明部分。

        // 参见:3.16  查询分类列表信息接口.json

        return $response;
    }

    /**
     * TODO 待测 3.17  同类商品查询
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_product_get_similar_sku_url()
    {
        return $this->host . $this->edition . "/api/product/getSimilarSku";
    }

    function api_product_get_similar_sku($skuId)
    {

//参数名称 | 参数选项  | 意义 
//token |  必须 |  授权时获取的access token
//skuId | 必须 | skuId

        $params          = array();
        $params['token'] = $this->access_token;
        $params['skuId'] = $skuId;

        $response = $this->post($this->api_product_get_similar_sku_url(), $params, true);

        // 参见:3.17  同类商品查询.json

        return $response;
    }



    /******************************************** 3、 商品API接口 end   ********************************************/


    /******************************************** 4、 地址api接口 start ********************************************/

    /**
     * TODO 已测 4.1  获取一级地址
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_area_get_province_url()
    {
        return $this->host . $this->edition . "/api/area/getProvince";
    }

    function api_area_get_province()
    {

        $params          = array();
        $params['token'] = $this->access_token;


        $response = $this->post($this->api_area_get_province_url(), $params, true);

        // 参见:4.1  获取一级地址.json

        return $response;
    }

    /**
     * TODO 已测 4.2  获取二级地址
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_area_get_city_url()
    {
        return $this->host . $this->edition . "/api/area/getCity";
    }

    function api_area_get_city($id)
    {

        $params          = array();
        $params['token'] = $this->access_token;
        $params['id']    = $id;//一级地址


        $response = $this->post($this->api_area_get_city_url(), $params, true);

        // 参见:4.2  获取二级地址.json

        return $response;
    }

    /**
     * TODO 已测 4.3  获取三级地址
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_area_get_county_url()
    {
        return $this->host . $this->edition . "/api/area/getCounty";
    }

    function api_area_get_county($id)
    {

        $params          = array();
        $params['token'] = $this->access_token;
        $params['id']    = $id;//二级地址

        $response = $this->post($this->api_area_get_county_url(), $params, true);

        // 参见:4.3  获取三级地址.json

        return $response;
    }

    /**
     * TODO 已测 4.4  获取四级地址
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_area_get_town_url()
    {
        return $this->host . $this->edition . "/api/area/getTown";
    }

    function api_area_get_town($id)
    {

        $params          = array();
        $params['token'] = $this->access_token;
        $params['id']    = $id;//三级地址

        $response = $this->post($this->api_area_get_town_url(), $params, true);

        // 参见:4.4  获取四级地址.json

        return $response;
    }

    /**
     * TODO 待测 4.5  验证四级地址是否正确
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_area_check_area_url()
    {
        return $this->host . $this->edition . "/api/area/checkArea";
    }

    function api_area_check_area($provinceId, $cityId, $countyId = 0, $townId = 0)
    {

        $params               = array();
        $params['token']      = $this->access_token;
        $params['provinceId'] = $provinceId;//一级地址
        $params['cityId']     = $cityId;//二级地址
        $params['countyId']   = $countyId;//三级地址，如果是空请传入0
        $params['townId']     = $townId;//四级地址，如果是空请传入0


        $response = $this->post($this->api_area_check_area_url(), $params, true);

        // 参见:4.5  验证四级地址是否正确.json

        return $response;
    }


    /******************************************** 4、 地址api接口 end   ********************************************/


    /******************************************** 5、 价格API接口 start ********************************************/


    /**
     * TODO 待测 5.1  批量查询京东价价格
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_price_get_jd_price_url()
    {
        return $this->host . $this->edition . "/api/price/getJdPrice";
    }

    function api_price_get_jd_price($sku)
    {

        $params          = array();
        $params['token'] = $this->access_token;//授权时获取的access token
        $params['sku']   = $sku;//商品编号，请以，分割。例如：J_129408,J_129409(最高支持100个商品)

        $response = $this->post($this->api_price_get_jd_price_url(), $params, true);

        // 参见:5.1  批量查询京东价价格.json

        return $response;
    }

    /**
     * TODO 待测 5.2  批量查询协议价价格
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_price_get_price_url()
    {
        return $this->host . $this->edition . "/api/price/getPrice";
    }

    function api_price_get_price($sku)
    {

//        $start = microtime(true);

        $params          = array();
        $params['token'] = $this->access_token;//授权时获取的access token
        $params['sku']   = $sku;//商品编号，请以，分割。例如：J_129408,J_129409(最高支持100个商品)

        $response = $this->post($this->api_price_get_price_url(), $params, true);

        // 参见:5.2  批量查询协议价价格.json

//        $end = microtime(true);
//        echo 'api_price_get_price 耗时'.round($end - $start,4).'秒';
//        echo '<br/>';

        return $response;
    }

    /**
     * TODO 待测 5.3  批量查询商品售卖价
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_price_get_sell_price_url()
    {
        return $this->host . $this->edition . "/api/price/getSellPrice";
    }

    function api_price_get_sell_price($sku)
    {

        $params          = array();
        $params['token'] = $this->access_token;//授权时获取的access token
        $params['sku']   = $sku;//商品编号，请以(英文逗号)分割。例如：129408,129409(最高支持100个商品)


        $response = $this->post($this->api_price_get_sell_price_url(), $params, true);

        // 参见:5.3  批量查询商品售卖价.json

        return $response;
    }


    /******************************************** 5、 价格API接口 end   ********************************************/


    /******************************************** 6、 库存API接口 start ********************************************/

    /**
     * TODO 待测 6.2  批量获取库存接口（建议订单详情页、下单使用）
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_stock_get_new_stock_by_id_url()
    {
        return $this->host . $this->edition . "/api/stock/getNewStockById";
    }

    function api_stock_get_new_stock_by_id($skuNums/*商品和数量  [{skuId: 569172,num:101}]*/, $area/*格式：1_0_0 (分别代表1、2、3级地址)*/)
    {

        $params            = array();
        $params['token']   = $this->access_token;
        $params['skuNums'] = $skuNums;//商品和数量  [{skuId: 569172,num:101}]
        $params['area']    = $area;//格式：1_0_0 (分别代表1、2、3级地址)


        $response = $this->post($this->api_stock_get_new_stock_by_id_url(), $params, true);

//参数名称 意义 
//areaId 配送地址id
//desc 描述
//skuId 商品编号
//stockStateId 库存状态编号 33,39,40,36,34
//StockStateDesc 库存状态描述
//33 有货 现货-下单立即发货
//39 有货 在途-正在内部配货，预计2~6天到达本仓库
//40 有货 可配货-下单后从有货仓库配货
//36 预订
//34 无货
//remainNum 剩余数量 -1未知；当库存小于5时展示真实库存数量


        // 参见:6.2  批量获取库存接口（建议订单详情页、下单使用）.json

        return $response;
    }

    /**
     * TODO 待测 6.3  批量获取库存接口(建议商品列表页使用)
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_stock_get_stock_by_id_url()
    {
        return $this->host . $this->edition . "/api/stock/getStockById";
    }

    function api_stock_get_stock_by_id($sku, $area)
    {

        $params          = array();
        $params['token'] = $this->access_token;
        $params['sku']   = $sku;//商品编号 批量以逗号分隔  (最高支持100个商品)
        $params['area']  = $area;//格式：1_0_0 (分别代表1、2、3级地址)

        $response = $this->post($this->api_stock_get_stock_by_id_url(), $params, true);

        // 参见:6.3  批量获取库存接口(建议商品列表页使用).json

//参数名称 意义 
//area 地址
//desc 描述
//sku 商品编号
//state
//33 有货 现货-下单立即发货
//39 有货 在途-正在内部配货，预计2~6天到达本仓库
//40 有货 可配货-下单后从有货仓库配货
//36 预订
//34 无货


        return $response;
    }

    /**
     * TODO 待测 6.4  批量获取库存接口（买卖宝使用，以5为阈值）
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_stock_get_five_stock_by_id_url()
    {
        return $this->host . $this->edition . "/api/stock/getFiveStockById";
    }

    function api_stock_get_five_stock_by_id($skuNums, $area)
    {

        $params            = array();
        $params['token']   = $this->access_token;
        $params['skuNums'] = $skuNums;//商品和数量  [{skuId: 569172,num:101}]
        $params['area']    = $area;//格式：1_0_0 (分别代表1、2、3级地址)

        $response = $this->post($this->api_stock_get_five_stock_by_id_url(), $params, true);

        // 参见:6.4  批量获取库存接口（买卖宝使用，以5为阈值）.json
// 参数名称 意义 
//areaId 配送地址id
//desc 描述
//skuId 商品编号
//stockStateId 库存状态编号 33,39,40,36,34
//StockStateDesc 库存状态描述
//33 有货 现货-下单立即发货
//39 有货 在途-正在内部配货，预计2~6天到达本仓库
//40 有货 可配货-下单后从有货仓库配货
//36 预订
//34 无货
//remainNum 剩余数量 -1未知；当库存小于5时展示真实库存数量

        return $response;
    }


    /******************************************** 6、 库存API接口 end   ********************************************/


    /******************************************** 7、 订单API接口 start ********************************************/

    /**
     * 注意:下单接口调用前，建议先调用实时价格查询接口，判断价格是否发生变化，如变化提示客户价格变化，并刷新页面；价格无变化才调用下单接口下单；
     * 外部客户对接vop下单的流程，如下：
     * 1、客户调用下单接口，我们接口会明确返回下单成功或失败，见success字段
     * 2、客户下单使用三方订单号应该与京东订单号一一对应，下单失败，不能修改三方订单号重新下单（因为大客户系统会使用三方订单号进行防重处理）；
     * 3、如果客户下单失败，下单接口"success"会返回false。resultMessage会返回失败原因。客户可根据失败原因调整下单参数后，使用同一三方订单号，重新下单；
     * 4、下单失败有一种特殊情况是“重复下单”，会返回resultCode为"0008"。如果同一三方订单号已经存在有效订单，则视为重复下单，此时下单结果 result会返回该三方订单号对应订单信息，需要客户系统进行金额和商品、收货人等确认，如一致，可视为下单成功。
     * 5、下单成功后，可使用订单查询接口查询订单详细信息。
     * 6、如果客户调用下单接口，出现超时或其他异常，可稍微等待后，使用订单反查接口（https://bizapi.jd.com/api/order/selectJdOrderIdByThirdOrder）确认是否下单成功。
     * 7、支持礼品卡实物卡下单，但是只能下普票订单，不能跟非实物礼品卡混合下单。
     */

    /**
     * TODO 已测 7.1  统一下单接口
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_order_submit_order_url()
    {
        return $this->host . $this->edition . "/api/order/submitOrder";
    }

    function api_order_submit_order($params = array())
    {

        $params['token'] = $this->access_token;

        $response = $this->post($this->api_order_submit_order_url(), $params, true);

        // 参见:7.1  统一下单接口.json

        return $response;
    }

    /**
     * TODO 已测 7.2  确认预占库存订单接口
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_order_confirm_order_url()
    {
        return $this->host . $this->edition . "/api/order/confirmOrder";
    }

    function api_order_confirm_order($jdOrderId)
    {

        $params              = array();
        $params['token']     = $this->access_token;
        $params['jdOrderId'] = $jdOrderId;//京东的订单单号(调用1.1接口时返回的父订单号)


        $response = $this->post($this->api_order_confirm_order_url(), $params, true);

        // 参见:7.2  确认预占库存订单接口.json
//        注意：错误码3103，代表该订单已确认下单，不需要重复确认。


        return $response;
    }

    /**
     * TODO 已测 7.3  取消未确认订单接口
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_order_cancel_url()
    {
        return $this->host . $this->edition . "/api/order/cancel";
    }

    function api_order_cancel($jdOrderId)
    {

        $params              = array();
        $params['token']     = $this->access_token;
        $params['jdOrderId'] = $jdOrderId;//京东的订单单号(调用1.1接口时返回的父订单号)

        $response = $this->post($this->api_order_cancel_url(), $params, true);

        // 参见:7.3  取消未确认订单接口.json

//        注意事项
//该接口仅能取消未确认的预占库存父订单单号。不能取消已经确认的订单单号。
//如果需要取消已确认的订单，可以调用取消订单接口进行取消操作，参数必须为子订单才能取消 。

        return $response;
    }

    /**
     * TODO 待测 7.4  发起支付接口
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_order_do_pay_url()
    {
        return $this->host . $this->edition . "/api/order/doPay";
    }

    function api_order_do_pay($jdOrderId)
    {

        $params              = array();
        $params['token']     = $this->access_token;
        $params['jdOrderId'] = $jdOrderId;//京东系统订单号

        $response = $this->post($this->api_order_do_pay_url(), $params, true);

        // 参见:7.4  发起支付接口.json
        //如果
        //success 为true   则代表发起支付成功
        //success 为false  则代表因为某种原因发起支付失败了

        return $response;
    }

    /**
     * TODO 待测 7.5  获取京东预约日历
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_order_promise_calendar_url()
    {
        return $this->host . $this->edition . "/api/order/promiseCalendar";
    }

    function api_order_promise_calendar($province, $city, $county, $town, $paymentType, $sku)
    {

//参数名称 | 类型 | 参数选项 | 意义 
//province | int | 必须 | 一级地址
//city | int | 必须 | 二级地址
//county | int | 必须 | 三级地址
//town | int | 非必须 | 四级地址  (如果该地区有四级地址，则必须传递四级地址，没有四级地址则传0)
//paymentType | int | 必须 | 支付类型，合同上允许的支付类型都可以
//sku | String | 必须 | [{"skuId":商品编号, "num":商品数}] 商品编号：类型为long, 必需大于0; 商品数:类型int，必需大于0;

        $params                = array();
        $params['token']       = $this->access_token;
        $params['province']    = $province;
        $params['city']        = $city;
        $params['county']      = $county;
        $params['town']        = $town;
        $params['paymentType'] = $paymentType;
        $params['sku']         = $sku;

        $response = $this->post($this->api_order_promise_calendar_url(), $params, true);

        // 返回很复杂，请
        // 参见:7.5  获取京东预约日历.json

        return $response;
    }

    /**
     * TODO 待测 7.6  订单反查接口
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_order_select_jd_order_id_by_third_order_url()
    {
        return $this->host . $this->edition . "/api/order/selectJdOrderIdByThirdOrder";
    }

    function api_order_select_jd_order_id_by_third_order($thirdOrder)
    {

        $params               = array();
        $params['token']      = $this->access_token;
        $params['thirdOrder'] = $thirdOrder;//客户系统订单号


        $response = $this->post($this->api_order_select_jd_order_id_by_third_order_url(), $params, true);

        // 参见:7.6  订单反查接口.json

//如果
//success 为true   则代表下单成功，result值为京东的订单号
//success 为false  则代表京东订单号不存在，
//背景：由于下单反馈超时时，有可能已下单成功，也有可能下单失败，需要反查查看实际情况。
//使用场景：
//调用下单接口下单时，当反馈抄送时，需要调用反馈查接口查询订单实际处理情况
//1、 当反查接口反馈true时，关联申请单，无需再次掉下单接口下单
//2、 当反查接口反馈false时，需要重新调下单接口下单，并关联审批单。

        return $response;
    }

    /**
     * TODO 已测 7.7  查询京东订单信息接口
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_order_select_jd_order_url()
    {
        return $this->host . $this->edition . "/api/order/selectJdOrder";
    }

    function api_order_select_jd_order($jdOrderId)
    {

        $params              = array();
        $params['token']     = $this->access_token;
        $params['jdOrderId'] = $jdOrderId;// 必须 京东订单号


        $response = $this->post($this->api_order_select_jd_order_url(), $params, true);

//参数名称 | 参数选项 | 意义 
//jdOrderId  | | 京东订单编号
//state  | | 物流状态 0 是新建  1是妥投   2是拒收
//type  | | 订单类型   1是父订单   2是子订单
//orderPrice  | | 订单价格
//freight | 运费（合同配置了才返回）
//sku  | | 商品列表
//pOrder  | | 父订单号
//orderState  | | 订单状态  0为取消订单  1为有效
//submitState  | | 0为未确认下单订单   1为确认下单订单

        // 参见:7.7  查询京东订单信息接口.json

        return $response;
    }

    /**
     * TODO 已测 7.8  查询配送信息接口
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_order_order_track_url()
    {
        return $this->host . $this->edition . "/api/order/orderTrack";
    }

    function api_order_order_track($jdOrderId)
    {

        $params              = array();
        $params['token']     = $this->access_token;
        $params['jdOrderId'] = $jdOrderId;//京东订单号


        $response = $this->post($this->api_order_order_track_url(), $params, true);

        // 参见:7.8  查询配送信息接口.json

        return $response;
    }

    /**
     * TODO 已测 7.9  统一余额查询接口
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_price_get_balance_url()
    {
        return $this->host . $this->edition . "/api/price/getBalance";
    }

    function api_price_get_balance($payType)
    {

        $params            = array();
        $params['token']   = $this->access_token;
        $params['payType'] = $payType;//支付类型 4：余额 7：网银钱包 101：金采支付

        $response = $this->post($this->api_price_get_balance_url(), $params, true);

        // 参见:7.9  统一余额查询接口.json
//        Result即为账户余额

        return $response;
    }

    /**
     * TODO 待测 7.10  查询用户金彩余额接口（仅供金彩支付客户使用）
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_price_select_jincai_credit_url()
    {
        return $this->host . $this->edition . "/api/price/selectJincaiCredit";
    }

    function api_price_select_jincai_credit()
    {

        $params          = array();
        $params['token'] = $this->access_token;

        $response = $this->post($this->api_price_select_jincai_credit_url(), $params, true);

        // 参见:7.10  查询用户金彩余额接口（仅供金彩支付客户使用）.json

        return $response;
    }

    /**
     * TODO 待测 7.11  余额明细查询接口
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_price_get_balance_detail_url()
    {
        return $this->host . $this->edition . "/api/price/getBalanceDetail";
    }

    function api_price_get_balance_detail($pageNum = 1, $pageSize = 20, $orderId = '', $startDate = '', $endDate = '')
    {

        $params             = array();
        $params['token']    = $this->access_token;
        $params['pageNum']  = $pageNum;//int 非必填 分页查询当前页数，默认为1
        $params['pageSize'] = $pageSize;//int 非必填 每页记录数，默认为20

        if (!empty($orderId)) {
            $params['orderId'] = $orderId;//String 非必填订 单号, 例如：42747145688
        }
        if (!empty($startDate)) {
            $params['startDate'] = $startDate;//String 非必填 开始日期，格式必须：yyyyMMdd
        }
        if (!empty($endDate)) {
            $params['endDate'] = $endDate;//String 非必填 截止日期，格式必须：yyyyMMdd
        }


        $response = $this->post($this->api_price_get_balance_detail_url(), $params, true);

//        echo $response;

        // 参见:7.11  余额明细查询接口.json

//         参数名称 类型 意义 
//success | boolean | 接口是否正常响应;true表示正常处理请求
//resultCode | String | 业务处理结果编码，详细参见：【11.错误码】
//resultMessage | String | 对resultCode的简要说明
//result | JSON |获取到的结果,json字符串


//         参数名称 | 类型 | 意义 
//total | Int | 记录总条数
//pageSize | Int | 分页大小，默认20，最大1000
//pageNo | Int | 当前页码
//pageCount | Int | 总页数
//data：
//
//id | Long | 余额明细ID
//accountType | Int | 账户类型  1：可用余额 2：锁定余额
//amount | BigDecimal | 金额（元），有正负，可以是零，表示订单流程变化，如退款时会先有一条退款申请的记录，金额为0
//pin | String | 京东Pin
//orderId | String | 订单号
//tradeType | Int | 业务类型
//tradeTypeName | String | 业务类型名称
//createdDate | Date | 余额变动日期
//notePub | String | 备注信息
//tradeNo | Long | 业务号，一般由余额系统，在每一次操作成功后自动生成，也可以由前端业务系统传入

        return $response;
    }

    /******************************************** 7、 订单API接口 end   ********************************************/


    /******************************************** 8、 企销API对账接口 start ********************************************/

    /**
     * TODO 待测 8.1  新建订单查询接口
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_check_order_check_new_order_url()
    {
        return $this->host . $this->edition . "/api/checkOrder/checkNewOrder";
    }

    function api_check_order_check_new_order($date, $page)
    {

        $params          = array();
        $params['token'] = $this->access_token;
        $params['date']  = $date;//2013-11-7             （不包含当天）
        $params['page']  = $page;//1       当前页码


        $response = $this->post($this->api_check_order_check_new_order_url(), $params, true);

        // 参见:8.1  新建订单查询接口.json


//参数名称 | 描述 
//jdOrderId | 京东订单编号
//state | 订单状态 0 是新建  1是妥投   2是拒收
//hangUpState | 是否挂起   0为为挂起    1为挂起
//invoiceState | 开票方式(1为随货开票，0为订单预借，2为集中开票 )
//orderPrice | 订单金额
//total | 订单总数
//totalPage | 总页码数
//curPage | 当前页码


        return $response;
    }

    /**
     * TODO 待测 8.2  获取妥投订单接口
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_check_order_check_dlok_order_url()
    {
        return $this->host . $this->edition . "/api/checkOrder/checkDlokOrder";
    }

    function api_check_order_check_dlok_order($date, $page)
    {

        $params          = array();
        $params['token'] = $this->access_token;
        $params['date']  = $date;//2013-11-7             （不包含当天）
        $params['page']  = $page;//1       当前页码

        $response = $this->post($this->api_check_order_check_dlok_order_url(), $params, true);

        // 参见:8.2  获取妥投订单接口.json
//参数名称 | 描述 
//jdOrderId | 京东订单编号
//state | 订单状态 0 是新建  1是妥投   2是拒收
//hangUpState | 是否挂起   0为为挂起    1为挂起
//invoiceState | 开票方式(1为随货开票，0为订单预借，2为集中开票 )
//orderPrice | 订单金额
//total | 订单总数
//totalPage | 总页码数
//curPage | 当前页码
        return $response;
    }

    /**
     * TODO 待测 8.3  获取拒收消息接口
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_check_order_check_refuse_order_url()
    {
        return $this->host . $this->edition . "/api/checkOrder/checkRefuseOrder";
    }

    function api_check_order_check_refuse_order($date, $page)
    {

        $params          = array();
        $params['token'] = $this->access_token;
        $params['date']  = $date;//2013-11-7             （不包含当天）
        $params['page']  = $page;//1       当前页码

        $response = $this->post($this->api_check_order_check_refuse_order_url(), $params, true);

        // 参见:8.3  获取拒收消息接口.json
//参数名称 | 描述 
//jdOrderId | 京东订单编号
//state | 订单状态 0 是新建  1是妥投   2是拒收
//hangUpState | 是否挂起   0为为挂起    1为挂起
//invoiceState | 开票方式(1为随货开票，0为订单预借，2为集中开票 )
//orderPrice | 订单金额
//total | 订单总数
//totalPage | 总页码数
//curPage | 当前页码
        return $response;
    }

    /******************************************** 8、 企销API对账接口 end   ********************************************/


    /******************************************** 9、 信息推送api接口 start ********************************************/

    /**
     * TODO 待测 9.1  信息推送接口
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_message_get_url()
    {
        return $this->host . $this->edition . "/api/message/get";
    }

    function api_message_get($type)
    {

        $params          = array();
        $params['token'] = $this->access_token;
//        $params['del']    = $del;//非必须 是否删除标示，1为获取完后，立刻删除，0为获取后不删除注意：建议为0，然后每次获取到数据后，去调用del接口，将数据删除；在调用get接口获取下一波数据；不调用del接口进行删除，get接口返回的是上一次数据；[已废弃功能，请调用删除接口]
        $params['type'] = $type;//非必须 推送类型，支持多个组合，例如 1,2,3

        $response = $this->post($this->api_message_get_url(), $params, true);

        // 返回结果说明 处理比较多
        // 参见:9.1  信息推送接口.json

        return $response;
    }

    /**
     * TODO 待测 9.2  根据推送id，删除推送信息接口
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_message_del_url()
    {
        return $this->host . $this->edition . "/api/message/del";
    }

    function api_message_del($id)
    {

        $params          = array();
        $params['token'] = $this->access_token;
        $params['id']    = $id;//上一接口获取的id 9.1 的ID?

        $response = $this->post($this->api_message_del_url(), $params, true);

        // 参见:9.2  根据推送id，删除推送信息接口.json

        return $response;
    }

    /******************************************** 9、 信息推送api接口 end   ********************************************/


    /******************************************** 10、 售后相关接口 start ********************************************/

    /**
     * TODO 待测 10.1  服务单保存申请
     * HTTPS请求方式：POST
     * 接口依赖
     * 需要该配送单已经妥投。
     * 需要先调用10.3接口校验订单中某商品是否可以提交售后服务
     * 需要先调用10.4接口查询支持的服务类型
     * 需要先调用10.5接口查询支持的商品返回京东方式
     *
     * @return string
     */
    function api_after_sale_create_afs_apply_url()
    {
        return $this->host . $this->edition . "/api/afterSale/createAfsApply";
    }

    function api_after_sale_create_afs_apply()
    {

        $params          = array();
        $params['token'] = $this->access_token;


        $response = $this->post($this->api_after_sale_create_afs_apply_url(), $params, true);

        // 参见:10.1  服务单保存申请.json

        return $response;
    }

    /**
     * TODO 待测 10.2  填写客户发运信息
     * 接口依赖
     * 需要调用10.6 查询得到服务单号
     * 并且有需要补充客户发运信息时调用这个接口
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_after_sale_update_send_sku_url()
    {
        return $this->host . $this->edition . "/api/afterSale/updateSendSku";
    }

    function api_after_sale_update_send_sku($afsServiceId, $freightMoney, $expressCompany, $deliverDate, $expressCode)
    {

        $params                   = array();
        $params['token']          = $this->access_token;
        $params['afsServiceId']   = $afsServiceId;//服务单号 | Integer | 必需
        $params['freightMoney']   = $freightMoney;//运费 | BigDecimal | 必需
        $params['expressCompany'] = $expressCompany;//发运公司 | String | 必需， 圆通快递、申通快递、韵达快递、中通快递、宅急送、EMS、顺丰快递
        $params['deliverDate']    = $deliverDate;//发货日期 | String | 必需，格式为yyyy-MM-dd HH:mm:ss
        $params['expressCode']    = $expressCode;//货运单号 | String | 必需，不超过50

        $response = $this->post($this->api_after_sale_update_send_sku_url(), $params, true);

//参数名 | 含义 | 参数类型 | 其他
//success | 状态码 | boolean | true成功 false失败
//result | 组件列表 | List< T >  | 没有数据返回空
//resultMessage | 错误信息 | String |
//resultCode | 异常代码Key |  | 0无错误信息
//                                1001无效服务单号
//                                1002金额无效
//                                1003运单号为空
//                                1004发运公司为空
//                                1005发货日期为空
//                                1006平台来源为空


        // 参见:10.2  填写客户发运信息.json

        return $response;
    }

    /**
     * TODO 待测 10.3  校验某订单中某商品是否可以提交售后服务
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_after_sale_get_available_number_comp_url()
    {
        return $this->host . $this->edition . "/api/afterSale/getAvailableNumberComp";
    }

    function api_after_sale_get_available_number_comp($jdOrderId, $skuId)
    {

        $param              = array();
        $param['jdOrderId'] = $jdOrderId;//京东订单号 | Long | 必需
        $param['skuId']     = $skuId;//京东商品编号 | Long | 必需

        $params          = array();
        $params['token'] = $this->access_token;
        $params['param'] = $param;

        // 请求示例 token=CDFZW3TO3StKAgOFPSkmbodZ2&param={"jdOrderId":40245152920,"skuId":800032}

        $response = $this->post($this->api_after_sale_get_available_number_comp_url(), $params, true);

//        参数名 | 含义 | 参数类型 | 其他
//success | 状态码 | boolean | true可申请 false不可申请
//result | 组件列表 | int | 可申请时返回可申请数量
//resultMessage | 错误信息 | String |
//resultCode | 异常代码Key | int | 0无错误信息

        // 参见:10.3  校验某订单中某商品是否可以提交售后服务.json


        return $response;
    }

    /**
     * TODO 待测 10.4  根据订单号、商品编号查询支持的服务类型
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_after_sale_get_customer_expect_comp_url()
    {
        return $this->host . $this->edition . "/api/afterSale/getCustomerExpectComp";
    }

    function api_after_sale_get_customer_expect_comp($jdOrderId, $skuId)
    {


        $param              = array();
        $param['jdOrderId'] = $jdOrderId;//京东订单号 | Long | 必需
        $param['skuId']     = $skuId;//京东商品编号 | Long | 必需

        $params          = array();
        $params['token'] = $this->access_token;
        $params['param'] = $param;

        // 请求示例 {"jdOrderId":40245152920,"skuId":800032}

        $response = $this->post($this->api_after_sale_get_customer_expect_comp_url(), $params, true);

//        参数名 | 含义 | 参数类型 | 其他
//success | 状态码 | boolean | true成功 false失败
//result | 组件列表 | List< ComponentExport >  | 没有数据返回空
//resultMessage | 错误信息 | String |
//resultCode | 异常代码Key | int | 0无错误信息
//
//ComponentExport实体
//参数名 | 含义 | 参数类型 | 其他
//code | 服务类型码 | String | 退货(10)、换货(20)、维修(30)
//name | 服务类型名称 | String | 退货、换货、维修


        // 参见:10.4  根据订单号、商品编号查询支持的服务类型.json

        return $response;
    }

    /**
     * TODO 待测 10.5  根据订单号、商品编号查询支持的商品返回京东方式
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_after_sale_get_ware_return_jd_comp_url()
    {
        return $this->host . $this->edition . "/api/afterSale/getWareReturnJdComp";
    }

    function api_after_sale_get_ware_return_jd_comp($jdOrderId, $skuId)
    {

        $param              = array();
        $param['jdOrderId'] = $jdOrderId;//京东订单号 | Long | 必需
        $param['skuId']     = $skuId;//京东商品编号 | Long | 必需

        $params          = array();
        $params['token'] = $this->access_token;
        $params['param'] = $param;

        // 请求示例 {"jdOrderId":40245152920,"skuId":800032}

        $response = $this->post($this->api_after_sale_get_ware_return_jd_comp_url(), $params, true);
        //        参数名 | 含义 | 参数类型 | 其他
//success | 状态码 | boolean | true成功 false失败
//result | 组件列表 | List< ComponentExport >  | 没有数据返回空
//resultMessage | 错误信息 | String |
//resultCode | 异常代码Key | int | 0无错误信息
//
//ComponentExport实体
//参数名 | 含义 | 参数类型 | 其他
//code | 服务类型码 | String | 上门取件(4)、客户发货(40)、客户送货(7)
//name | 服务类型名称 | String | 上门取件、客户发货、客户送货

        // 参见:10.5  根据订单号、商品编号查询支持的商品返回京东方式.json

        return $response;
    }

    /**
     * TODO 待测 10.6  根据客户账号和订单号分页查询服务单概要信息
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_after_sale_get_service_list_page_url()
    {
        return $this->host . $this->edition . "/api/afterSale/getServiceListPage";
    }

    function api_after_sale_get_service_list_page($jdOrderId, $pageSize, $pageIndex)
    {

        $params              = array();
        $params['token']     = $this->access_token;
        $params['jdOrderId'] = $jdOrderId;//订单号 | long | 必需
        $params['pageSize']  = $pageSize;//每页记录数 | int | 必需
        $params['pageIndex'] = $pageIndex;//页码 | int | 必需，1代表第一页

        // 请求示例 {"jdOrderId":40215143944,"pageIndex":1,"pageSize":10}

        $response = $this->post($this->api_after_sale_get_service_list_page_url(), $params, true);

        // 参见:10.6  根据客户账号和订单号分页查询服务单概要信息.json

//ResultDTO实体
//参数名 | 含义 | 参数类型 | 其他
//success | 状态码 | booelan | true成功，false失败
//result | 服务单详情 | AfsServicebyCustomerPinPage | 没有数据返回空
//resultMessage | 异常消息提示 | String |
//resultCode | 异常代码Key |  | 0无错误信息
//                                1001服务单号为空
//                                1002 客户账号为空
//                                2000 查询出错
//
//
//AfsServicebyCustomerPinPage实体
//参数名 | 含义 | 参数类型 | 其他
//serviceInfoList | 组件列表 | list<AfsServicebyCustomerPin>  | 没有数据返回空
//totalNum | 总记录数 | Int |
//pageSize | 每页记录数 | Int |
//pageNum | 总页数 | Int |
//pageIndex | 当前页数 | Int |
//
//
//AfsServicebyCustomerPin实体
//参数名 | 含义 | 参数类型 | 其他
//afsServiceId | 服务单号 | Integer |
//customerExpect | 服务类型码 | Integer | 退货(10)、换货(20)、维修(30)
//customerExpectName | 服务类型名称 | String |
//afsApplyTime | 服务单申请时间 | String | 格式为yyyy-MM-dd HH:mm:ss
//orderId | 订单号 | Long |
//wareId | 商品编号 | Long |
//wareName | 商品名称 | String |
//afsServiceStep | 服务单环节 | Integer | 申请阶段(10),审核不通过(20),客服审核(21),商家审核(22),京东收货(31),商家收货(32), 京东处理(33),商家处理(34), 用户确认(40),完成(50), 取消(60);
//afsServiceStepName | 服务单环节名称 | String | 申请阶段,客服审核,商家审核,京东收货,商家收货, 京东处理,商家处理, 用户确认,完成, 取消;
//cancel | 是否可取消 | int | 0代表否，1代表是


        return $response;
    }

    /**
     * TODO 待测 10.7  根据服务单号查询服务单明细信息
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_after_sale_get_service_detail_info_url()
    {
        return $this->host . $this->edition . "/api/afterSale/getServiceDetailInfo";
    }

    function api_after_sale_get_service_detail_info($afsServiceId, $appendInfoSteps)
    {

        $params                    = array();
        $params['token']           = $this->access_token;
        $params['afsServiceId']    = $afsServiceId;//服务单号 | long必需
        $params['appendInfoSteps'] = $appendInfoSteps;//获取信息模块 | List<integer>  | 不设置数据表示只获取服务单主信息、商品明细以及客户信息；1、代表增加获取售后地址信息 2、代表增加获取客户发货信息 3、代表增加获取退款明细 4、 增加获取跟踪信息 5.获取允许的操作信息

        // 请求示例 {"afsServiceId":100011597,"appendInfoSteps":[]}

        $response = $this->post($this->api_after_sale_get_service_detail_info_url(), $params, true);

//ResultDTO实体
//参数名 | 含义 | 参数类型 | 其他
//success | 状态码 | int | true成功，false失败
//result | 服务单详情 | CompatibleServiceDetailDTO | 没有数据返回空
//resultMessage | 异常消息提示 | String |
//resultCode | 异常代码Key |  | 0无错误信息
//                            1001服务单号为空
//                            2000 查询出错
//
//CompatibleServiceDetailDTO实体
//参数名 | 含义 | 参数类型 | 其他
//afsServiceId | 服务单号 | Integer |
//customerExpect | 服务类型码 | Integer | 退货(10)、换货(20)、维修(20)
//afsApplyTime | 服务单申请时间 | String | 格式为yyyy-MM-dd HH:mm:ss
//orderId | 订单号 | Long |
//isHasInvoice | 是不是有发票 | int | 0没有 1有
//isNeedDetectionReport | 是不是有检测报告 | int | 0没有 1有
//isHasPackage | 是不是有包装 | int | 0没有 1有
//questionPic | 上传图片访问地址 | String | 不同图片逗号分割，可能为空
//afsServiceStep | 服务单环节 | int | 申请阶段(10),审核不通过(20),客服审核(21),商家审核(22),京东收货(31),商家收货(32), 京东处理(33),商家处理(34), 用户确认(40),完成(50), 取消(60);
//afsServiceStepName | 服务单环节名称 | String | 申请阶段,客服审核,商家审核,京东收货,商家收货, 京东处理,商家处理, 用户确认,完成, 取消;
//approveNotes | 审核意见 | String |
//questionDesc | 问题描述 | String | 可能为空
//approvedResult | 审核结果 | Integer | 直赔积分(11),直赔余额(12),直赔优惠卷(13),直赔京豆 (14), 直赔商品(21),上门换新(22),自营取件(31),客户送货(32),客户发货(33),闪电退款(34),虚拟退款(35),大家电检测 (80),大家电安装(81),大家电移机(82),大家电维修(83),大家电其它(84);
//approvedResultName | 审核结果名称 | String | 直赔积分,直赔余额,直赔优惠卷,直赔京豆, 直赔商品,上门换新,自营取件,客户送货,客户发货,闪电退款,虚拟退款,大家电检测,大家电安装,大家电移机,大家电维修 ,大家电其它;
//processResult | 处理结果 | Integer | 返修换新(23), 退货(40), 换良(50),原返(60),病单 (71),出检(72),维修(73),强制关单 (80),线下换新(90)
//processResultName | 处理结果名称 | String | 返修换新,退货 , 换良,原返,病单,出检,维修,强制关单,线下换新
//serviceCustomerInfoDTO | 客户信息 | ServiceCustomerInfoDTO |
//serviceAftersalesAddressInfoDTO | 售后地址信息 | ServiceAftersalesAddressInfoDTO |
//serviceExpressInfoDTO | 客户发货信息 | ServiceExpressInfoDTO |
//serviceFinanceDetailInfoDTOs | 退款明细 | List<ServiceFinanceDetailInfoDTO> |
//serviceTrackInfoDTOs | 服务单追踪信息 | List<ServiceTrackInfoDTO> |
//serviceDetailInfoDTOs | 服务单商品明细 | List<ServiceDetailInfoDTO> |
//allowOperations | 获取服务单允许的操作列表 | List<Integer>  | 列表为空代表不允许操作 列表包含1代表取消 列表包含2代表允许填写或者修改客户发货信息
//
//
//ServiceCustomerInfoDTO实体
//参数名 | 含义 | 参数类型 | 其他
//customerPin | 客户京东账号 | String |
//customerName | 用户昵称 | String |
//customerContactName | 服务单联系人 | String |
//customerTel | 联系电话 | String |
//customerMobilePhone | 手机号 | String |
//customerEmail | 电子邮件地址 | String |
//customerPostcode | 邮编 | String |
//
//ServiceAftersalesAddressInfoDTO实体
//参数名 | 含义 | 参数类型 | 其他
//address | 售后地址 | String |
//tel | 售后电话 | String |
//linkMan | 售后联系人 | String |
//postCode | 售后邮编 | String |
//
//serviceExpressInfoDTO实体
//参数名 | 含义 | 参数类型 | 其他
//afsServiceId | 服务单号 | Integer |
//freightMoney | 运费 | String |
//expressCompany | 快递公司名称 | String | 中文
//deliverDate | 客户发货日期 | String | 格式为yyyy-MM-dd HH:mm:ss
//expressCode | 快递单号 | String |
//
//ServiceFinanceDetailInfoDTO实体
//参数名 | 含义 | 参数类型 | 其他
//refundWay | 退款方式 | int |
//refundWayName | 退款方式名称 | String |
//status | 状态 | int |
//statusName | 状态名称 | String |
//refundPrice | 退款金额 | BigDecimal |
//wareName | 商品名称 | String |
//wareId | 商品编号 | Integer |
//
//
//ServiceTrackInfoDTO实体
//参数名 | 含义 | 参数类型 | 其他
//afsServiceId | 服务单号 | Integer |
//title | 追踪标题 | String |
//context | 追踪内容 | String |
//createDate | 提交时间 | String | 格式为yyyy-MM-dd HH:mm:ss
//createName | 操作人昵称 | String |
//createPin | 操作人账号 | String |
//
//ServiceDetailInfoDTO实体
//参数名 | 含义 | 参数类型 | 其他
//wareId | 商品编号 | Integer |
//wareName | 商品名称 | String |
//wareBrand | 商品品牌 | String |
//afsDetailType | 明细类型 | Integer | 主商品(10), 赠品(20), 附件(30)，拍拍取主商品就可以
//wareDescribe | 附件描述 | String |

        // 参见:10.7  根据服务单号查询服务单明细信息.json

        return $response;
    }

    /**
     * TODO 待测 10.8  取消服务单/客户放弃
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_after_sale_audit_cancel_url()
    {
        return $this->host . $this->edition . "/api/afterSale/auditCancel";
    }

    function api_after_sale_audit_cancel($serviceIdList, $approveNotes)
    {

        $params                  = array();
        $params['token']         = $this->access_token;
        $params['serviceIdList'] = $serviceIdList;//服务单号集合 | ArrayList<Integer>| 必需
        $params['approveNotes']  = $approveNotes;//审核意见 | String | 必需


        $response = $this->post($this->api_after_sale_audit_cancel_url(), $params, true);

//参数名 | 含义 | 参数类型 | 其他
//success | 状态码 | boolean | true成功 false失败
//result | 返回内容 | boolean | true成功 false失败
//resultMessage | 错误信息 | String  |
//resultCode | 异常代码Key | String |

        // 参见:10.8  取消服务单/客户放弃.json

        return $response;
    }

    /******************************************** 10、 售后相关接口 end   ********************************************/



    /******************************************** 发票相关接口 start   ********************************************/

    /**
     * TODO 待测 11.1  申请开票接口.v1
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_invoice_submit_url()
    {
        return $this->host . $this->edition . "/api/invoice/submit";
    }

    function api_invoice_submit($params = array())
    {
        $params['token']        = $this->access_token;

        $response = $this->post($this->api_invoice_submit_url(), $params, true);

        //参数名 | 含义 | 参数类型 | 其他
        //success | 状态码 | boolean | true成功 false失败
        //result | 返回内容 | boolean | true成功 false失败
        //resultMessage | 错误信息 | String  |
        //resultCode | 异常代码Key | String |

        // 参见:11.1  申请开票接口.v1.json

        return $response;
    }

    /**
     * TODO 待测 11.2  查询发票信息接口
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_invoice_select_url()
    {
        return $this->host . $this->edition . "/api/invoice/select";
    }

    function api_invoice_select($markId, $queryExts)
    {
        $params['token']        = $this->access_token;
        $params['markId']       = $markId; // String | 必填 | 第三方申请单号：申请发票的唯一 id 标识
        $params['queryExts']    = $queryExts; // String | 非必填 | queryExts 逗号间隔的参数，ignoreApplyState 忽略申请单状态过滤。

        $response = $this->post($this->api_invoice_select_url(), $params, true);

        //参数名 | 含义 | 参数类型 | 其他
        //success | 状态码 | boolean | true成功 false失败
        //result | 返回内容 | boolean | true成功 false失败
        //resultMessage | 错误信息 | String  |
        //resultCode | 异常代码Key | String |

        // 参见:11.2  查询发票信息接口.json

        return $response;
    }

    /**
     * TODO 待测 11.3  查询发票明细接口
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_invoice_query_invoice_item_url()
    {
        return $this->host . $this->edition . "/api/invoice/queryInvoiceItem";
    }

    function api_invoice_query_invoice_item($invoiceId, $invoiceCode)
    {
        $params['token']        = $this->access_token;
        $params['invoiceId']       = $invoiceId; // String | 必填 | 发票号
        $params['invoiceCode']    = $invoiceCode; // String | 必填 | 发票代码

        $response = $this->post($this->api_invoice_query_invoice_item_url(), $params, true);

        //参数名 | 含义 | 参数类型 | 其他
        //success | 状态码 | boolean | true成功 false失败
        //result | 返回内容 | boolean | true成功 false失败
        //resultMessage | 错误信息 | String  |
        //resultCode | 异常代码Key | String |

        // 参见:11.3  查询发票明细接口.json

        return $response;
    }

    /**
     * TODO 待测 11.4  查询发票运单号
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_invoice_waybill_url()
    {
        return $this->host . $this->edition . "/api/invoice/waybill";
    }

    function api_invoice_waybill($markId)
    {
        $params['token']        = $this->access_token;
        $params['markId']       = $markId; // String | 必填 | 第三方申请单号：申请发票的唯一 id 标识

        $response = $this->post($this->api_invoice_waybill_url(), $params, true);

        //参数名 | 含义 | 参数类型 | 其他
        //success | 状态码 | boolean | true成功 false失败
        //result | 返回内容 | boolean | true成功 false失败
        //resultMessage | 错误信息 | String  |
        //resultCode | 异常代码Key | String |

        // 参见:11.4  查询发票运单号.json

        return $response;
    }

    /**
     * TODO 待测 11.6  获取电子发票信息.v1
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_invoice_get_invoice_list_url()
    {
        return $this->host . $this->edition . "/api/invoice/getInvoiceList";
    }

    function api_invoice_get_invoice_list($jdOrderId, $ivcType, $queryExts)
    {
        $params['token']        = $this->access_token;
        $params['jdOrderId']       = $jdOrderId; // Long | 必填 | 订单号,42747145688
        $params['ivcType']       = $ivcType; // Integer | 必填 | 发票类型：1 普票，2 增票，3 电子发票
        $params['queryExts']       = $queryExts; // String | 非必填 | 扩展参数：英文逗号间隔输入 prefixZero：增票发票号前面补齐零 electronicVAT：增票电子化，（返回独立的对象）

        $response = $this->post($this->api_invoice_get_invoice_list_url(), $params, true);

        //参数名 | 含义 | 参数类型 | 其他
        //success | 状态码 | boolean | true成功 false失败
        //result | 返回内容 | boolean | true成功 false失败
        //resultMessage | 错误信息 | String  |
        //resultCode | 异常代码Key | String |

        // 参见:11.6  获取电子发票信息.v1.json

        return $response;
    }

    /**
     * TODO 待测 11.7  订单号查询第三方申请单号.v1
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_invoice_query_thr_apply_no_url()
    {
        return $this->host . $this->edition . "/api/invoice/queryThrApplyNo";
    }

    function api_invoice_query_thr_apply_no($jdOrderId)
    {
        $params['token']        = $this->access_token;
        $params['jdOrderId']       = $jdOrderId; // Long | 必填 | 订单号,42747145688

        $response = $this->post($this->api_invoice_query_thr_apply_no_url(), $params, true);

        //参数名 | 含义 | 参数类型 | 其他
        //success | 状态码 | boolean | true成功 false失败
        //result | 返回内容 | boolean | true成功 false失败
        //resultMessage | 错误信息 | String  |
        //resultCode | 异常代码Key | String |

        // 参见:11.7  订单号查询第三方申请单号.v1.json

        return $response;
    }

    /**
     * TODO 待测 11.8  订单号查询发票物流信息.v1
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function api_invoice_query_delivery_no_url()
    {
        return $this->host . $this->edition . "/api/invoice/queryDeliveryNo";
    }

    function api_invoice_query_delivery_no($jdOrderId)
    {
        $params['token']        = $this->access_token;
        $params['jdOrderId']       = $jdOrderId; // Long | 必填 | 订单号,42747145688

        $response = $this->post($this->api_invoice_query_delivery_no_url(), $params, true);

        //参数名 | 含义 | 参数类型 | 其他
        //success | 状态码 | boolean | true成功 false失败
        //result | 返回内容 | boolean | true成功 false失败
        //resultMessage | 错误信息 | String  |
        //resultCode | 异常代码Key | String |

        // 参见:11.8  订单号查询发票物流信息.v1.json

        return $response;
    }

    /******************************************** 发票相关接口 end   ********************************************/



    /******************************************** 金彩对账相关接口 start   ********************************************/

    /**
     * TODO 待测 获取金彩账单明细接口
     * HTTPS请求方式：POST
     *
     * @return string
     */
    function decare_http_json_jincai_get_bill_detail_url()
    {
        return $this->host . $this->edition . "/decare/http/JSON/jincai/getBillDetail";
    }

    /**
     * @param string $billId
     * @param string $orderId
     * @param int $pageNo
     * @param int $pageSize
     * @return mixed
     */
    function decare_http_json_jincai_get_bill_detail(
        $billId   = '', /* 账单号 */
        $orderId  = '', /* 订单号 */
        $pageNo   = 1, /* 页码 */
        $pageSize = 10 /* 条数 */
    )
    {

        $params                  = array();
        $params['token']        = $this->access_token;
        $params['billId']       = $billId;  //账单号 | String| 非必需
        $params['orderId']      = $orderId; //订单号 | String | 非必需
        $params['pageNo']       = $pageNo;  //页码 | 默认第1页 | Int | 非必需
        $params['pageSize']    = $pageSize; //条数 | 默认10条 | 最多100条 | Int | 非必需   文档上有坑 写着最多1000条.其实最多100条


        $response = $this->post($this->decare_http_json_jincai_get_bill_detail_url(), $params, true);

        //参数名 | 含义 | 参数类型 | 其他
        //success | 状态码 | boolean | true成功 false失败
        //result | 返回内容 | boolean | true成功 false失败
        //resultMessage | 错误信息 | String  |
        //resultCode | 异常代码Key | String |

        // 参见:获取金彩账单明细.json

        return $response;
    }

    /******************************************** 金彩对账相关接口 end   ********************************************/



    //======================Other End========================

    /**
     * GET wrappwer for oAuthRequest.
     *
     * @return mixed
     */
    function get($url, $parameters = array())
    {
        $response = $this->oAuthRequest($url, 'GET', $parameters);
        if ($this->format === 'json' && $this->decode_json) {
            return json_decode($response, true);
        }
        return $response;
    }

    /**
     * POST wreapper for oAuthRequest.
     *
     * @return mixed
     */
    function post($url, $parameters = array(), $multi = false)
    {

        $response = $this->oAuthRequest($url, 'POST', $parameters, $multi);
        if ($this->format === 'json' && $this->decode_json) {
            return json_decode($response, true);
        }
        return $response;
    }

    /**
     * DELTE wrapper for oAuthReqeust.
     *
     * @return mixed
     */
    function delete($url, $parameters = array())
    {
        $response = $this->oAuthRequest($url, 'DELETE', $parameters);
        if ($this->format === 'json' && $this->decode_json) {
            return json_decode($response, true);
        }
        return $response;
    }

    /**
     * PUT wrapper for oAuthReqeust.
     *
     * @param       $url
     * @param array $parameters
     *
     * @return mixed|string
     */
    function put($url, $parameters = array())
    {
        $response = $this->oAuthRequest($url, 'PUT', $parameters);
        if ($this->format === 'json' && $this->decode_json) {
            return json_decode($response, true);
        }
        return $response;
    }

    /**
     * Format and sign an OAuth / API request
     *
     * @return string
     * @ignore
     */
    function oAuthRequest($url, $method, $params, $multi = false)
    {
        if (strrpos($url, 'http://') !== 0 && strrpos($url, 'https://') !== 0) {
            $url = "{$this->host}/{$this->edition}/{$url}";
        } else {
            $url = $url . '?client_id=' . $this->client_id . '&client_secret=' . $this->client_secret;
        }

        $parameters = array_merge_recursive($this->params_base, $params);

        // TODO jd_vop_api_logs

        switch ($method) {
            case 'GET' :
                $url = $url . http_build_query($parameters);
                return $this->http($url, 'GET');
            case 'PUT' :
                $headers = array();
                if (!$multi && (is_array($parameters) || is_object($parameters))) {

                    if ($this->format === 'json' && $this->decode_json) {
                        $body = json_encode($parameters);
                        if ($this->debug) {
                            echo '============= BODY =============';
                            echo '<br/>';
                            echo $body;
                            echo '<br/>';
                            echo '============= BODY =============';
                            echo '<br/>';
                        }
                    } else {
                        $body = http_build_query($parameters);
                    }

                } else {
                    $body       = self::build_http_query_multi($parameters);
                    $headers [] = "Content-Type: multipart/form-data; boundary=" . self::$boundary;
                }
                return $this->http($url, 'PUT', $body, $headers);
            case 'DELETE' :
                if (!empty($parameters)) {
                    $url = $url . '&' . http_build_query($parameters);
                }

                return $this->http($url, 'DELETE');
            default :
                $headers = array();
                if (!$multi && (is_array($parameters) || is_object($parameters))) {

                    if ($this->format === 'json' && $this->decode_json) {
                        $body = json_encode($parameters);
                        if ($this->debug) {
                            echo '============= BODY =============';
                            echo '<br/>';
                            echo $body;
                            echo '<br/>';
                            echo '============= BODY =============';
                            echo '<br/>';
                        }
                    } else {
                        $body = http_build_query($parameters);
                    }

                } else {
                    $body       = self::build_http_query_multi($parameters);
                    $headers [] = "Content-Type: multipart/form-data; boundary=" . self::$boundary;
                }
                return $this->http($url, $method, $body, $headers);
        }
    }

    /**
     * Make an HTTP request
     *
     * @return string API results
     * @ignore
     */
    function http($url, $method, $postfields = NULL, $headers = array())
    {
        $this->http_info = array();
        $ci              = curl_init();
        /* Curl settings */
        curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
        curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ci, CURLOPT_ENCODING, "");
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
//      curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
        curl_setopt($ci, CURLOPT_HEADER, FALSE);

        switch ($method) {

            case 'GET':

                curl_setopt($ci, CURLOPT_HTTPGET, TRUE);
                if (!empty($postfields)) {
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
                    $this->postdata = $postfields;
                }
                break;

            case 'POST':

                curl_setopt($ci, CURLOPT_POST, TRUE);

                if ($this->format === 'json') {
                    $headers [] = "Content-Type: application/json;";
                }

                if (!empty($postfields)) {
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
                    $this->postdata = $postfields;
                }
                break;

            case 'PUT':
                curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'PUT');
                $headers [] = "Content-Type: application/json;";
                if (!empty($postfields)) {
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
                    $this->postdata = $postfields;
                }
                break;

            case 'DELETE':
                curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
                if (!empty($postfields)) {
                    $url = "{$url}?{$postfields}";
                    echo $url;
                    echo '<br/>';
                } else {
                    $url = $url;
                }
                break;

        }


//      $headers[] = "API-RemoteIP: " . $_SERVER['REMOTE_ADDR'];
        curl_setopt($ci, CURLOPT_URL, $url);
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE);
        $response        = curl_exec($ci);
        $this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
        $this->http_info = array_merge($this->http_info, curl_getinfo($ci));
        $this->url       = $url;

        if ($this->debug) {
            echo "=====post data======\r\n";
            echo '<br/>';
            var_dump($postfields);
            echo '<br/>';

            echo '=====info=====' . "\r\n";
            echo '<br/>';
            print_r(curl_getinfo($ci));
            echo '<br/>';

            echo '=====$response=====' . "\r\n";
            echo '<br/>';
            print_r($response);
            echo '<br/>';
        }


        $code     = curl_getinfo($ci);
        $response = json_decode($response, true);

        if (!$response) {// TODO 暂时修正京东返回空的问题
            $response = array();
        }

        $response['code'] = $code['http_code'];

        $___url___                 = str_replace($this->host . $this->edition, '', $url);
        $log_data                  = array();
        $log_data['url']           = $url;
        $log_data['api']           = substr($___url___, 0, strpos($___url___, '?'));
        $log_data['method']        = $method;
        $log_data['post_fields']   = $postfields;
        $log_data['curl_info']     = json_encode(curl_getinfo($ci), JSON_UNESCAPED_UNICODE);
        $log_data['success']       = isset($response['success']) ? $response['success'] : "exit0";
        $log_data['resultMessage'] = isset($response['resultMessage']) ? $response['resultMessage'] : "exit0";
        $log_data['resultCode']    = isset($response['resultCode']) ? $response['resultCode'] : "exit0";
        $log_data['result']        = isset($response['result']) ? json_encode($response['result'], JSON_UNESCAPED_UNICODE) : "";

        $this->_logsModel->insert($log_data);// TODO 屏蔽测试

//        $this->_transmitShopJdLogsService->transmitShopJdLogsToBackup($log_data);


        if (!empty($response['status']) && $response['status'] == 'Unauthorized.') {
            /* header("Location:".site_url("Login/logout"));
             exit();*/
            $response['status'] = 'Unauthorized.';
        }

        $response = json_encode($response);

        curl_close($ci);

        return $response;
    }


    private function request($request_url, $request_body, $method = 'post')
    {
        $request_url = $request_url . '?client_id=' . $this->client_id . '&client_secret=' . $this->client_secret;
        $curl_handle = curl_init();

        curl_setopt($curl_handle, CURLOPT_URL, $request_url);
        curl_setopt($curl_handle, CURLOPT_FILETIME, true);
        curl_setopt($curl_handle, CURLOPT_FRESH_CONNECT, false);
        if (version_compare(phpversion(), "5.5", "<=")) {
            curl_setopt($curl_handle, CURLOPT_CLOSEPOLICY, CURLCLOSEPOLICY_LEAST_RECENTLY_USED);
        } elseif (version_compare(phpversion(), "7.0", "<")) {
            curl_setopt($curl_handle, CURLOPT_SAFE_UPLOAD, false);
        }
        curl_setopt($curl_handle, CURLOPT_MAXREDIRS, 5);
        curl_setopt($curl_handle, CURLOPT_HEADER, false);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handle, CURLOPT_TIMEOUT, 5184000);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 120);
        curl_setopt($curl_handle, CURLOPT_NOSIGNAL, true);
        curl_setopt($curl_handle, CURLOPT_REFERER, $request_url);


        if (extension_loaded('zlib')) {
            curl_setopt($curl_handle, CURLOPT_ENCODING, '');
        }
        if ($method == 'put') {
            curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, 'PUT');
        } else {
            curl_setopt($curl_handle, CURLOPT_POST, true);
        }

        if (array_key_exists('image', $request_body)) {
            $file                  = $request_body['image'];
            $request_body['image'] = new CurlFile($file);//PHP 5.5
        } else {
            $request_body = http_build_query($request_body);
        }
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $request_body);

        $response_text   = curl_exec($curl_handle);
        $response_header = curl_getinfo($curl_handle);
        curl_close($curl_handle);

        return $this->objectToArray(json_decode($response_text));
    }

    /**
     * @param $obj
     *
     * @return array
     */
    public function objectToArray($obj)
    {
        $arr = is_object($obj) ? get_object_vars($obj) : $obj;
        if (is_array($arr)) {
            return array_map(array('SuperDeskCoreSDK', 'objectToArray'), $arr);
        } else {
            return $arr;
        }
    }

    /**
     * 处理多媒体数据内容
     *
     * @ignore
     */
    public static function build_http_query_multi($params)
    {
        if (!$params) return '';

        uksort($params, 'strcmp');

        $pairs = array();

        self::$boundary = $boundary = uniqid('------------------');
        $MPboundary     = '--' . $boundary;
        $endMPboundary  = $MPboundary . '--';
        $multipartbody  = '';

        foreach ($params as $parameter => $value) {

            if (in_array($parameter, array('pic', 'image', 'Filedata')) && $value{0} == '@') {
                $url      = ltrim($value, '@');
                $content  = file_get_contents($url);
                $array    = explode('?', basename($url));
                $filename = $array[0];

                $multipartbody .= $MPboundary . "\r\n";
                $multipartbody .= 'Content-Disposition: form-data; name="' . $parameter . '"; filename="' . $filename . '"' . "\r\n";
                $multipartbody .= "Content-Type: image/unknown\r\n\r\n";
                $multipartbody .= $content . "\r\n";
            } else {
                $multipartbody .= $MPboundary . "\r\n";
                $multipartbody .= 'content-disposition: form-data; name="' . $parameter . "\"\r\n\r\n";
                $multipartbody .= $value . "\r\n";
            }

        }

        $multipartbody .= $endMPboundary;
        return $multipartbody;
    }

}