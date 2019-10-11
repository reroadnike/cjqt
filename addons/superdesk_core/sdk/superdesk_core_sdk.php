<?php


/**
 *
 * Class SuperDeskCoreSDK
 * 基础平台系统系统对外开放的接口通信方式采用HTTP POST方式
 * POST的参数以Form表单中的name=value方式放入body中提交
 * 返回的结果都是以JSON字符串返回
 */
class SuperDeskCoreSDK
{
    public $host = "http://wx.palmnest.com/";
    public $edition = "super_service";
//    public $edition = "super_reception"; // 弃置


    public $app_key = '1cda2c79780d18aa8f51edaea3649cf7';//测试默认为 1cda2c79780d18aa8f51edaea3649cf7
    public $app_secret = '57d8c6e96cfa5';
    public $api_version = '1';//测试默认为 1

    public $timeout = 30;
    public $connecttimeout = 30;
    public $ssl_verifypeer = FALSE;
    public $useragent = 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2049.0 Safari/537.36';
    public $format = 'form';// form || json
    public $decode_json = true;
    public $debug = false;
    public $params_base = array();

    public static $boundary = '';

    function __construct()
    {
//        $this->host = "http://www.avic-s.com/"; // 弃置
        $this->host = "http://superdesk.avic-s.com/"; //正式
        $this->edition = "super_service";
        $this->app_key = '1cda2c79780d18aa8f51edaea3649cf7';
        $this->app_secret = '57d8c6e96cfa5';
        $this->api_version = 1;



        $this->params_base['timeStamp']     = date('YmdHis',time());//时间戳 yyyyMMddhhmmss
        $this->params_base['IMEI']          = '0000';//设备串号（移动终端设备串号，默认0000）
        $this->params_base['apiVersion']    = $this->api_version;//接口版本号（服务端向下版本兼容控制），默认值为1
        $this->params_base['SIGN']          = md5($this->params_base['IMEI']."&".$this->app_key."&".$this->params_base['timeStamp']);
        //MD5加密后的签名字串（不区分大小写）SIGN=MD5(IMEI&AppKEY&timeStamp)应用的全局唯一标识(以此做为读数据权限标识)。AppKEY安全密钥

//        echo $this->params_base['IMEI']."&".$this->app_key."&".$this->params_base['timeStamp'];
    }

    /**
     * @return string
     * http://wx.palmnest.com/super_reception/api/base/userInfo 弃置
     * http://wx.palmnest.com/super_service/wechat/userInfo
     */
    function userInfoURL()
    {
        return $this->host . $this->edition . "/wechat/userInfo";
    }

    /**
     * @return string
     * http://wx.palmnest.com/super_reception/api/base/access_token 弃置
     * http://wx.palmnest.com/super_service/wechat/access_token
     */
    function accessTokenURL()
    {
        return $this->host . $this->edition . "/wechat/access_token";
    }


    /**
     * 用户基本资料信息获取接口（新）
     * 根据用户的电话号码信息，获取用户基础资料信息、所在项目、企业的信息
     * $userMobile 弃置
     * @param $core_user
     *
     * @return array
     */
    public function userInfo($core_user)
    {

        $params = array();
        $params['userId'] = $core_user; //userMobile换成了userId
        $response = $this->post($this->userInfoURL() , $params , true);

        return $response;
    }


    /**
     * 用户基本资料信息获取接口（新）
     * 根据用户的电话号码信息，获取用户基础资料信息、所在项目、企业的信息
     * @param $userMobile
     *
     * @return array
     */
    public function accessToken()
    {
        $response = $this->post($this->accessTokenURL() , array() , true);
        return $response;
    }


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
//        if (strrpos($url, 'http://') !== 0 && strrpos($url, 'https://') !== 0) {
//            $url = "{$this->host}/{$this->edition}/{$url}";
//        } else {
//            $url = $url . '?app_key=' . $this->app_key . '&app_secret=' . $this->app_secret;
//        }

        $parameters = array_merge_recursive($this->params_base,$params);

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
                    $body = self::build_http_query_multi($parameters);
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
                    $body = self::build_http_query_multi($parameters);
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
        $ci = curl_init();
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
                $headers [] = "Content-Type: application/json;";
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
        $response = curl_exec($ci);
        $this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
        $this->http_info = array_merge($this->http_info, curl_getinfo($ci));
        $this->url = $url;

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
        $code = curl_getinfo($ci);
        $response = json_decode($response);

        if(!empty($response)){
            $response->code = $code['http_code'];
        }

        if (!empty($response->status) && $response->status == 'Unauthorized.') {
            /* header("Location:".site_url("Login/logout"));
             exit();*/
            $response->status = 'Unauthorized.';
        }
        $response = json_encode($response);
        curl_close($ci);
        return $response;
    }


    private function request($request_url, $request_body, $method = 'post')
    {
        $request_url = $request_url . '?app_key=' . $this->app_key . '&app_secret=' . $this->app_secret;
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
            $file = $request_body['image'];
            $request_body['image'] = new CurlFile($file);//PHP 5.5
        } else {
            $request_body = http_build_query($request_body);
        }
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $request_body);

        $response_text = curl_exec($curl_handle);
        $response_header = curl_getinfo($curl_handle);
        curl_close($curl_handle);

        return $this->objectToArray(json_decode($response_text));
    }

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
     * @ignore
     */
    public static function build_http_query_multi($params)
    {
        if (!$params) return '';

        uksort($params, 'strcmp');

        $pairs = array();

        self::$boundary = $boundary = uniqid('------------------');
        $MPboundary = '--' . $boundary;
        $endMPboundary = $MPboundary . '--';
        $multipartbody = '';

        foreach ($params as $parameter => $value) {

            if (in_array($parameter, array('pic', 'image', 'Filedata')) && $value{0} == '@') {
                $url = ltrim($value, '@');
                $content = file_get_contents($url);
                $array = explode('?', basename($url));
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