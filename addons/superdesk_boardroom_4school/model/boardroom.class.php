<?php

/**
* Created by PhpStorm.
* User: linjinyu
* Date: 6/19/17
* Time: 11:28 AM
*/

class boardroomModel
{

    public $table_name = "superdesk_boardroom_4school";

    public $table_column_all = "id,name,address,floor,traffic,lat,lng,thumb,basic,carousel,price,equipment,desk,chair,max_num,appointment_num,remark,cancle_rule,createtime,updatetime,enabled,uniacid,";

    /**
     * @param $params
     */
    public function insert($params)
    {
        global $_GPC, $_W;

        $params['uniacid'] = $_W['uniacid'];
        $params['createtime'] = strtotime('now');

        $ret = pdo_insert($this->table_name, $params);
        if (!empty($ret)) {
            $id = pdo_insertid();
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

    public function getMaxNumNumericalArrays($expect_people = 0){

        global $_GPC, $_W;//TIMESTAMP

        $where_sql .= " WHERE `uniacid` = :uniacid";
        $params = array(
            ':uniacid' => $_W['uniacid'],
        );

        $group_by_sql = " GROUP BY max_num ";
        $order_by_sql = " ORDER BY max_num ASC ";

        $list = pdo_fetchall(
            " SELECT max_num ".
            " FROM " . tablename($this->table_name) .
            $where_sql .
            $group_by_sql .
            $order_by_sql,$params);


        $start = 0 ;
        $end = 0;
        $total = sizeof($list);

        foreach ($list as $index => $_value_){
            if($expect_people > $_value_['max_num']){
                $start = $index;
            }

            if($start !=0 && $end == 0 && $expect_people < $_value_['max_num']){
                $end = $index;
            }
        }

        if ($end == 0) {
            return array("start" => $list[$start]['max_num'], "end" => 0);
        } elseif ($end != 0 && $end <= $total) {
            return array("start" => $list[$start]['max_num'], "end" => $list[$end]['max_num']);
        } else {
            return array("start" => 0, "end" => 0);
        }

    }

    /**
     * by web
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

        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_name) . $where_sql, $params);
        $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_name) . $where_sql . " ORDER BY id ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);
//        $pager = pagination($total, $page, $page_size);

        $pager = array();
        $pager['total'] = $total;
        $pager['page'] = $page;
        $pager['page_size'] = $page_size;
        $pager['data'] = $list;

        return $pager;

    }

    /**
     * by web
     * @param array $where
     * @param int   $page
     * @param int   $page_size
     *
     * @return array
     */
    public function queryAllByCoreUser($where = array(), $page = 0, $page_size = 50)
    {
        global $_GPC, $_W;//TIMESTAMP

        $page = max(1, intval($page));

        $where_sql .= " WHERE `uniacid` = :uniacid";
        $params = array(
            ':uniacid' => $_W['uniacid'],
        );

        $structures_parentid    = isset($where['structures_parentid']) ? intval($where['structures_parentid']) : 0 ;
        if ($structures_parentid != 0) {
            $where_sql .= " AND `structures_parentid` = :structures_parentid";
            $params[':structures_parentid'] = $structures_parentid;
        }

        $structures_childid     = isset($where['structures_childid']) ? intval($where['structures_childid']) : 0 ;
        if ($structures_childid != 0) {
            $where_sql .= " AND `structures_childid` = :structures_childid";
            $params[':structures_childid'] = $structures_childid;
        }

        $attribute              = isset($where['attribute']) ? intval($where['attribute']) : 0 ;
        if ($attribute != 0) {
            $where_sql .= " AND `attribute` = :attribute";
            $params[':attribute'] = $attribute;
        }

        $organization_code = isset($where['organization_code']) ? $where['organization_code'] : "" ;
        if (!empty($organization_code)) {
            $where_sql .= " AND `organization_code` = :organization_code ";
            $params[':organization_code'] = $organization_code;
        }

        $virtual_code = isset($where['virtual_code']) ? $where['virtual_code'] : "" ;
        if (!empty($virtual_code)) {
            $where_sql .= " AND `virtual_code` = :virtual_code ";
            $params[':virtual_code'] = $virtual_code;
        }

        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_name) . $where_sql, $params);
        $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_name) . $where_sql . " ORDER BY id ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);
//        $pager = pagination($total, $page, $page_size);

