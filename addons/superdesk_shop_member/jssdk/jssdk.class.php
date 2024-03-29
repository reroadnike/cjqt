<?php
/**
 * 微信jssdk和Oauth功能类,请勿删除本人版权,本人保留本页面所有版权
 * 包含分享签名,获取openid,获取用户信息,获取全局票据等
 *
 */
define('APPID', ''); //设置借用appid
define('APPSN', ''); //设置借用apps
define('SAE_TMP_PATH', true);
if (!function_exists('dump')) {
    function dump($arr)
    {
        echo '<pre>' . print_r($arr, TRUE) . '</pre>';
    }
}

class jssdk
{
    private $appid;
    private $url;
    private $appsn;

    function __construct($appid = '', $secret = '', $urlx = '')
    {
        global $_W;
        /*$this->appid = APPID;
        $this->appsn = APPSN;*/
        $this->appid = empty($appid) ? $_W['account']['key'] : $appid;
        $this->appsn = empty($secret) ? $_W['account']['secret'] : $secret;
        $this->url = (empty($urlx)) ? "http://" . $_SERVER[HTTP_HOST] . $_SERVER[REQUEST_URI] : $urlx;

        load()->classs('weixin.account');
        load()->func('communication');
        $this->weixin_account = new WeiXinAccount();

    }

    public function get_curl($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        return json_decode($data, 1);
    }

    public function post_curl($url, $post = '')
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        return json_decode($data, 1);
    }

    private function get_randstr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    public function get_jsapi_ticket_engine(){
        $jsapiTicket = $this->weixin_account->getJsApiTicket($this->appid ,$this->appsn);
        WeUtility::logging('eso返回的是什么34232323', $this->jsapiTicket);
        return $jsapiTicket;
    }

    public function get_jsapi_ticket()
    {
        if (defined('SAE_TMP_PATH')) {
            /* sae写法
            $mem = memcache_init();
            $ticket = memcache_get($mem, 'ticket');
            if (empty($ticket)) {
                $accessToken = $this->get_access_token();
                $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
                $result = $this->get_curl($url);
                $ticket = $result["ticket"];
                memcache_set($mem, 'ticket', $ticket, 0, 7200);
            }*/
            /* 拥有memcache的服务器写法 */
            if (class_exists('memcache')) {
                $mem = new Memcache;
                $mem->connect('localhost', 11211) or die ("连接memcache错误");
                $ticket = $mem->get('ticket');

                if (empty($ticket)) {
                    $accessToken = $this->get_access_token();
                    $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
                    $result = $this->get_curl($url);
                    $ticket = $result["ticket"];
                    $mem->set('ticket', $ticket, 0, 7200);
                }
            }
        }
        else {
//            $data = json_decode(file_get_contents("jsapi_ticket.json"));
            $data = json_decode($this->get_php_file("jsapi_ticket.php"));
            if ($data->expire_time < time()) {
                $accessToken = $this->get_access_token();
                $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
                $result = json_decode(file_get_contents($url), 1);
                $ticket = $result["ticket"];
                if ($ticket) {
                    $data->expire_time = time() + 7200;
                    $data->jsapi_ticket = $ticket;
//                    file_put_contents("jsapi_ticket.json", json_encode($data));
                    $this->set_php_file("jsapi_ticket.php", json_encode($data));
                }
            } else {
                $ticket = $data->jsapi_ticket;
            }
        }

        return $ticket;
    }


    public function get_access_token_engine(){
//        $accessToken = $this->weixin_account->fetch_token();
        $accessToken = $this->weixin_account->fetch_available_token($this->appid , $this->appsn);
        WeUtility::logging('eso返回的是什么2', $accessToken);
        return $accessToken;
    }

    public function get_access_token()
    {
        if (defined('SAE_TMP_PATH')) {
            /* sae写法
            $mem = memcache_init();
            $access_token = memcache_get($mem, 'access_token');
            if (empty($access_token)) {
                $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $this->appid . "&secret=" . $this->appsn;
                $result = $this->get_curl($url);
                $access_token = $result["access_token"];
                memcache_set($mem, 'access_token', $access_token, 0, 7200);
            }*/
            /* 非sae的memcache写法*/
            if (class_exists('memcache')) {
                $mem = new Memcache;
                $mem->connect('localhost', 11211) or die ("连接memcache错误");
                $access_token = $mem->get('access_token');

                if (empty($access_token)) {
                    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $this->appid . "&secret=" . $this->appsn;
                    $result = $this->get_curl($url);
                    $access_token = $result["access_token"];
                    $mem->set('access_token', $access_token, 0, 7200);
                }
            }

        }
        else {
//            $data = json_decode(file_get_contents("access_token.json"));
            $data = json_decode($this->get_php_file("access_token.php"));
            if ($data->expire_time < time()) {
                $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appid}&secret={$this->appsn}";
                $result = json_decode(file_get_contents($url), 1);
                $access_token = $result["access_token"];
                if ($access_token) {
                    $data->expire_time = time() + 7200;
                    $data->access_token = $access_token;
//                    file_put_contents("access_token.json", json_encode($data));
                    $this->set_php_file("access_token.php", json_encode($data));
                }
            } else {
                $access_token = $data->access_token;
            }
        }
        return $access_token;
    }

    public function get_sign()
    {
        $jsapi_ticket = $this->get_jsapi_ticket_engine();
        $url = $this->url;
        $timestamp = time();
        $nonceStr = $this->get_randstr();
        $string = "jsapi_ticket=$jsapi_ticket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId" => $this->appid,
            "nonceStr" => $nonceStr,
            "timestamp" => $timestamp,
            "url" => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    public function get_code($redirect_uri, $scope = 'snsapi_base', $state = 1)
    { //snsapi_userinfo
        if ($redirect_uri[0] == '/') {
            $redirect_uri = substr($redirect_uri, 1);
        }
        $redirect_uri = urlencode($redirect_uri);
        $response_type = 'code';
        $appid = $this->appid;
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $appid . '&redirect_uri=' . $redirect_uri . '&response_type=' . $response_type . '&scope=' . $scope . '&state=' . $state . '#wechat_redirect';
        header('Location: ' . $url, true, 301);
    }

    public function get_openid($code)
    {
        $grant_type = 'authorization_code';
        $appid = $this->appid;
        $appsn = $this->appsn;
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $appid . '&secret=' . $appsn . '&code=' . $code . '&grant_type=' . $grant_type . '';
        $data = json_decode(file_get_contents($url), 1);
        return $data;
    }

    public function get_user($openid)
    {
        $accessToken = $this->get_access_token_engine();
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$accessToken}&openid={$openid}&lang=zh_CN";
        $data = json_decode(file_get_contents($url), 1);
        return $data;
    }

    public function get_user1($accessToken, $openid)
    {
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $accessToken . '&openid=' . $openid . '&lang=zh_CN';
        $data = json_decode(file_get_contents($url), 1);
        return $data;
    }

    private function get_php_file($filename) {

        return trim(substr(file_get_contents(MODULE_ROOT . '/jssdk/'. $filename), 15));
    }
    private function set_php_file($filename, $content) {
        $fp = fopen(MODULE_ROOT . '/jssdk/'. $filename, "w");
        fwrite($fp, "<?php exit();?>" . $content);
        fclose($fp);
    }
}

?>