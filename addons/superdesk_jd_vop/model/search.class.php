<?php

/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2017/11/13
 * Time: 17:18
 */
class searchModel
{

    public $table_name = "superdesk_jd_vop_search";

    public $table_column_all = "openid,has_keyword,keyword,has_pricebetween,minprice,maxprice,has_filter,filters,has_cate,cate,has_order,order_by,createtime";

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
        $ret                  = pdo_update($this->table_name, $params, array('id' => $id));
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
    public function queryAll($where = array(), $page = 0, $page_size = 10000)
    {
        global $_GPC, $_W;//TIMESTAMP

        $page = max(1, intval($page));

        $where_sql = " WHERE 1 ";
        $params = array();

        if (!empty($where['createtime'])) {
            $starttime = strtotime($where['createtime']['start']);
            $endtime   = strtotime($where['createtime']['end']);
            $where_sql .= ' AND createtime >= :starttime AND createtime <= :endtime ';
            $params[':starttime'] = $starttime;
            $params[':endtime']   = $endtime;
        }

        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_name) . $where_sql, $params);
        $list  = pdo_fetchall("SELECT * FROM " . tablename($this->table_name) . $where_sql . " ORDER BY createtime DESC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);
//        $pager = pagination($total, $page, $page_size);

        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        return $pager;

    }

    /**
     * @param array $where
     * @param int   $page
     * @param int   $page_size
     *
     * @return array
     */
    public function queryAllLeftJoin($where = array(), $page = 0, $page_size = 10000)
    {
        global $_GPC, $_W;//TIMESTAMP

        $page = max(1, intval($page));

        $where_sql = " WHERE 1 ";
        $params = array();

        if (!empty($where['createtime'])) {
            $starttime = strtotime($where['createtime']['start']);
            $endtime   = strtotime($where['createtime']['end']);
            $where_sql .= ' AND createtime >= :starttime AND createtime <= :endtime ';
            $params[':starttime'] = $starttime;
            $params[':endtime']   = $endtime;
        }
		
		$where_sql .=' AND keyword !=" " ';

        $total = pdo_fetchcolumn(
            "SELECT COUNT(*) FROM " . tablename($this->table_name) . $where_sql,
            $params
        );

        $list  = pdo_fetchall(
            "SELECT s.*,u.realname,c.name as category_name FROM " . tablename($this->table_name) . ' as s ' .
			"LEFT JOIN " . tablename('superdesk_shop_member') . ' as u on u.openid = s.openid ' .
            "LEFT JOIN " . tablename('superdesk_shop_category') . ' as c on c.id = s.cate ' .

            $where_sql .
            " ORDER BY createtime DESC LIMIT " . ($page - 1) * $page_size . ',' . $page_size,
            $params
        );

//        $pager = pagination($total, $page, $page_size);

        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        return $pager;

    }


}