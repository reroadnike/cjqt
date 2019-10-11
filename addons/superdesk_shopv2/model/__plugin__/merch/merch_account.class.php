<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/18
 * Time: 18:30
 */

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/base_setting/SuperdeskShopv2BaseModel.class.php');

class merch_accountModel extends SuperdeskShopv2BaseModel
{

    public $table_name = "superdesk_shop_merch_account";

    public $table_column_all = "id,uniacid,openid,merchid,username,pwd,salt,status,perms,isfounder,lastip,lastvisit,roleid";

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

    public function saveOrUpdateByOldShop($params, $redis_cache_id)
    {
        global $_GPC, $_W;

//        {
//            "store_id": "143",
//            "store_account": "jd",
//            "store_name": "京东商城",
//            "store_user": "刘强东",
//            "store_address": "京东",
//            "phone": "13910011001",
//            "store_code": null,
//            "ctime": "2017-03-07 11:34:29",
//            "endTime": "2017-12-09 00:00:00",
//            "status": "400"
//        }

        if (empty($redis_cache_id)) {
//            $_is_exist = false;

            $column    = array(
                "username" => $params['store_account']
            );
            $_is_exist = $this->getOneByColumn($column);
        } else {
            $column    = array(
                "id" => $redis_cache_id
            );
            $_is_exist = $this->getOneByColumn($column);
        }

        // 如果没找到会返回 false
        if (!$_is_exist) {

            $salt = random(8);
            while (1) {
                $saltcount = pdo_fetchcolumn(
                    ' select count(*) ' .
                    ' from ' . tablename('superdesk_shop_merch_account') .
                    ' where salt=:salt ' .
                    ' limit 1',
                    array(
                        ':salt' => $salt
                    )
                );

                if ($saltcount <= 0) {
                    break;
                }

                $salt = random(8);
            }

            $pwd = md5(trim('123456') . $salt);

            $insert_data = array();

            // TODO
            $insert_data['openid']    = '';//  | YES | varchar(255) | 
            $insert_data['merchid']   = $params['merchid'];// 商户ID | YES | int(11) | 0 引用 ims_superdesk_shop_merch_user.id
            $insert_data['username']  = $params['store_account'];// 用户名 | YES | varchar(255) |
            $insert_data['pwd']       = $pwd;// 用户密码 | YES | varchar(255) |
            $insert_data['salt']      = $salt;// 密码加盐 | YES | varchar(255) |
            $insert_data['status']    = '1';//  | YES | tinyint(3) | 0
            $insert_data['perms']     = serialize(array());// 权限 | YES | text |
            $insert_data['isfounder'] = '1';// 是否创始人 | YES | tinyint(3) | 0
            $insert_data['lastip']    = '';// 最后IP | YES | varchar(255) |
            $insert_data['lastvisit'] = '';// 最后访问 | YES | varchar(255) |
            $insert_data['roleid']    = '0';//  | YES | int(11) | 0


            $insert_data['uniacid']    = $_W['uniacid'];

            $ret = pdo_insert($this->table_name, $insert_data);
            if (!empty($ret)) {
                $id = pdo_insertid();
                return $id;
            } else{
                return 0 ;
            }

        } else {

            return $_is_exist['id'];
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

        $where_sql = "";

        $where_sql .= " WHERE `uniacid` = :uniacid";
        $params = array(
            ':uniacid' => $_W['uniacid'],
        );

        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_name) . $where_sql, $params);
        $list  = pdo_fetchall("SELECT * FROM " . tablename($this->table_name) . $where_sql . " ORDER BY id ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);

        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        return $pager;

    }
}