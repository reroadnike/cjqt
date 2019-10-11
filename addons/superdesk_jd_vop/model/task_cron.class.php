<?php

/**
 * 计划任务
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2017/12/22
 * Time: 16:05
 */
class task_cronModel
{

    // 京东 start
    const _cron_handle_task_00 = '_cron_handle_task_00';
    const _cron_handle_task_01_page_num = '_cron_handle_task_01_page_num';
    const _cron_handle_task_02_sku_4_page_num = '_cron_handle_task_02_sku_4_page_num';
    const _cron_handle_task_03_sku_detail = '_cron_handle_task_03_sku_detail';
    const _cron_handle_task_04_sku_price_update_100 = '_cron_handle_task_04_sku_price_update_100';

    const _cron_handle_task_66_checking_order = '_cron_handle_task_66_checking_order';
    const _cron_handle_task_67_checking_order_state = '_cron_handle_task_67_checking_order_state';

    const _cron_handle_task_90_sync_organization_incrememt = '_cron_handle_task_90_sync_organization_incrememt';
    const _cron_handle_task_91_sync_virtualarchitecture_incrememt = '_cron_handle_task_91_sync_virtualarchitecture_incrememt';
    const _cron_handle_task_92_sync_th_user_increment = '_cron_handle_task_92_sync_th_user_increment';

    const _cron_handle_task_100_sync_jd_balance_detail = '_cron_handle_task_100_sync_jd_balance_detail';
    const _cron_handle_task_101_process_jd_balance_detail = '_cron_handle_task_101_process_jd_balance_detail';

    const _cron_handle_task_777_clean_cache_detail_overwrite_0 = '_cron_handle_task_777_clean_cache_detail_overwrite_0';

    const _cron_handle_task_1001_message_get_type_1 = '_cron_handle_task_1001_message_get_type_1';
    const _cron_handle_task_1002_message_get_type_2 = '_cron_handle_task_1002_message_get_type_2';
    const _cron_handle_task_1004_message_get_type_4 = '_cron_handle_task_1004_message_get_type_4';
    const _cron_handle_task_1005_message_get_type_5 = '_cron_handle_task_1005_message_get_type_5';
    const _cron_handle_task_1006_message_get_type_6 = '_cron_handle_task_1006_message_get_type_6';
    const _cron_handle_task_1010_message_get_type_10 = '_cron_handle_task_1010_message_get_type_10';
    const _cron_handle_task_1012_message_get_type_12 = '_cron_handle_task_1012_message_get_type_12';
    const _cron_handle_task_1013_message_get_type_13 = '_cron_handle_task_1013_message_get_type_13';
    const _cron_handle_task_1014_message_get_type_14 = '_cron_handle_task_1014_message_get_type_14';
    const _cron_handle_task_1015_message_get_type_15 = '_cron_handle_task_1015_message_get_type_15';
    const _cron_handle_task_1016_message_get_type_16 = '_cron_handle_task_1016_message_get_type_16';
    const _cron_handle_task_1017_message_get_type_17 = '_cron_handle_task_1017_message_get_type_17';
    const _cron_handle_task_1025_message_get_type_25 = '_cron_handle_task_1025_message_get_type_25';
    const _cron_handle_task_1050_message_get_type_50 = '_cron_handle_task_1050_message_get_type_50';
    // 京东 end

    // 商城 start

    const superdesk_shop_goods_get_total_ajax = 'superdesk_shop_goods_get_total_ajax';

    // 商城 end


    public $table_name = "superdesk_jd_vop_task_cron";

    public $table_column_all = "name,orde,file,no,desc,freq,lastdo,log";

    /**
     * @param $params
     */
    public function insert($params)
    {
        global $_GPC, $_W;

//        $params['uniacid']    = $_W['uniacid'];
//        $params['createtime'] = strtotime('now');

        $ret = pdo_insert($this->table_name, $params);
        if (!empty($ret)) {
            $id = pdo_insertid();
        }

    }

    /**
     * @param $params
     * @param $id
     */
    public function update($params, $name)
    {
        global $_GPC, $_W;

        $ret = pdo_update($this->table_name, $params, array('name' => $name));
    }

