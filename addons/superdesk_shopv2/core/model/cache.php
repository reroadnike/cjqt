<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Cache_SuperdeskShopV2Model
{
    public function get_key($key = '', $uniacid = '')
    {
        global $_W;
        if (empty($uniacid)) {
            $uniacid = $_W['uniacid'];
        }
        return SUPERDESK_SHOPV2_PREFIX . md5($uniacid . '_new_' . $key);
    }

    public function getArray($key = '', $uniacid = '')
    {
//        echo "Cache_SuperdeskShopV2Model=>getArray";
//        echo "<br/>";
//        echo $key;
//        echo "<br/>";
//        echo $uniacid;
//        echo "<br/>";
        return $this->get($key, $uniacid);
    }

    public function getString($key = '', $uniacid = '')
    {
        return $this->get($key, $uniacid);
    }

    public function get($key = '', $uniacid = '')
    {
        return cache_read($this->get_key($key, $uniacid));
    }

    public function set($key = '', $value = NULL, $uniacid = '')
    {
        cache_write($this->get_key($key, $uniacid), $value);
    }
}