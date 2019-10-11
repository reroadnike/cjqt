<?php

/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2017/12/14
 * Time: 13:15
 */
class page_numModel
{

    public $table_name = "superdesk_jd_vop_page_num";

    public $table_column_all = "id,uniacid,page_num,name,state,deleted,createtime,updatetime";

    /**
     * @param $params
     */
    public function insert($params)
    {
        global $_GPC, $_W;

        $params['uniacid']    = $_W['uniacid'];
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

//        $params['updatetime'] = strtotime('now');
        $ret = pdo_update($this->table_name, $params, array('id' => $id));
    }

    public function updateByColumn($params, $column = array())
    {
        global $_GPC, $_W;


        $ret = pdo_update($this->table_name, $params, $column);


    }

    /**
     * @param $id
     *
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
     * @param        $params
     * @param string $id
     */
    public function saveOrUpdate($params, $id = '')
    {
        global $_GPC, $_W;

        if (empty($id)) {
            $params['uniacid']    = $_W['uniacid'];
            $params['createtime'] = strtotime('now');

            $ret = pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = pdo_insertid();
            }
        } else {
            $params['updatetime'] = strtotime('now');
            $ret                  = pdo_update($this->table_name, $params, array('id' => $id));
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

            $params['uniacid']    = $_W['uniacid'];
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

    public function saveOrUpdateByJdVop($params, $page_num)
    {
        global $_GPC, $_W;


        $column    = array(
            "page_num" => $page_num
        );

        $_is_exist = $this->getOneByColumn($column);

        // 如果没找到会返回 false
        if (!$_is_exist) {


//            $params['page_num'] = '';// page_num | NO | int(11) |
//            $params['name'] = '';// 商品池名字 | NO | varchar(128) |
            $params['state'] = '1';// state | NO | tinyint(3) | 1


            $params['uniacid']    = $_W['uniacid'];
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
     *
     * @return bool
     */
    public function getOne($id)
    {
        global $_GPC, $_W;

        if (empty($id)) {
            return null;
        }

        $result = pdo_get($this->table_name, array('id' => $id));

        return $result;

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

        $where_sql .= " WHERE `uniacid` = :uniacid";
        $params = array(
            ':uniacid' => $_W['uniacid'],
        );

        if (isset($where['deleted'])){
            $where_sql .= " AND `deleted` = :deleted";
            $params[':deleted'] = $where['deleted'];
        }

        if (isset($where['state'])){
            $where_sql .= " AND `state` = :state";
            $params[':state'] = $where['state'];
        }

        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_name) . $where_sql, $params);
        $list  = pdo_fetchall(
            " SELECT * ".
            " FROM " . tablename($this->table_name) . $where_sql .
            " ORDER BY state DESC ,deleted ASC,id ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);

        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        return $pager;

    }

    public function queryByCronComparison($where = array())
    {

        global $_GPC, $_W;

        $where_sql .= " WHERE `uniacid` = :uniacid";
        $params = array(
            ':uniacid' => $_W['uniacid']
        );

        if(isset($where['deleted'])){
            $where_sql .= " AND `deleted` = :deleted";
            $params[':deleted'] = intval($where['deleted']);
        }

        if(isset($where['state'])){
            $where_sql .= " AND `state` = :state";
            $params[':state'] = intval($where['state']);
        }


        $list = pdo_fetchall(
            " SELECT page_num,name " .
            " FROM " . tablename($this->table_name) . $where_sql .
            " ORDER BY id ASC ", $params);

        return $list;
    }

    public function zTreeV3Data($where = array(), $page = 0, $page_size = 5000)
    {

        global $_GPC, $_W;//TIMESTAMP

        $page = max(1, intval($page));

        $where_sql = "";
        $where_sql .= " WHERE `uniacid` = :uniacid";
        $params = array(
            ':uniacid' => $_W['uniacid'],
        );

        if(isset($where['deleted'])){
            $where_sql .= " AND `deleted` = :deleted";
            $params[':deleted'] = intval($where['deleted']);
        }

        if(isset($where['state'])){
            $where_sql .= " AND `state` = :state";
            $params[':state'] = intval($where['state']);
        }

        $list = pdo_fetchall(
            " SELECT id, 0 as pId, name, page_num " .
            " FROM " . tablename($this->table_name) .
            $where_sql .
            " ORDER BY state DESC ,deleted ASC,id ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);

//        foreach ($list as &$item) {
//        }

        return json_encode($list, JSON_UNESCAPED_UNICODE);//, JSON_PRETTY_PRINT |
    }
}