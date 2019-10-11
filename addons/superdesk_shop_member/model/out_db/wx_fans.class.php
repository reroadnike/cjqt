<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/11
 * Time: 17:02
 */

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/base_setting/SuperdeskShopMemberBaseOutDbModel.class.php');

class wx_fansModel extends SuperdeskShopMemberBaseOutDbModel
{

    public $table_name = "wx_fans";

    public $table_column_all = "fansId,openId,nickName,headImgUrl,telePhone,password,wxProvince,city,sex,is_subscribed,is_wsd_user,version,created_time,modified_time,token,marking_time,scene_str,fansType,language,levelid,userName,account,pwd,salerid,logintype,stated,pointPwd,userInfoAddress,storeQRCode";

    /**
     * @param $params
     */
    public function insert($params)
    {
        global $_GPC, $_W;

//        $params['uniacid']    = $_W['uniacid'];
//        $params['createtime'] = strtotime('now');

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

//        $params['updatetime'] = strtotime('now');
        $ret                  = $this->pdo_update($this->table_name, $params, array('id' => $id));
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
        $this->pdo_delete($this->table_name, array('id' => $id));
    }

    /**
     * @param        $params
     * @param string $id
     */
    public function saveOrUpdate($params, $id = '')
    {
        global $_GPC, $_W;

        if (empty($id)) {
//            $params['uniacid']    = $_W['uniacid'];
//            $params['createtime'] = strtotime('now');

            $ret = $this->pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = $this->pdo_insertid();
            }
        } else {
//            $params['updatetime'] = strtotime('now');
            $ret                  = $this->pdo_update($this->table_name, $params, array('id' => $id));
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

//            $params['uniacid']    = $_W['uniacid'];
//            $params['createtime'] = strtotime('now');

            $ret = $this->pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = $this->pdo_insertid();
            }

        } else {

//            $params['updatetime'] = strtotime('now');

            $ret = $this->pdo_update($this->table_name, $params, $column);

        }

    }

    public function saveOrUpdateByJdVop($params, $sku)
    {
        global $_GPC, $_W;


        $column    = array(
            "jd_vop_sku" => $sku
        );
        $_is_exist = $this->getOneByColumn($column);

        // 如果没找到会返回 false
        if (!$_is_exist) {

            $insert_data = array();

            // TODO
            $insert_data['fansId']          = '';// 活动用户ID | NO | int(20) |
            $insert_data['openId']          = '';// 微信用户OPENID | YES | char(35) |
            $insert_data['nickName']        = '';// 用户昵称 | YES | varchar(500) |
            $insert_data['headImgUrl']      = '';// 用户头像 | YES | varchar(150) |
            $insert_data['telePhone']       = '';// 手机号码 | YES | varchar(20) |
            $insert_data['password']        = '';// 登陆密码 | YES | varchar(20) |
            $insert_data['wxProvince']      = '';//  | YES | varchar(100) |
            $insert_data['city']            = '';// 所在城市 | YES | varchar(100) |
            $insert_data['sex']             = '0';// 1:男性，2：女性,0：未知 | YES | varchar(5) | 0
            $insert_data['is_subscribed']   = '';// 是否关注:0否1是 | YES | char(1) |
            $insert_data['is_wsd_user']     = '';// 是否已登陆用户:0否1是 | YES | char(1) |
            $insert_data['version']         = '0';// 版本号 | YES | int(11) | 0
            $insert_data['created_time']    = '';// 创建时间 | YES | datetime |
            $insert_data['modified_time']   = '';// 修改时间 | YES | datetime |
            $insert_data['token']           = '';// 来源 | YES | varchar(20) |
            $insert_data['marking_time']    = '0000-00-00 00:00:00';// 打标时间 | YES | datetime | 0000-00-00 00:00:00
            $insert_data['scene_str']       = '';// 推荐码/水店ID | YES | varchar(20) |
            $insert_data['fansType']        = '0';// 0:微信会员1:pc端会员2:导入 | YES | int(3) | 0
            $insert_data['language']        = '';// 语言 | YES | varchar(100) |
            $insert_data['levelid']         = '0';// 角色,0:普通客户，1：经销商，2:其他 | YES | int(255) | 0
            $insert_data['userName']        = '';// 会员名称 | YES | varchar(255) |
            $insert_data['account']         = '';// 帐号 | YES | varchar(255) |
            $insert_data['pwd']             = '';// 密码 | YES | varchar(255) |
            $insert_data['salerid']         = '';// 所属销售ID | YES | varchar(50) |
            $insert_data['logintype']       = '';// 登陆类型,0:手机，1：后台 | YES | int(11) |
            $insert_data['stated']          = '1';// 1:正常 2: 黑名单 | YES | int(11) | 1
            $insert_data['pointPwd']        = '';// 积分密码 | YES | varchar(255) |
            $insert_data['userInfoAddress'] = '';// 用户基本信息地址 | YES | varchar(255) |
            $insert_data['storeQRCode']     = '';// 店铺二维码 | YES | varchar(255) |


//            $insert_data['uniacid']    = $_W['uniacid'];
//            $insert_data['createtime'] = strtotime('now');

            $ret = $this->pdo_insert($this->table_name, $insert_data);
            if (!empty($ret)) {
                $id = $this->pdo_insertid();
            }

        } else {

            $update_data = array();

            // TODO

//            $update_data['updatetime'] = strtotime('now');

            $ret = $this->pdo_update($this->table_name, $update_data, $column);

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

        $result = $this->pdo_get($this->table_name, array('id' => $id));

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

        $result = $this->pdo_get($this->table_name, $column);

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

        $where_sql = "";

//        $where_sql .= " WHERE `uniacid` = :uniacid";
//        $params = array(
//            ':uniacid' => $_W['uniacid'],
//        );

        $total = $this->pdo_fetchcolumn("SELECT COUNT(*) FROM " . $this->tablename($this->table_name) . $where_sql, $params);
        $list  = $this->pdo_fetchall("SELECT * FROM " . $this->tablename($this->table_name) . $where_sql . " ORDER BY fansId ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);
//        $pager = pagination($total, $page, $page_size);

        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        

        return $pager;

    }
}