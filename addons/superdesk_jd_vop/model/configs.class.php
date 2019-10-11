<?php

/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2017/12/14
 * Time: 13:15
 */
class configsModel
{

    public $table_name = "superdesk_jd_vop_configs";

    public $table_column_all = "id,client_id,client_secret,username,password,is_default,craetetime,updatetime,title,deleted";

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
            $params['createtime'] = strtotime('now');

            $ret = pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = pdo_insertid();
            }
        } else {
            $params['updatetime'] = strtotime('now');
            $ret                  = pdo_update($this->table_name, $params, array('id' => $id));
        }

        return $id;

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

            $params['createtime'] = strtotime('now');

            $ret = pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = pdo_insertid();
            }

        } else {

            $params['updatetime'] = strtotime('now');

            $ret = pdo_update($this->table_name, $params, $column);

        }

        return $id;
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

        $where_sql = " WHERE 1";
        $params = array();

        if (isset($where['deleted'])){
            $where_sql .= " AND `deleted` = :deleted";
            $params[':deleted'] = $where['deleted'];
        }

        if (isset($where['is_default'])){
            $where_sql .= " AND `is_default` = :is_default";
            $params[':is_default'] = $where['is_default'];
        }

        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_name) . $where_sql, $params);
        $list  = pdo_fetchall(
            " SELECT * ".
            " FROM " . tablename($this->table_name) . $where_sql .
            " ORDER BY is_default DESC ,id ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);

        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        return $pager;

    }
}