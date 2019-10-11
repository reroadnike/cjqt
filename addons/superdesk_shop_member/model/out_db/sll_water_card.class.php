<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/11
 * Time: 17:02
 */

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/base_setting/SuperdeskShopMemberBaseOutDbModel.class.php');

class sll_water_cardModel extends SuperdeskShopMemberBaseOutDbModel
{

    public $table_name = "sll_water_card";

    public $table_column_all = "water_card_id,water_card_code,water_card_no,water_card_url,water_card_status,water_card_ctime,water_card_create_note_pk_code,water_card_pkCode,water_card_sellPrice,goods_id,user_id,number,proxy_price,base_price,sale_price,activate_id,activate_time,own_id,get_time,expire_time,assign_id,water_card_title";

    /**
     * @param $params
     */
    public function insert($params)
    {
        global $_GPC, $_W;

        $params['uniacid'] = $_W['uniacid'];
        $params['createtime'] = strtotime('now');

        $ret = $this->pdo_insert($this->table_name, $params);
        if (!empty($ret)) {
            $id = $this->pdo_insertid();
        }

    }

    /**
     * @param $params
     * @param $id
     */
    public function update($params, $id)
    {
        global $_GPC, $_W;

        $params['updatetime'] = strtotime('now');
        $ret = $this->pdo_update($this->table_name, $params, array('id' => $id));
    }


    /**
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        global $_GPC, $_W;
        if (empty($id)) {
            return false;
        }
        $this->pdo_delete($this->table_name, array('id' => $id));
    }

    /**
     * @param $params
     * @param string $id
     */
    public function saveOrUpdate($params, $id = '')
    {
        global $_GPC, $_W;

        if (empty($id)) {
            $params['uniacid'] = $_W['uniacid'];
            $params['createtime'] = strtotime('now');

            $ret = $this->pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = $this->pdo_insertid();
            }
        } else {
            $params['updatetime'] = strtotime('now');
            $ret = $this->pdo_update($this->table_name, $params, array('id' => $id));
        }

    }

    /**
     * @param $params
     * @param array $column
     */
    public function saveOrUpdateByColumn($params, $column = array())
    {
        global $_GPC, $_W;

        $_is_exist = $this->getOneByColumn($column);

        // 如果没找到会返回 false
        if (!$_is_exist) {

            $params['uniacid'] = $_W['uniacid'];
            $params['createtime'] = strtotime('now');

            $ret = $this->pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = $this->pdo_insertid();
            }

        } else {

            $params['updatetime'] = strtotime('now');

            $ret = $this->pdo_update($this->table_name, $params, $column);

        }

    }

    public function saveOrUpdateByJdVop($params , $sku)
    {
        global $_GPC, $_W;


        $column = array(
            "jd_vop_sku" => $sku
        );
        $_is_exist = $this->getOneByColumn($column);

        // 如果没找到会返回 false
        if (!$_is_exist) {

            $insert_data = array();

            // TODO
    $insert_data['water_card_id'] = '';// 水卡ID | NO | int(11) | 
    $insert_data['water_card_code'] = '';// 水卡序列码 | YES | varchar(255) | 
    $insert_data['water_card_no'] = '';// 水卡编号 | YES | varchar(255) | 
    $insert_data['water_card_url'] = '';// 水卡访问url | YES | varchar(255) | 
    $insert_data['water_card_status'] = '1';// 状态0失效1有效 | YES | int(11) | 1
    $insert_data['water_card_ctime'] = '';// 创建时间 | YES | datetime | 
    $insert_data['water_card_create_note_pk_code'] = '';// 水卡创建记录主键编码 | YES | varchar(255) | 
    $insert_data['water_card_pkCode'] = '';// 自定义主键标识 | YES | varchar(255) | 
    $insert_data['water_card_sellPrice'] = '';// 打印售价 | YES | float(10,2) | 
    $insert_data['goods_id'] = '';// 商品ID | YES | int(11) | 
    $insert_data['user_id'] = '';// 市级用户ID | YES | int(11) | 
    $insert_data['number'] = '';// 生成币数量 | YES | int(11) | 
    $insert_data['proxy_price'] = '';// 代理价 | YES | float(10,2) | 
    $insert_data['base_price'] = '';// 成本价 | YES | float(10,2) | 
    $insert_data['sale_price'] = '';// 零售价 | YES | float(10,2) | 
    $insert_data['activate_id'] = '';// 激会者粉丝ID | YES | int(100) | 
    $insert_data['activate_time'] = '';// 激会时间 | YES | datetime | 
    $insert_data['own_id'] = '';// 拥有者粉丝ID | YES | int(11) | 
    $insert_data['get_time'] = '';// 拥有时间 | YES | datetime | 
    $insert_data['expire_time'] = '';// 到期时间 | YES | datetime | 
    $insert_data['assign_id'] = '';// 分配的水店ID | YES | int(11) | 
    $insert_data['water_card_title'] = '';//  | YES | varchar(255) | 


            $insert_data['uniacid'] = $_W['uniacid'];
            $insert_data['createtime'] = strtotime('now');

            $ret = $this->pdo_insert($this->table_name, $insert_data);
            if (!empty($ret)) {
                $id = $this->pdo_insertid();
            }

        } else {

            $update_data = array();

            // TODO

            $update_data['updatetime'] = strtotime('now');

            $ret = $this->pdo_update($this->table_name, $update_data, $column);

        }

    }

    /**
     * @param $id
     * @return bool
     */
    public function getOne($id)
    {
        global $_GPC, $_W;

        if(empty($id)){
            return null;
        }

        $result = $this->pdo_get($this->table_name, array('id' => $id));

        return $result;

    }

    /**
     * @param array $column
     * @return bool
     */
    public function getOneByColumn($column = array())
    {
        global $_GPC, $_W;

        $result = $this->pdo_get($this->table_name, $column);

        return $result;

    }

    /**
     * @param array $where
     * @param int $page
     * @param int $page_size
     * @return array
     */
    public function queryAll($where = array(), $page = 0, $page_size = 50)
    {
        global $_GPC, $_W;//TIMESTAMP

        $page = max(1, intval($page));

        $where_sql .= " WHERE `uniacid` = :uniacid";
        $params = array(
            ':uniacid' => $_W['uniacid'],
        );

        $total = $this->pdo_fetchcolumn("SELECT COUNT(*) FROM " . $this->tablename($this->table_name) . $where_sql, $params);
        $list = $this->pdo_fetchall("SELECT * FROM " . $this->tablename($this->table_name) . $where_sql . " ORDER BY id ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);
//        $pager = pagination($total, $page, $page_size);

        $pager = array();
        $pager['total'] = $total;
        $pager['page'] = $page;
        $pager['page_size'] = $page_size;
        $pager['data'] = $list;

        return $pager;

    }
}