<?php

defined('IN_IA') or exit('Access Denied');
session_start();
include 'model/common.func.php';

/**
 * Class Superdesk_coreModuleSite
 */
class Superdesk_coreModuleSite extends WeModuleSite
{


    public function __construct()
    {
        global $_W;


        // DEBUG 超级前台 openid
//        $_W['openid'] = "oX8KYwkxwNW6qzHF4cF-tGxYTcPg";

    }

    public function getMenus()
    {
        global $_W;

        /*
        <entry title="省市信息管理" do="provincecity" state="" direct="false"/>
        <entry title="项目信息管理" do="organization" state="" direct="false"/>
        <entry title="楼宇信息管理" do="build" state="" direct="false"/>
        <entry title="企业资料管理" do="virtualarchitecture" state="" direct="false"/>
        <entry title="数据字典配置" do="dictionary_group" state="" direct="false"/>
        <entry title="项目管理员" do="users" state="" direct="false"/>
        */
        return array(
            array('title' => '省市信息管理', 'icon' => 'fa fa-puzzle-piece', 'url' => $this->createWebUrl('provincecity', array('op' => 'list'))),
            array('title' => '项目信息管理', 'icon' => 'fa fa-puzzle-piece', 'url' => $this->createWebUrl('organization', array('op' => 'list'))),
            array('title' => '楼宇信息管理', 'icon' => 'fa fa-puzzle-piece', 'url' => $this->createWebUrl('build', array('op' => 'list'))),
            array('title' => '企业资料管理', 'icon' => 'fa fa-puzzle-piece', 'url' => $this->createWebUrl('virtualarchitecture', array('op' => 'list'))),
            array('title' => '数据字典配置', 'icon' => 'fa fa-puzzle-piece', 'url' => $this->createWebUrl('dictionary_group', array('op' => 'list'))),
            array('title' => '项目管理员', 'icon' => 'fa fa-puzzle-piece', 'url' => $this->createWebUrl('users', array('op' => 'list'))),

        );
    }


    public function createMyJsUrl($do, $query = array(), $m = '', $noredirect = true, $addhost = true)
    {
        global $_W;

        if (empty($m)) {
            $m = strtolower($this->modulename);
        }

        $query['do'] = $do;
        $query['m']  = $m;

        return murl('entry', $query, $noredirect, $addhost);
    }

    public function createMyWebUrl($do, $query = array(), $m = '')
    {
        global $_W;

        if (empty($m)) {
            $m = strtolower($this->modulename);
        }

        $query['do'] = $do;
        $query['m']  = $m;
        return wurl('site/entry', $query);

    }


    public function doMobileCreateUrl()
    {

        $ApiGetAllCaseCate = $this->createAngularJsUrl('h5_step_01.inc');

        echo $ApiGetAllCaseCate;
        echo '<br/>';


    }



}
