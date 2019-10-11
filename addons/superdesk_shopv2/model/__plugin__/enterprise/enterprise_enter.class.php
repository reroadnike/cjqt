<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/07/25
 * Time: 17:47
 */



class enterprise_enterModel
{

    public $table_name = "superdesk_shop_member_enter";

    public $table_column_all = "id,enter_id,enter_key,enter_uid,enter_balance,status,createtime,updatetime,expiretime,usedtime,enter_qrcode";

//`id` int(11) NOT NULL AUTO_INCREMENT,
//`enter_id` bigint(20) NOT NULL COMMENT '卡号',
//`enter_key` char(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '登录唯一标识',
//`enter_uid` int(11) NOT NULL COMMENT '绑定的用户ID',
//`enter_balance` decimal(10,0) NOT NULL COMMENT '卡面金额',
//`status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为未激活 1为激活',
//`createtime` int(11) NOT NULL,
//`updatetime` int(11) DEFAULT NULL COMMENT '更新时间',
//`expiretime` int(11) DEFAULT NULL COMMENT '过期时间',
//`usedtime` int(11) DEFAULT NULL COMMENT '第一次使用时间',
//`enter_qrcode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '进入二维码',

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
            $params['createtime'] = time();

            $ret = pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = pdo_insertid();
            }
        } else {
            $params['updatetime'] = time();
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
            $params['createtime'] = time();

            $ret = pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = pdo_insertid();
            }

        } else {

            $params['updatetime'] = time();
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
            $condition .= ' and ( a.enter_id like :keyword or m.realname like :keyword or a.enter_key like :keyword)';
            $params[':keyword'] = '%' . $keyword . '%';
        }

        if ($_GPC['status'] != '') {
            $condition .= ' and a.status=' . intval($_GPC['status']);
        }

        $total = pdo_fetchcolumn(
            ' select count(*) '.
            ' from ' . tablename($this->table_name) . ' as a' .
            ' left join ' . tablename('superdesk_shop_member') . 'as m on a.enter_uid = m.id ' .
            ' where a.uniacid=:uniacid and a.isdelete = 0 ' . $condition, $params);

        $sql =
            ' select a.*,m.id as mid, m.realname, m.mobile,m.credit2, m.nickname '.
            ' from ' . tablename($this->table_name) . ' as a' .
            ' left join ' . tablename('superdesk_shop_member') . 'as m on a.enter_uid = m.id ' .
            ' where a.uniacid=:uniacid and a.isdelete = 0 ' . $condition .
            ' ORDER BY a.id desc LIMIT ' . ($pindex - 1) * $psize.', '.$psize;
        $list  = pdo_fetchall($sql, $params);

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
            ' SELECT id,enter_id' .
            ' FROM ' . tablename($this->table_name) .
            ' WHERE ' . $where .
            ' ORDER BY id ASC',$params);

        return $result;

    }
}