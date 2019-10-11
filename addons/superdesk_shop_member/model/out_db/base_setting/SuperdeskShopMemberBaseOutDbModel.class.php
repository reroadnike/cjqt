<?php

//namespace superdesk_shop_member\model\out_db\base_setting;

/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 1/8/18
 * Time: 5:27 PM
 */
class SuperdeskShopMemberBaseOutDbModel
{

    function pdo() {
        global $_W;
        static $db_model_shop_member;
        if(empty($db_model_shop_member)) {
//            if($_W['config']['db']['slave_status'] == true && !empty($_W['config']['db']['slave'])) {
//                load()->classs('slave.db');
//                $db = new SlaveDb('master');
//            } else {
//                load()->classs('db');
//                if(empty($_W['config']['db']['master'])) {
//                    $_W['config']['db']['master'] = $GLOBALS['_W']['config']['db'];
//                    $db = new DB($_W['config']['db']);
//                } else {
//                    $db = new DB('master');
//                }
//            }

//            $database_config_shop_member = array(
//                'host' => '127.0.0.1', //数据库IP或是域名
//                'username' => 'root', // 数据库连接用户名
//                'password' => 'root@2016', // 数据库连接密码
//                'database' => 'membershop', // 数据库名
//                'port' => 3306, // 数据库连接端口
//                'tablepre' => '', // 表前缀，如果没有前缀留空即可
//                'charset' => 'utf8', // 数据库默认编码
//                'pconnect' => 0, // 是否使用长连接
//            );

            $db_model_shop_member = new DB('shop_member');
        }
        return $db_model_shop_member;
    }

    function tablename($tablename){
        return $tablename;
    }


    function pdo_query($sql, $params = array()) {
        return $this->pdo()->query($sql, $params);
    }


    function pdo_fetchcolumn($sql, $params = array(), $column = 0) {
        return $this->pdo()->fetchcolumn($sql, $params, $column);
    }

    function pdo_fetch($sql, $params = array()) {
        return $this->pdo()->fetch($sql, $params);
    }

    function pdo_fetchall($sql, $params = array(), $keyfield = '') {
        return $this->pdo()->fetchall($sql, $params, $keyfield);
    }


    function pdo_get($tablename, $condition = array(), $fields = array()) {
        return $this->pdo()->get($tablename, $condition, $fields);
    }

    function pdo_getall($tablename, $condition = array(), $fields = array(), $keyfield = '') {
        return $this->pdo()->getall($tablename, $condition, $fields, $keyfield);
    }

    function pdo_getslice($tablename, $condition = array(), $limit = array(), &$total = null, $fields = array(), $keyfield = '') {
        return $this->pdo()->getslice($tablename, $condition, $limit, $total, $fields, $keyfield);
    }


    function pdo_update($table, $data = array(), $params = array(), $glue = 'AND') {
        return $this->pdo()->update($table, $data, $params, $glue);
    }


    function pdo_insert($table, $data = array(), $replace = FALSE) {
        return $this->pdo()->insert($table, $data, $replace);
    }


    function pdo_delete($table, $params = array(), $glue = 'AND') {
        return $this->pdo()->delete($table, $params, $glue);
    }


    function pdo_insertid() {
        return $this->pdo()->insertid();
    }


    function pdo_begin() {
        $this->pdo()->begin();
    }


    function pdo_commit() {
        $this->pdo()->commit();
    }


    function pdo_rollback() {
        $this->pdo()->rollBack();
    }


    function pdo_debug($output = true, $append = array()) {
        return $this->pdo()->debug($output, $append);
    }

    function pdo_run($sql) {
        return $this->pdo()->run($sql);
    }


    function pdo_fieldexists($tablename, $fieldname = '') {
        return $this->pdo()->fieldexists($tablename, $fieldname);
    }


    function pdo_indexexists($tablename, $indexname = '') {
        return $this->pdo()->indexexists($tablename, $indexname);
    }


    function pdo_fetchallfields($tablename){
        $fields = $this->pdo_fetchall("DESCRIBE {$tablename}", array(), 'Field');
        $fields = array_keys($fields);
        return $fields;
    }


    function pdo_tableexists($tablename){
        return $this->pdo()->tableexists($tablename);
    }

}