    public function updateLastdo($name)
    {

        global $_GPC, $_W;

        $ret = pdo_update($this->table_name, array(
            'lastdo' => strtotime('now')
        ), array('name' => $name));

    }


    /**
     * @param $id
     *
     * @return bool
     */
    public function delete($name)
    {
        global $_GPC, $_W;
        if (empty($name)) {
            return false;
        }
        pdo_delete($this->table_name, array('name' => $name));
    }

    /**
     * @param        $params
     * @param string $id
     */
    public function saveOrUpdate($params, $name = '')
    {
        global $_GPC, $_W;

        $_is_exist = $this->getOne($name);

        if (!$_is_exist) {
            $ret = pdo_insert($this->table_name, $params);
        } else {
            $ret = pdo_update($this->table_name, $params, array('name' => $name));
        }

    }

    /**
     * @param       $params
     * @param array $column
     */
    public function saveOrUpdateByColumn($params, $column = array())
    {
        global $_GPC, $_W;

        $_is_exist = $this->getOneByColumn($column);

        // 如果没找到会返回 false
        if (!$_is_exist) {

//            $params['uniacid']    = $_W['uniacid'];
//            $params['createtime'] = strtotime('now');

            $ret = pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = pdo_insertid();
            }

        } else {

            $params['updatetime'] = strtotime('now');

            $ret = pdo_update($this->table_name, $params, $column);

        }

    }

    public function saveOrUpdateByJdVop($params, $sku)
    {
        global $_GPC, $_W;


        $column    = array(
            "jd_vop_sku" => $sku
        );
        $_is_exist = $this->getOneByColumn($column);

        // 如果没找到会返回 false
        if (!$_is_exist) {

            $insert_data = array();

            // TODO
            $insert_data['name']   = '';//  | NO | varchar(40) |
            $insert_data['orde']   = '0';//  | NO | int(10) | 0
            $insert_data['file']   = '';//  | NO | varchar(100) |
            $insert_data['no']     = '0';//  | NO | tinyint(1) unsigned | 0
            $insert_data['desc']   = '';//  | YES | text |
            $insert_data['freq']   = '0';//  | NO | int(10) | 0
            $insert_data['lastdo'] = '0';//  | NO | int(11) unsigned | 0
            $insert_data['log']    = '';//  | YES | text |


            $insert_data['uniacid']    = $_W['uniacid'];
            $insert_data['createtime'] = strtotime('now');

            $ret = pdo_insert($this->table_name, $insert_data);
            if (!empty($ret)) {
                $id = pdo_insertid();
            }

        } else {

            $update_data = array();

            // TODO

            $update_data['updatetime'] = strtotime('now');

            $ret = pdo_update($this->table_name, $update_data, $column);

        }

    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function getOne($name)
    {
        global $_GPC, $_W;

        if (empty($name)) {
            return null;
        }

        $result = pdo_get($this->table_name, array('name' => $name));

        return $result;

    }


    /**
     * 是否忽略
     *
     * @param $name
     *
     * @return bool 返回true 不执行任务
     */
    public function isIgnore($name)
    {

//        $_is_ignore = true;

        $item = $this->getOne($name);

        if (empty($item)) {
            return 1;
        } else {
            return $item['no'];//0 运行 1 忽略
        }
    }

    /**
     * @param array $column
     *
     * @return bool
     */
    public function getOneByColumn($column = array())
    {
        global $_GPC, $_W;

        $result = pdo_get($this->table_name, $column);

        return $result;

    }

    /**
     * @param array $where
     * @param int   $page
     * @param int   $page_size
     *
     * @return array
     */
    public function queryAll($where = array(), $page = 0, $page_size = 50)
    {
        global $_GPC, $_W;//TIMESTAMP

        $page = max(1, intval($page));


        $where_sql = '';
//        $where_sql .= " WHERE `uniacid` = :uniacid";
//        $params = array(
//            ':uniacid' => $_W['uniacid'],
//        );

        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_name) . $where_sql, $params);
        $list  = pdo_fetchall("SELECT * FROM " . tablename($this->table_name) . $where_sql . " ORDER BY orde ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);
//        $pager = pagination($total, $page, $page_size);

        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        return $pager;

    }
}