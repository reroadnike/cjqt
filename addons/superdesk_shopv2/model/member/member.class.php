<?php

/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 6/19/17
 * Time: 11:28 AM
 */
class memberModel
{

    public $table_name = "superdesk_shop_member";

    public $table_column_all =
        "id,uniacid,uid,groupid,level,agentid,openid,core_user,realname,mobile,pwd,weixin,content,createtime,agenttime,status,isagent,clickcount,agentlevel,noticeset,nickname,credit1,credit2,birthyear,birthmonth,birthday,gender,avatar,province,city,area,childtime,agentnotupgrade,inviter,agentselectgoods,agentblack,username,fixagentid,diymemberid,diymemberdataid,diymemberdata,diycommissionid,diycommissiondataid,diycommissiondata,isblack,diymemberfields,diycommissionfields,commission_total,endtime2,ispartner,partnertime,partnerstatus,partnerblack,partnerlevel,partnernotupgrade,diyglobonusid,diyglobonusdata,diyglobonusfields,isaagent,aagentlevel,aagenttime,aagentstatus,aagentblack,aagentnotupgrade,aagenttype,aagentprovinces,aagentcitys,aagentareas,diyaagentid,diyaagentdata,diyaagentfields,salt,mobileverify,mobileuser,carrier_mobile,isauthor,authortime,authorstatus,authorblack,authorlevel,authornotupgrade,diyauthorid,diyauthordata,diyauthorfields,authorid,comefrom,openid_qq,openid_wx,core_enterprise";

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

        $params['updatetime'] = strtotime('now');
        $ret                  = pdo_update($this->table_name, $params, array('id' => $id));
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

    public function getOneByMobile($mobile)
    {

        $where = array(
            'mobile' => $mobile
        );

        return $this->getOneByColumn($where);

    }

    public function getOneByOpenid($openid)
    {

        $where = array(
            'openid' => $openid
        );

        return $this->getOneByColumn($where);

    }

    public function getOneByCoreUser($core_user)
    {
        $where = array(
            'core_user' => $core_user
        );

        return $this->getOneByColumn($where);
    }

    public function getSalt()
    {
        $salt = random(16);
        while (1) {

            $count = pdo_fetchcolumn(
                ' select count(*) ' .
                ' from ' . tablename('superdesk_shop_member') .
                ' where salt=:salt limit 1',
                array(':salt' => $salt)
            );

            if ($count <= 0) {
                break;
            }

            $salt = random(16);
        }

        return $salt;
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
        $params    = array(
            ':uniacid' => $_W['uniacid'],
        );

        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_name) . $where_sql, $params);
        $list  = pdo_fetchall("SELECT * FROM " . tablename($this->table_name) . $where_sql . " ORDER BY id ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);
//        $pager = pagination($total, $page, $page_size);

        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        return $pager;

    }
}