<?php

defined('IN_IA') or exit('Access Denied');

require_once IA_ROOT . '/addons/superdesk_shopv2/version.php';
require_once IA_ROOT . '/addons/superdesk_shopv2/defines.php';
require_once SUPERDESK_SHOPV2_INC . 'functions.php';

//session_start();
//include 'model.php';

class Superdesk_jd_vopModuleSite extends WeModuleSite
{



    public function __construct()
    {
        global $_W;
        
    }


    public function getUuid() {

        $uuid = sprintf ( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand ( 0, 0xffff ), mt_rand ( 0, 0xffff ),
            // 16 bits for "time_mid"
            mt_rand ( 0, 0xffff ),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand ( 0, 0x0fff ) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant
            // DCE1.1
            mt_rand ( 0, 0x3fff ) | 0x8000,
            // 48 bits for "node"
            mt_rand ( 0, 0xffff ), mt_rand ( 0, 0xffff ), mt_rand ( 0, 0xffff ) );

        return $uuid;
    }


    /**
     * 缩略图
     * @param string $src
     * @param int $height
     * @param int $width
     * @return string
     */
    public function doCutImg($src = '', $height = 350, $width = 445)
    {

        global $_W;
        $src = urlencode($src);
        $cut = $_W['siteroot'] . 'thumbnail/timthumb.php?src=' . $src . '&h=' . $height . '&w=' . $width . '&zc=1';

        return $cut;
    }

    public function createAngularJsUrl($do, $query = array(), $noredirect = true)
    {
        global $_W;

        $addhost = true;
        $query['do'] = $do;
        $query['m'] = strtolower($this->modulename);
        return murl('entry', $query, $noredirect, $addhost);

        
    }

    public function doMobileCreateUrl()
    {

        $ApiGetAllCaseCate = $this->createAngularJsUrl('h5_step_01.inc');

        echo $ApiGetAllCaseCate;
        echo '<br/>';


    }


    //?检查是否登录
    private function checkAuth()
    {
        global $_W;
//        checkauth();
    }




}
