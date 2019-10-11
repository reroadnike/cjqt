<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/11
 * Time: 17:02
 */

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/base_setting/SuperdeskShopMemberBaseOutDbModel.class.php');

class wx_usersModel extends SuperdeskShopMemberBaseOutDbModel
{

    public $table_name = "wx_users";

    public $table_column_all = "id,openid,agentid,inviter,gid,username,mp,smscount,password,email,createtime,lasttime,status,createip,lastip,diynum,activitynum,card_num,card_create_status,money,moneybalance,spend,viptime,connectnum,lastloginmonth,attachmentsize,wechat_card_num,serviceUserNum,invitecode,remark,business,usertplid,sysuser,wxuserid,parent_id,province,city";

    /**
     * @param $params
     */
    public function insert($params)
    {
        global $_GPC, $_W;

        $params['uniacid'] = $_W['uniacid'];
        $params['createtime'] = strtotime('now');

        $ret = $this->pdo_insert($this->table_name, $params);
        if (!empty($ret)) {
            $id = $this->pdo_insertid();
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
        $ret = $this->pdo_update($this->table_name, $params, array('id' => $id));
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
        $this->pdo_delete($this->table_name, array('id' => $id));
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

            $ret = $this->pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = $this->pdo_insertid();
            }
        } else {
            $params['updatetime'] = strtotime('now');
            $ret = $this->pdo_update($this->table_name, $params, array('id' => $id));
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

            $ret = $this->pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = $this->pdo_insertid();
            }

        } else {

            $params['updatetime'] = strtotime('now');

            $ret = $this->pdo_update($this->table_name, $params, $column);

        }

    }

    public function saveOrUpdateByJdVop($params , $sku)
    {
        global $_GPC, $_W;


        $column = array(
            "jd_vop_sku" => $sku
        );
        $_is_exist = $this->getOneByColumn($column);

        // 如果没找到会返回 false
        if (!$_is_exist) {

            $insert_data = array();

            // TODO
    $insert_data['openid'] = '';//  | YES | varchar(80) | 
    $insert_data['agentid'] = '0';//  | YES | int(10) | 0
    $insert_data['inviter'] = '0';//  | YES | int(10) | 0
    $insert_data['gid'] = '';//  | YES | int(11) | 
    $insert_data['username'] = '';//  | YES | varchar(60) | 
    $insert_data['mp'] = '';//  | YES | varchar(11) | 
    $insert_data['smscount'] = '0';//  | YES | int(10) | 0
    $insert_data['password'] = '';//  | YES | varchar(100) | 
    $insert_data['email'] = '';//  | YES | varchar(90) | 
    $insert_data['lasttime'] = '';//  | YES | datetime | 
    $insert_data['status'] = '';// 0:拉黑1:正常 | YES | varchar(10) | 
    $insert_data['createip'] = '';//  | YES | varchar(30) | 
    $insert_data['lastip'] = '';//  | YES | varchar(30) | 
    $insert_data['diynum'] = '';//  | YES | int(11) | 
    $insert_data['activitynum'] = '';//  | YES | int(11) | 
    $insert_data['card_num'] = '';//  | YES | int(11) | 
    $insert_data['card_create_status'] = '';//  | YES | tinyint(1) | 
    $insert_data['money'] = '';//  | YES | int(11) | 
    $insert_data['moneybalance'] = '0';//  | YES | int(10) | 0
    $insert_data['spend'] = '0';//  | YES | int(5) | 0
    $insert_data['viptime'] = '';//  | YES | datetime | 
    $insert_data['connectnum'] = '0';//  | YES | int(11) | 0
    $insert_data['lastloginmonth'] = '0';//  | YES | smallint(2) | 0
    $insert_data['attachmentsize'] = '0';//  | YES | int(10) | 0
    $insert_data['wechat_card_num'] = '';//  | YES | int(3) | 
    $insert_data['serviceUserNum'] = '';//  | YES | tinyint(3) | 
    $insert_data['invitecode'] = '';//  | YES | varchar(6) | 
    $insert_data['remark'] = '';//  | YES | varchar(200) | 
    $insert_data['business'] = 'other';//  | YES | char(20) | other
    $insert_data['usertplid'] = '0';//  | YES | tinyint(4) | 0
    $insert_data['sysuser'] = '';//  | YES | int(11) | 
    $insert_data['wxuserid'] = '';//  | YES | int(11) | 
    $insert_data['parent_id'] = '0';//  | YES | int(11) | 0
    $insert_data['province'] = '';//  | YES | varchar(255) | 
    $insert_data['city'] = '';//  | YES | varchar(255) | 


            $insert_data['uniacid'] = $_W['uniacid'];
            $insert_data['createtime'] = strtotime('now');

            $ret = $this->pdo_insert($this->table_name, $insert_data);
            if (!empty($ret)) {
                $id = $this->pdo_insertid();
            }

        } else {

            $update_data = array();

            // TODO

            $update_data['updatetime'] = strtotime('now');

            $ret = $this->pdo_update($this->table_name, $update_data, $column);

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

        $result = $this->pdo_get($this->table_name, array('id' => $id));

        return $result;

    }

    /**
     * @param array $column
     * @return bool
     */
    public function getOneByColumn($column = array())
    {
        global $_GPC, $_W;

        $result = $this->pdo_get($this->table_name, $column);

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

        $where_sql .= " WHERE `uniacid` = :uniacid";
        $params = array(
            ':uniacid' => $_W['uniacid'],
        );

        $total = $this->pdo_fetchcolumn("SELECT COUNT(*) FROM " . $this->tablename($this->table_name) . $where_sql, $params);
        $list = $this->pdo_fetchall("SELECT * FROM " . $this->tablename($this->table_name) . $where_sql . " ORDER BY id ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);
//        $pager = pagination($total, $page, $page_size);

        $pager = array();
        $pager['total'] = $total;
        $pager['page'] = $page;
        $pager['page_size'] = $page_size;
        $pager['data'] = $list;

        return $pager;

    }
}