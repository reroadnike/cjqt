<?php
/**
 * StusGame Framework 部分函数库
 *
 * @author Kenvix
 */
defined('IN_IA') or exit('Access Denied');


/**
 * 执行一个网络请求而不等待返回结果
 *
 * @param string $url    URL
 * @param string $post   post数据包，留空为get
 * @param string $cookie cookies
 *
 * @return bool fsockopen是否成功
 */
function sendRequest($url, $post = '', $cookie = '')
{
    if (function_exists('fsockopen')) {
        $matches = parse_url($url);
        $host    = $matches['host'];
        if (substr($url, 0, 8) == 'https://') {
            $host = 'ssl://' . $host;
        }
        $path = $matches['path'] ? $matches['path'] . ($matches['query'] ? '?' . $matches['query'] : '') : '/';
        $port = !empty($matches['port']) ? $matches['port'] : 80;
        if (!empty($post)) {
            $out = "POST $path HTTP/1.1\r\n";
            $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $out .= "Host: $host\r\n";
            $out .= "Connection: Close\r\n\r\n";
            $out .= $post;
        } else {
            $out = "GET $path HTTP/1.1\r\n";
            $out .= "Host: $host\r\n";
            $out .= "Connection: Close\r\n\r\n";
        }
        $fp = fsockopen($host, $port);
        if (!$fp) {
            return false;
        } else {
            stream_set_blocking($fp, 0);
            stream_set_timeout($fp, 0);
            fwrite($fp, $out);
            fclose($fp);
            return true;
        }
    } else {
        $x = new wcurl($url);
        $x->set(CURLOPT_CONNECTTIMEOUT, 1);
        $x->set(CURLOPT_TIMEOUT, 1);
        $x->addcookie($cookie);
        if (empty($post)) {
            $x->post($post);
        } else {
            $x->exec();
        }
        return true;
    }
}

/**
 * fosckopen 改进版
 */

function XFSockOpen($url, $limit = 0, $post = '', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15, $block = false)
{
    if (function_exists('fsockopen')) {
        $return  = '';
        $matches = parse_url($url);
        $host    = $matches['host'];
        $path    = $matches['path'] ? $matches['path'] . ($matches['query'] ? '?' . $matches['query'] : '') : '/';
        $port    = !empty($matches['port']) ? $matches['port'] : 80;

        if ($post) {
            $out = "POST $path HTTP/1.0\r\n";
            $out .= "Accept: */*\r\n";
            //$out .= "Referer: $boardurl\r\n";
            $out .= "Accept-Language: zh-cn\r\n";
            $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
            $out .= "Host: $host\r\n";
            $out .= 'Content-Length: ' . strlen($post) . "\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Cache-Control: no-cache\r\n";
            $out .= "Cookie: $cookie\r\n\r\n";
            $out .= $post;
        } else {
            $out = "GET $path HTTP/1.0\r\n";
            $out .= "Accept: */*\r\n";
            //$out .= "Referer: $boardurl\r\n";
            $out .= "Accept-Language: zh-cn\r\n";
            $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
            $out .= "Host: $host\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Cookie: $cookie\r\n\r\n";
        }
        $fp = fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
        if (!$fp) {
            return false;//note $errstr : $errno \r\n
        } else {
            stream_set_blocking($fp, $block);
            stream_set_timeout($fp, $timeout);
            fwrite($fp, $out);
            while (!feof($fp)) {
                $status = stream_get_meta_data($fp);
                if (!empty($status['timed_out'])) {
                    return false;
                }
                if (($header = fgets($fp)) && ($header == "\r\n" || $header == "\n")) {
                    break;
                }
            }
            $stop = false;
            while (!feof($fp) && !$stop) {
                $status = stream_get_meta_data($fp);
                if (!empty($status['timed_out'])) {
                    return false;
                }
                $data   = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
                $return .= $data;
                if ($limit) {
                    $limit -= strlen($data);
                    $stop  = $limit <= 0;
                }
            }
            fclose($fp);
            return $return;
        }
    }
}

