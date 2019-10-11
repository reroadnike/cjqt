<?php


/**
 * Class Yitu1vnQuerySDK
1、appid appsercret账号鉴权
Appid=xxxx
SecretKey=xxx
Expired=600

2、拼接有效签名串
appid=xxx&m=xxx&t=xxx&e=xx&r=xxx

3、生成签名串signature
(1)使用 HMAC-SHA1 算法对请求进行签名
(2)签名串使用 Base64 编码

根据签名方法signature= Base64(HMAC-SHA1(SecretKey, orignal) + original)计算签名

4、使用签名
http请求的header中的Authorization参数

Appid=4439
SecretKey=da1905e296add5ebf70d0c00777439d0
Expired=600

 */
class Yitu1vnQuerySDK
{
    public $host = "https://iauth-dev.wecity.qq.com/";
    public $edition = "cgi-bin";


    public $appid = '4439';
    public $secret_key = 'da1905e296add5ebf70d0c00777439d0';
    public $expired = 600;

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
        $this->host = "https://iauth-dev.wecity.qq.com/";
        $this->edition = "cgi-bin";

        $this->appid = '4439';

        $this->params_base['sig']          = md5($this->appid."&".$this->params_base['timeStamp']);
    }

    /**
     * @return string
     * http://wx.palmnest.com/super_reception/api/base/userInfo
     */
    function yitu_1vn_query_url()
    {
        return $this->host . $this->edition . "/yitu_1vn_query.php";
    }


    /**
     * 用户基本资料信息获取接口（新）
     * 根据用户的电话号码信息，获取用户基础资料信息、所在项目、企业的信息
     * @param $userMobile
     *
     * @return array
     */
    public function yitu_1vn_query($user_id)
    {
        $params = array();
        $params['user_id'] = $user_id;
        $response = $this->post($this->yitu_1vn_query_url() , $params , true);
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
//            $url = $url . '?appid=' . $this->appid . '&app_secret=' . $this->app_secret;
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
//                $headers [] = "Content-Type: application/json;";
                if (!empty($postfields)) {
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
                    $this->postdata = $postfields;
                }
                break;

            case 'PUT':
                curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'PUT');
//                $headers [] = "Content-Type: application/json;";
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
        $response->code = $code['http_code'];
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
        $request_url = $request_url . '?appid=' . $this->appid . '&app_secret=' . $this->app_secret;
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

            if (in_array($parameter, array('file', 'image')) && $value{0} == '@') {
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


    /**
     * @brief 使用HMAC-SHA1算法生成oauth_signature签名值
     *
     * @param $secret_key  密钥
     * @param $orignal  源串
     *
     * @return 签名值
     */
    public static function getSignature($secret_key, $orignal)
    {
        $signature = "";
        if (function_exists('hash_hmac')) {
            $signature = base64_encode(hash_hmac("sha1", $orignal, $secret_key, true) . $orignal);
        } else {
            $blocksize = 64;
            $hashfunc = 'sha1';
            if (strlen($secret_key) > $blocksize) {
                $secret_key = pack('H*', $hashfunc($secret_key));
            }
            $secret_key = str_pad($secret_key, $blocksize, chr(0x00));
            $ipad = str_repeat(chr(0x36), $blocksize);
            $opad = str_repeat(chr(0x5c), $blocksize);
            $hmac = pack(
                'H*', $hashfunc(
                    ($secret_key ^ $opad) . pack(
                        'H*', $hashfunc(
                            ($secret_key ^ $ipad) . $orignal
                        )
                    )
                )
            );
            $signature = base64_encode($hmac . $orignal);
        }
        return $signature;
    }

}