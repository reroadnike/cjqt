<?php
/**
* Created by PhpStorm.
* User: linjinyu
* Date: 6/19/17
* Time: 11:28 AM
*/

class boardroom_appointmentModel
{

    public $table_name = "superdesk_boardroom_4school_appointment";

    public $table_column_all = "id,boardroom_id,openid,client_name,client_telphone,deleted,state,relate_id,people_num,createtime,updatetime,starttime,endtime,uniacid,";

    /**
     * @param $params
     *
     * @return bool
     */
    public function insert($params)
    {
        global $_GPC, $_W;

        $params['uniacid'] = $_W['uniacid'];
        $params['createtime'] = strtotime('now');

        $ret = pdo_insert($this->table_name, $params);
        if (!empty($ret)) {
            $id = pdo_insertid();
            return $id;
        }

        return false;

    }

    /**
     * @param $params
     * @param $id
     */
    public function update($params, $id)
    {
        global $_GPC, $_W;

        $params['updatetime'] = strtotime('now');
        $ret = pdo_update($this->table_name, $params, array('id' => $id));
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
        pdo_delete($this->table_name, array('id' => $id));
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

            $ret = pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = pdo_insertid();
            }
        } else {
            $params['updatetime'] = strtotime('now');
            $ret = pdo_update($this->table_name, $params, array('id' => $id));
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

            $ret = pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = pdo_insertid();
            }

        } else {

            $params['updatetime'] = strtotime('now');

            $ret = pdo_update($this->table_name, $params, $column);

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

        $result = pdo_get($this->table_name, array('id' => $id));

        return $result;

    }

    /**
     * @param array $column
     * @return bool
     */
    public function getOneByColumn($column = array())
    {
        global $_GPC, $_W;

        $result = pdo_get($this->table_name, $column);

        return $result;

    }

    /**k
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



        $out_trade_no = isset($where['out_trade_no']) ? $where['out_trade_no'] : "" ;
        if (!empty($out_trade_no)) {
            $where_sql .= " AND `out_trade_no` = :out_trade_no ";
            $params[':out_trade_no'] = $out_trade_no;
        }

        $client_name = isset($where['client_name']) ? $where['client_name'] : "" ;
        if (!empty($client_name)) {
            $where_sql .= " AND `client_name` like :client_name ";
            $params[':client_name'] = '%' . $client_name . '%';
        }

        $client_telphone = isset($where['client_telphone']) ? $where['client_telphone'] : "" ;
        if (!empty($client_telphone)) {
            $where_sql .= " AND `client_telphone` = :client_telphone ";
            $params[':client_telphone'] = $client_telphone;
        }





        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_name) . $where_sql, $params);
        $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_name) . $where_sql . " ORDER BY id DESC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);

        $pager = array();
        $pager['total'] = $total;
        $pager['page'] = $page;
        $pager['page_size'] = $page_size;
        $pager['data'] = $list;

        return $pager;

    }

    public function queryAllByCoreUser($where = array(), $page = 0, $page_size = 50)
    {
        global $_GPC, $_W;//TIMESTAMP

        $page = max(1, intval($page));

        $where_sql .= " WHERE a.`uniacid` = :uniacid";
        $params = array(
            ':uniacid' => $_W['uniacid'],
        );

        $status = isset($where['status']) ? $where['status'] : "" ;
        if (!empty($status)) {
            $where_sql .= " AND a.`status` = :status ";
            $params[':status'] = $status;
        }

        $out_trade_no = isset($where['out_trade_no']) ? $where['out_trade_no'] : "" ;
        if (!empty($out_trade_no)) {
            $where_sql .= " AND a.`out_trade_no` = :out_trade_no ";
            $params[':out_trade_no'] = $out_trade_no;
        }

        $client_name = isset($where['client_name']) ? $where['client_name'] : "" ;
        if (!empty($client_name)) {
            $where_sql .= " AND a.`client_name` like :client_name ";
            $params[':client_name'] = '%' . $client_name . '%';
        }

        $client_telphone = isset($where['client_telphone']) ? $where['client_telphone'] : "" ;
        if (!empty($client_telphone)) {
            $where_sql .= " AND a.`client_telphone` = :client_telphone ";
            $params[':client_telphone'] = $client_telphone;
        }

        $organization_code = isset($where['organization_code']) ? $where['organization_code'] : "" ;
        if (!empty($organization_code)) {
            $where_sql .= " AND a.`organization_code` = :organization_code ";
            $params[':organization_code'] = $organization_code;
        }

//        $virtual_code = isset($where['virtual_code']) ? $where['virtual_code'] : "" ;
//        if (!empty($virtual_code)) {
//            $where_sql .= " AND a.`virtual_code` = :virtual_code ";
//            $params[':virtual_code'] = $virtual_code;
//        }

        $start = isset($where['start']) ? strtotime($where['start']) : "" ;
        if (!empty($start)) {
            $where_sql .= " AND a.`createtime` >= :start ";
            $params[':start'] = $start;
        }

        $end = isset($where['end']) ? strtotime($where['end']) : "" ;
        if (!empty($end)) {
            $where_sql .= " AND a.`createtime` <= :end ";
            $params[':end'] = $end;
        }

        $subject = isset($where['subject']) ? $where['subject'] : "" ;
        if (!empty($subject)) {
            $where_sql .= " AND a.`subject` like :subject ";
            $params[':subject'] = '%' . $subject. '%';
        }


//        关联sql
        $inner_join_sql = " INNER JOIN ".tablename('superdesk_boardroom_4school')." AS b on b.id = a.boardroom_id ";

        $attribute = isset($where['attribute']) ? $where['attribute'] : "" ;

        if (!empty($attribute)) {
            $inner_join_sql .= " AND b.`attribute` = :attribute ";
            $params[':attribute'] = $attribute;
        }

        $structures_parentid = isset($where['structures_parentid']) ? $where['structures_parentid'] : "" ;
        if (!empty($structures_parentid)) {
            $inner_join_sql .= " AND b.`structures_parentid` = :structures_parentid ";
            $params[':structures_parentid'] = $structures_parentid;
        }

        $structures_childid = isset($where['structures_childid']) ? $where['structures_childid'] : "" ;
        if (!empty($structures_childid)) {
            $inner_join_sql .= " AND b.`structures_childid` = :structures_childid ";
            $params[':structures_childid'] = $structures_childid;
        }

//        echo "SELECT * FROM " . tablename($this->table_name) . $where_sql . " ORDER BY id DESC LIMIT " . ($page - 1) * $page_size . ',' . $page_size;


        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_name) . " AS a " . $inner_join_sql . $where_sql, $params);

        $list = pdo_fetchall("SELECT a.*,b.name as boardroom_name FROM " . tablename($this->table_name) . " AS a " . $inner_join_sql . $where_sql . " ORDER BY id DESC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);
//        var_dump("SELECT a.* FROM " . tablename($this->table_name) . " AS a " . $inner_join_sql . $where_sql . " ORDER BY id DESC LIMIT " . ($page - 1) * $page_size . ',' . $page_size);exit;

//var_dump($params);exit;

        $pager = array();
        $pager['total'] = $total;
        $pager['page'] = $page;
        $pager['page_size'] = $page_size;
        $pager['data'] = $list;

        return $pager;

    }






    public function queryAllByCoreUserExport($where = array())
    {
        global $_GPC, $_W;//TIMESTAMP

        $table_goods = 'superdesk_boardroom_4school_s_goods';
        $table_order_goods = 'superdesk_boardroom_4school_s_order_goods';

        $where_sql .= " WHERE a.`uniacid` = :uniacid";
        $params = array(
            ':uniacid' => $_W['uniacid'],
        );

        $status = isset($where['status']) ? $where['status'] : "" ;
        if (!empty($status)) {
            $where_sql .= " AND a.`status` = :status ";
            $params[':status'] = $status;
        }

        $start = isset($where['start']) ? strtotime($where['start']) : "" ;
        if (!empty($start)) {
            $where_sql .= " AND a.`createtime` >= :start ";
            $params[':start'] = $start;
        }

        $end = isset($where['end']) ? strtotime($where['end']) : "" ;
        if (!empty($end)) {
            $where_sql .= " AND a.`createtime` <= :end ";
            $params[':end'] = $end;
        }

        $out_trade_no = isset($where['out_trade_no']) ? $where['out_trade_no'] : "" ;
        if (!empty($out_trade_no)) {
            $where_sql .= " AND a.`out_trade_no` = :out_trade_no ";
            $params[':out_trade_no'] = $out_trade_no;
        }

        $client_name = isset($where['client_name']) ? $where['client_name'] : "" ;
        if (!empty($client_name)) {
            $where_sql .= " AND a.`client_name` like :client_name ";
            $params[':client_name'] = '%' . $client_name . '%';
        }

        $client_telphone = isset($where['client_telphone']) ? $where['client_telphone'] : "" ;
        if (!empty($client_telphone)) {
            $where_sql .= " AND a.`client_telphone` = :client_telphone ";
            $params[':client_telphone'] = $client_telphone;
        }


        $subject = isset($where['subject']) ? $where['subject'] : "" ;
        if (!empty($subject)) {
            $where_sql .= " AND a.`subject` like :subject ";
            $params[':subject'] = '%' . $subject. '%';
        }


        if (!empty($attribute)) {
            $where_sql .= " AND s.`attribute` = :attribute ";
            $params[':attribute'] = $end;
        }

        $structures_parentid = isset($where['structures_parentid']) ? $where['structures_parentid'] : "" ;
        if (!empty($structures_parentid)) {
            $where_sql .= " AND s.`structures_parentid` = :structures_parentid ";
            $params[':structures_parentid'] = $structures_parentid;
        }

        $structures_childid = isset($where['structures_childid']) ? $where['structures_childid'] : "" ;
        if (!empty($structures_childid)) {
            $where_sql .= " AND s.`structures_childid` = :structures_childid ";
            $params[':structures_childid'] = $structures_childid;
        }





        $list = pdo_fetchall("SELECT a.*,s.name as boardroom_name,s.address as boardroom_address,s.equipment as boardroom_equipment FROM " . tablename($this->table_name) ." AS a INNER JOIN ".tablename('superdesk_boardroom_4school')." AS s ON a.boardroom_id = s.id " . $where_sql . " ORDER BY id DESC ", $params);

        foreach($list as $key => $value){
//            设备处理
//            TODO 查出来的格式   {"value":13,"text":"屏风"},{"value":12,"text":"电脑"},{"value":6,"text":"电子屏"}
            $handleEquipmentStr = json_decode('['.htmlspecialchars_decode(unserialize($value['boardroom_equipment'])).']',true);
            $str = '';
            foreach($handleEquipmentStr as $k => $v){
                $str .= $v['text'].';';
            }
            $list[$key]['boardroom_equipment'] = $str;


            $sql = " SELECT g.title,og.total as buy_total FROM ".tablename($table_order_goods)." AS og INNER JOIN ".tablename($table_goods)." AS g ON g.id = og.goodsid WHERE g.uniacid = {$_W['uniacid']} AND og.out_trade_no = {$value['out_trade_no']} ";
            $list[$key]['order_goods'] = pdo_fetchall($sql);


        }


        return $list;

    }







    public function queryByMobile($where = array(), $page = 0, $page_size = 50)
    {
        global $_GPC, $_W;//TIMESTAMP

        $page = max(1, intval($page));

        $where_sql .= " WHERE `uniacid` = :uniacid";
        $params = array(
            ':uniacid' => $_W['uniacid'],
        );

//        $enabled = isset($where['enabled']) ? intval($where['enabled']) : 1 ;
//        if ($enabled != 0) {
//            $where_sql .= " AND `enabled` = :enabled";
//            $params[':enabled'] = $enabled;
//        }


        $openid = isset($where['openid']) ? $where['openid'] : "" ;
        if (!empty($openid)) {
            $where_sql .= " AND `openid` = :openid";
            $params[':openid'] = $openid;
        }

        $status = isset($where['status']) ? intval($where['status']) : 999 ;
//        echo $status;

//      '-1取消，0待审，1待审已付款，3已审'

        if($status == 999){

        } elseif ($status == -1 || $status == 0 || $status == 1) {
            $where_sql .= " AND `status` = :status";
            $params[':status'] = $status;
        } elseif ($status == 3) {
            $is_overdue = isset($where['is_overdue']) ? intval($where['is_overdue']) : 0;
            if ($is_overdue == 0) { //未使用
                $where_sql .= " AND `status` = :status";
                $params[':status'] = $status;
                // TODO
            } else {//$is_overdue == 1 已使用
                // TODO
                $where_sql .= " AND `status` = :status";
                $params[':status'] = $status;

                $where_sql .= " AND `endtime` < :now";
                $params[':now'] = time();


            }
        }




//        var_dump($params);

//        echo "SELECT * FROM " . tablename($this->table_name) . $where_sql . " ORDER BY createtime DESC LIMIT " . ($page - 1) * $page_size . ',' . $page_size;

        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_name) . $where_sql, $params);
        $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_name) . $where_sql . " ORDER BY createtime DESC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);

        $pager = array();
        $pager['total'] = $total;
        $pager['page'] = $page;
        $pager['page_size'] = $page_size;
        $pager['data'] = $list;

        return $pager;

    }
}