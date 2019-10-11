<?php

defined('IN_IA') or exit('Access Denied');

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/logs.class.php');



/**
 * 报文格式：Json 格式
   请求方法的编码格式(utf-8)："application/x-www-form-urlencoded;charset=utf-8"
   交互协议上统一用 UTF-8，避免传递中文数据出现乱码。

 */

/**
 * 快递鸟sdk
 */
class KdniaoSDK
{
    public $host = "http://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx";
//    public $host = "http://sandboxapi.kdniao.cc:8080/kdniaosandbox/gateway/exterfaceInvoke.json";    //测试的

//    public $EBusinessID = "test1383479";    //测试的
//    public $AppKey = "08fb8381-2447-4def-b993-d8f7ab33e02b";    //测试


    public $EBusinessID = "1383479";
    public $AppKey = "af9ceef1-dd65-4da5-bc5a-a1d416b52c89";

    private $_logsModel;

    public $timeout = 30;
    public $connecttimeout = 30;
    public $ssl_verifypeer = FALSE;
    public $useragent = 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2049.0 Safari/537.36';
    public $format = 'form';// form || json
    public $decode_json = true;
    public $debug = false;
    public $params_base = array();

    function __construct()
    {
        global $_W;

        $this->_logsModel = new logsModel();

    }

    /**
     * $requestData= "{'OrderCode':'','ShipperCode':'YTO','LogisticCode':'12345678'}";
     * OrderCode : 订单编号 非必填
     * ShipperCode : 快递公司编码
     * LogisticCode : 快递单号
     */
    public function getOrderTracesByJson($ShipperCode,$LogisticCode) {
        $RequestType = '1002';
        $requestData = array(
            'OrderCode' => '',
            'ShipperCode' => $ShipperCode,
            'LogisticCode' => $LogisticCode
        );
        $requestData = json_encode($requestData);

        $datas = array(
            'EBusinessID' => $this->EBusinessID,
            'RequestType' => $RequestType,
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2',
        );
        $datas['DataSign'] = $this->encrypt($requestData, $this->AppKey);
        $result=$this->http($this->host, $datas);
//        print_r($result);die;
        //根据公司业务处理返回的信息......

        return $result;
    }

    /**
     * 电商Sign签名生成
     * @param data 内容
     * @param appkey Appkey
     * @return DataSign签名
     * //把(jsonStr+APIKey)进行MD5加密，然后Base64编码，最后 进行URL(utf-8)编码
     */
    function encrypt($data, $appkey) {
        return urlencode(base64_encode(md5($data.$appkey)));
    }

    /**
     * Make an HTTP request
     *
     * @return string API results
     * @ignore
     */
    function http($url,$postfields)
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

        curl_setopt($ci, CURLOPT_POST, TRUE);

        $headers [] = "Content-Type: application/x-www-form-urlencoded;";

        if (!empty($postfields)) {
            curl_setopt($ci, CURLOPT_POSTFIELDS, http_build_query($postfields));
            $this->postdata = $postfields;
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
            var_dump($headers);
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

        curl_close($ci);

        return $response;
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

}