        $pager = array();
        $pager['total'] = $total;
        $pager['page'] = $page;
        $pager['page_size'] = $page_size;
        $pager['data'] = $list;

        return $pager;

    }

    public function queryByMobile($where = array(), $page = 0, $page_size = 50)
    {
        global $_GPC, $_W;//TIMESTAMP

        $page = max(1, intval($page));

        $where_sql .= " WHERE a.uniacid = :uniacid";
        $params = array(
            ':uniacid' => $_W['uniacid'],
        );

        $join_sql = "";
        $join_where_sql = "";

        $enabled = isset($where['enabled']) ? intval($where['enabled']) : 1 ;
        if ($enabled != 0) {
            $where_sql .= " AND a.enabled = :enabled";
            $params[':enabled'] = $enabled;
        }

        $structures_parentid    = isset($where['structures_parentid']) ? intval($where['structures_parentid']) : 0 ;
        if ($structures_parentid != 0) {
            $where_sql .= " AND a.structures_parentid = :structures_parentid";
            $params[':structures_parentid'] = $structures_parentid;
        }

        $structures_childid     = isset($where['structures_childid']) ? intval($where['structures_childid']) : 0 ;
        if ($structures_childid != 0) {
            $where_sql .= " AND a.structures_childid = :structures_childid";
            $params[':structures_childid'] = $structures_childid;
        }

        $attribute              = isset($where['attribute']) ? intval($where['attribute']) : 0 ;
        if ($attribute != 0) {
            $where_sql .= " AND a.attribute = :attribute";
            $params[':attribute'] = $attribute;
        }

        $organization_code      = isset($where['organization_code']) ? $where['organization_code'] : "" ;
        if (!empty($organization_code)) {
            $where_sql .= " AND a.organization_code = :organization_code ";
            $params[':organization_code'] = $organization_code;
        }

        $virtual_code           = isset($where['virtual_code']) ? $where['virtual_code'] : "" ;
        if (!empty($virtual_code)) {
            $where_sql .= " AND a.virtual_code = :virtual_code ";
            $params[':virtual_code'] = $virtual_code;
        }

        $expect_people          = isset($where['expect_people']) ? $where['expect_people'] : "" ;// var_dump($expect_people);
        if(is_array($expect_people) && sizeof($expect_people) > 0){
            if(!empty($expect_people['start'])){
                $where_sql .= " AND a.max_num >= :expect_people_start ";
                $params[':expect_people_start'] = intval($expect_people['start']);
            }
            if(!empty($expect_people['end'])){
                $where_sql .= " AND a.max_num <= :expect_people_end ";
                $params[':expect_people_end'] = intval($expect_people['end']);
            }
        }

        $equipment_tags         = isset($where['equipment_tags']) ? $where['equipment_tags'] : "" ;

        if(is_array($equipment_tags) && sizeof($equipment_tags) > 0){
            $join_sql .= " inner join " . tablename("superdesk_boardroom_4school_x_equipment") . " as b on a.id = b.boardroom_id";
            $__ex = "";
            foreach ($equipment_tags as $__index => $equipment_tag){

                if($__index != 0 ){
                    $__ex = " or ";
                }
                $join_where_sql .=  $__ex . " b.equipment_id = :equipment_id"."_".$__index." ";
                $params[':equipment_id_'.$__index] = $equipment_tag['value'];
            }
        }

        if(!empty($join_where_sql)){
            $join_where_sql = "and ( ". $join_where_sql. " )";
        }


//        echo "SELECT DISTINCT a.id, a.* FROM " . tablename($this->table_name) ." as a ". $join_sql . " " .$where_sql . $join_where_sql . " ORDER BY id ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size;

        $total = pdo_fetchcolumn("SELECT COUNT(DISTINCT a.id) FROM " . tablename($this->table_name) ." as a " . $join_sql . " ". $where_sql . $join_where_sql , $params);
        $list = pdo_fetchall("SELECT DISTINCT a.id, a.* FROM " . tablename($this->table_name) ." as a ". $join_sql . " " .$where_sql . $join_where_sql . " ORDER BY id ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);
//        $pager = pagination($total, $page, $page_size);

        $pager = array();
        $pager['total'] = $total;
        $pager['page'] = $page;
        $pager['page_size'] = $page_size;
        $pager['data'] = $list;

        return $pager;

    }
}