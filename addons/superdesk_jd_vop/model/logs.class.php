<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2017/12/01
 * Time: 11:33
 */



class logsModel
{

    public $table_name = "superdesk_jd_vop_logs";

    public $table_column_all = "id,createtime,url,api,method,post_fields,curl_info,success,resultMessage,resultCode,result";

    /**
     * @param $params
     */
    public function insert($params)
    {
        global $_GPC, $_W;

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

        $where_sql = " WHERE 1 = 1 ";

        $api = isset($where['api']) ? $where['api'] : "" ;
        if (!empty($api)) {
            $where_sql .= " AND api = :api";
            $params[':api'] = $api;
        }

        if (isset($where['success'])){
            $where_sql .= " AND success = :success";
            $params[':success'] = $where['success'];
        }

        $start = isset($where['start']) ? $where['start'] : "";
        if (!empty($start)) {
            $where_sql .= " AND createtime >= :start ";
            $params[':start'] = $start;
        }

        $end = isset($where['end']) ? $where['end'] : "";
        if (!empty($end)) {
            $where_sql .= " AND createtime <= :end ";
            $params[':end'] = $end;
        }

        $total = pdo_fetchcolumn(
            " SELECT COUNT(id) ".
            " FROM " . tablename($this->table_name) .
            $where_sql,
            $params
        );

        $list = pdo_fetchall(
            " SELECT * ".
            " FROM " . tablename($this->table_name) .
            $where_sql .
            " ORDER BY id DESC LIMIT " . ($page - 1) * $page_size . ',' . $page_size,
            $params
        );

//        socket_log($where_sql);

        $pager = array();
        $pager['total'] = $total;
        $pager['page'] = $page;
        $pager['page_size'] = $page_size;
        $pager['data'] = $list;

        return $pager;

    }
}