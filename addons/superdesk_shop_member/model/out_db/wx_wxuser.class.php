<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/11
 * Time: 17:02
 */

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/base_setting/SuperdeskShopMemberBaseOutDbModel.class.php');

class wx_wxuserModel extends SuperdeskShopMemberBaseOutDbModel
{

    public $table_name = "wx_wxuser";

    public $table_column_all = "id,uid,wxname,winxintype,aeskey,encode,appid,appsecret,wxid,weixin,headerpic,token,pigsecret,province,city,qq,wxfans,typeid,typename,createtime,updatetime,oauth,oauthinfo,state,mchid,wxkey";

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
    $insert_data['uid'] = '';//  | YES | int(11) | 
    $insert_data['wxname'] = '';// 公众号名称 | YES | varchar(60) | 
    $insert_data['winxintype'] = '1';// 公众号类型 | YES | smallint(2) | 1
    $insert_data['aeskey'] = '';// 加密串 | YES | varchar(45) | 
    $insert_data['encode'] = '0';// 加密方式,0:表示明文 | YES | tinyint(1) | 0
    $insert_data['appid'] = '';// 公众号appid | YES | varchar(50) | 
    $insert_data['appsecret'] = '';// 公众号密钥 | YES | varchar(50) | 
    $insert_data['wxid'] = '';// 公众号原始ID | YES | varchar(20) | 
    $insert_data['weixin'] = '';// 微信号 | YES | text | 
    $insert_data['headerpic'] = '';// 头像地址 | YES | char(255) | 
    $insert_data['token'] = '';// 登陆用户唯一token | YES | char(255) | 
    $insert_data['pigsecret'] = '';// 第三方者绑定密钥 | YES | varchar(150) | 
    $insert_data['province'] = '';// 省 | YES | varchar(30) | 
    $insert_data['city'] = '';// 市 | YES | varchar(60) | 
    $insert_data['qq'] = '';// 公众号邮箱 | YES | char(255) | 
    $insert_data['wxfans'] = '0';// 微信粉丝 | YES | int(11) | 0
    $insert_data['typeid'] = '';// 分类ID | YES | int(11) | 
    $insert_data['typename'] = '0';// 分类名 | YES | varchar(90) | 0
    $insert_data['oauth'] = '0';//  | YES | tinyint(1) | 0
    $insert_data['oauthinfo'] = '1';//  | YES | tinyint(1) | 1
    $insert_data['state'] = '';//  | YES | int(255) unsigned zerofill | 
    $insert_data['mchid'] = '';// 支付id | YES | varchar(100) | 
    $insert_data['wxkey'] = '';// 支付key | YES | varchar(255) | 


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