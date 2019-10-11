<?php

/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/03/30
 * Time: 15:24
 */
class balance_detail_processingModel
{

    public $table_name = "superdesk_jd_vop_balance_detail_processing";

    public $table_column_all = "id,processing,process_result";

    /**
     * @param $params
     */
    public function insert($params)
    {
        global $_GPC, $_W;

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

        $ret = pdo_update($this->table_name, $params, array('id' => $id));
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
     *
     * @param        $params
     * @param string $id
     */
    public function replace($params, $id = '')
    {
        global $_GPC, $_W;

//        pdo_insert($this->table_name, $params, true); // 这个是会全行替换的

        $_is_exist = $this->getOne($id);

        if (!$_is_exist) {
            $ret = pdo_insert($this->table_name, $params);
        }
    }

    /**
     * @param        $params
     * @param string $id
     */
    public function saveOrUpdate($params, $id = '')
    {
        global $_GPC, $_W;

        $_is_exist = $this->getOne($id);

        if (!$_is_exist) {
            $ret = pdo_insert($this->table_name, $params);
        } else {
            $ret = pdo_update($this->table_name, $params, array('id' => $id));
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

            $ret = pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = pdo_insertid();
            }

        } else {

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

        $where_sql = " WHERE 1 = 1 ";
        $params    = array();

        if (isset($where['processing'])) {
            $where_sql .= " AND `processing` = :processing ";
            $params[':processing'] = $where['processing'];
        }


        $total = pdo_fetchcolumn(
            " SELECT COUNT(id) " .
            " FROM " . tablename($this->table_name) .
            $where_sql,
            $params
        );

        $list = pdo_fetchall(
            " SELECT * " .
            " FROM " . tablename($this->table_name) .
            $where_sql .
            " ORDER BY id DESC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);

        socket_log(" SELECT * " .
            " FROM " . tablename($this->table_name) .
            $where_sql .
            " ORDER BY id DESC LIMIT " . ($page - 1) * $page_size . ',' . $page_size);

        socket_log(json_encode($params));

        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        return $pager;

    }

    /**
     * @param        $params
     * @param string $id
     */
    public function saveOrUpdateToId($params, $id = '')
    {
        global $_GPC, $_W;

        $_is_exist = $this->getOne($id);

        if (!$_is_exist) {
            $params['id'] = $id;
            $ret = pdo_insert($this->table_name, $params);
        } else {
            $ret = pdo_update($this->table_name, $params, array('id' => $id));
        }

    }
}