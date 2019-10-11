<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/07/25
 * Time: 17:47
 */



class enterprise_groupModel
{

    public $table_name = "superdesk_shop_enterprise_group";

    public $table_column_all = "id,uniacid,groupname,createtime,status,isdefault";

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

        $ret = pdo_update($this->table_name, $params, array('id' => $id));
    }

    /**
     * @param $params
     * @param $id
     */
    public function updateByColumn($params, $where)
    {
        global $_GPC, $_W;

        $ret = pdo_update($this->table_name, $params, $where);
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
     * @param $id
     * @return bool
     */
    public function deleteByColumn($params)
    {
        global $_GPC, $_W;
        if (empty($params)) {
            return false;
        }
        pdo_delete($this->table_name, $params);
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
            $ret = pdo_update($this->table_name, $params, array('id' => $id));
        }
        return $id;
    }

    /**
     * @param $params
     * @param array $column
     */
    public function saveOrUpdateByColumn($params, $column = array())
    {
        global $_GPC, $_W;

        $_is_exist = $this->getOneByColumn($column);

        $id = 0;
        // 如果没找到会返回 false
        if (!$_is_exist) {

            $params['uniacid'] = $_W['uniacid'];
            $params['createtime'] = strtotime('now');

            $ret = pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = pdo_insertid();
            }

        } else {

            $ret = pdo_update($this->table_name, $params, $column);

        }
        return $id;

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
    public function queryAll()
    {
        global $_GPC, $_W;//TIMESTAMP

        $pindex    = max(1, intval($_GPC['page']));
        $psize     = 20;
        $condition = ' and uniacid=:uniacid';
        $params    = array(':uniacid' => $_W['uniacid']);

        if ($_GPC['status'] != '') {
            $condition .= ' and status=' . intval($_GPC['status']);
        }


        if (!empty($_GPC['keyword'])) {
            $_GPC['keyword'] = trim($_GPC['keyword']);
            $condition .= ' and groupname  like :keyword';
            $params[':keyword'] = '%' . $_GPC['keyword'] . '%';
        }


        $list  = pdo_fetchall('SELECT * FROM ' . tablename($this->table_name) . ' WHERE 1 ' . $condition . '  ORDER BY isdefault desc, id DESC limit ' . (($pindex - 1) * $psize) . ',' . $psize, $params);
        $total = pdo_fetchcolumn('SELECT count(*) FROM ' . tablename($this->table_name) . ' WHERE 1 ' . $condition, $params);

        $pager = array();
        $pager['total'] = $total;
        $pager['page'] = $pindex;
        $pager['page_size'] = $psize;
        $pager['data'] = $list;

        return $pager;

    }

    /**
     * @param array $column
     * @return bool
     */
    public function getAllByWhere($where,$params)
    {
        global $_GPC, $_W;

        $result = pdo_fetchall(
            ' SELECT id,groupname ' .
            ' FROM ' . tablename($this->table_name) .
            ' WHERE ' . $where .
            ' ORDER BY id ASC',$params);

        return $result;

    }
}