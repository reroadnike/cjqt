<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/07/25
 * Time: 17:47
 */



class enterprise_userModel
{

    public $table_name = "superdesk_shop_enterprise_user";

    public $table_column_all = "id,uniacid,regid,groupid,cateid,openid,enterpriseno,enterprisename,address,realname,mobile,status,accountid,accounttotal,accounttime,applytime,jointime,diyformdata,diyformfields,remark,sets,tel,lat,logo,lng,desc";

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
    public function queryAll()
    {
        global $_GPC, $_W;//TIMESTAMP

        $pindex    = max(1, intval($_GPC['page']));
        $psize     = 20;
        $params    = array(':uniacid' => $_W['uniacid']);

        $condition = '';

        $keyword   = trim($_GPC['keyword']);

        if (!empty($keyword)) {
            $condition .= ' and ( u.enterprise_name like :keyword or u.realname like :keyword or u.mobile like :keyword)';
            $params[':keyword'] = '%' . $keyword . '%';
        }
        if ($_GPC['groupid'] != '') {
            $condition .= ' and u.groupid=' . intval($_GPC['groupid']);
        }
        if ($_GPC['status'] != '') {
            $condition .= ' and u.status=' . intval($_GPC['status']);
        }
        if ($_GPC['status'] == '0') {
            $sortfield = 'u.applytime';
        } else {
            $sortfield = 'u.jointime';
        }

        $sql =
            ' select  u.*,g.groupname,a.username '.
            ' from ' . tablename($this->table_name) . '  u ' .
            ' left join  ' . tablename('superdesk_shop_enterprise_group') . ' g on u.groupid = g.id ' .
            ' left join  ' . tablename('superdesk_shop_enterprise_account') . ' a on u.accountid = a.id ' .// TODO 标志 楼宇之窗 openid shop_enterprise_account 不处理
            ' where u.uniacid=:uniacid ' . $condition .
            ' ORDER BY ' . $sortfield . ' desc';

        $list  = pdo_fetchall($sql, $params);
        $total = pdo_fetchcolumn(
            ' select count(*) '.
            ' from' . tablename($this->table_name) . ' u  ' .
            ' left join  ' . tablename('superdesk_shop_enterprise_group') . ' g on u.groupid = g.id ' .
            ' where u.uniacid = :uniacid ' . $condition, $params);

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
            ' SELECT id,enterprise_name ' .
            ' FROM ' . tablename($this->table_name) .
            ' WHERE ' . $where .
            ' ORDER BY id ASC',$params);

        return $result;

    }
}