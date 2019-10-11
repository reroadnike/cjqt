<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/18
 * Time: 18:30
 */

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/base_setting/SuperdeskShopv2BaseModel.class.php');

class merch_userModel extends SuperdeskShopv2BaseModel
{

    public $table_name = "superdesk_shop_merch_user";

    public $table_column_all = "id,uniacid,regid,openid,groupid,merchno,merchname,salecate,desc,realname,mobile,status,accounttime,diyformdata,diyformfields,applytime,accounttotal,remark,jointime,accountid,sets,logo,payopenid,payrate,isrecommand,cateid,address,tel,lat,lng";

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

        $ret = pdo_update($this->table_name, $params, array('id' => $id));
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
//            "status": "400" //0:正常1:到期2:暂停400:删除
//        }

        if (empty($redis_cache_id)) {
//            $_is_exist = false;
            $column    = array(
                "merchno" => $params['store_account']
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

            $insert_data = array();

            // TODO
            $insert_data['accountid']     = '0';// 帐号表ID | YES | int(11) | 0 生成account.id后再更换
            $insert_data['regid']         = '0';// 商户注册ID | YES | int(11) | 0 引用ims_superdesk_shop_merch_reg.id
            $insert_data['openid']        = '';//  | NO | varchar(255) | //一般为空
            $insert_data['groupid']       = '1';// 商户分组ID | YES | int(11) | 0
            $insert_data['merchno']       = $params['store_account'];//$params['store_code'];// 商户编号 | NO | varchar(255) |
            $insert_data['merchname']     = $params['store_name'];// 商户名 | NO | varchar(255) |
            $insert_data['salecate']      = 'please write down 销售类别';// 销售类别 | NO | varchar(255) |
            $insert_data['desc']          = $params['store_name'];// 介绍 | NO | varchar(500) |
            $insert_data['realname']      = $params['store_user'];// 实名 | NO | varchar(255) |
            $insert_data['mobile']        = $params['phone'];// 手机号 | NO | varchar(255) |
            $insert_data['status']        = '1';// 状态 1 允许入驻 2 暂停 3 即将到期 | YES | tinyint(3) | 0
            $insert_data['accounttime']   = strtotime($params['endTime']);// 服务时间，默认1年 | YES | int(11) | 0
            $insert_data['diyformdata']   = '';// 自定义数据 | YES | text |
            $insert_data['diyformfields'] = '';// 自定义字段 | YES | text |
            $insert_data['applytime']     = time();// 审核时间 | YES | int(11) | 0
            $insert_data['accounttotal']  = '10';// 可以开多少子帐号 商户可以创建的子管理帐号个数，默认不能创建 | YES | int(11) | 0
            $insert_data['remark']        = '导入';// 备注 | YES | text |
            $insert_data['jointime']      = time();// 加入时间 | YES | int(11) | 0

            $insert_data['sets']        = '';// 商家基础设置 | YES | text |
            $insert_data['logo']        = '';// 标志 | NO | varchar(255) |
            $insert_data['payopenid']   = '';// 收款人openid | NO | varchar(32) |
            $insert_data['payrate']     = '0.00';// 抽成利率 | NO | decimal(10,2) | 0.00
            $insert_data['isrecommand'] = '0';// 是否推荐 | YES | tinyint(1) | 0
            $insert_data['cateid']      = '1';// 商户分类ID | YES | int(11) | 0
            $insert_data['address']     = $params['store_address'];// 地址 | YES | varchar(255) |
            $insert_data['tel']         = $params['phone'];// 电话 | YES | varchar(255) |
            $insert_data['lat']         = '';// 经度 | YES | varchar(255) |
            $insert_data['lng']         = '';// 纬度 | YES | varchar(255) |

            $insert_data['uniacid'] = $_W['uniacid'];

            $ret = pdo_insert($this->table_name, $insert_data);
            if (!empty($ret)) {
                $id = pdo_insertid();
                return $id;
            } else {
                return 0;
            }

        } else {

            $update_data                = array();
            $update_data['merchno']     = $params['store_account'];//$params['store_code'];// 商户编号 | NO | varchar(255) |
            $update_data['merchname']   = $params['store_name'];// 商户名 | NO | varchar(255) |
            $update_data['desc']        = $params['store_name'];// 介绍 | NO | varchar(500) |
            $update_data['realname']    = $params['store_user'];// 实名 | NO | varchar(255) |
            $update_data['mobile']      = $params['phone'];// 手机号 | NO | varchar(255) |
            $update_data['accounttime'] = strtotime($params['endTime']);// 服务时间，默认1年 | YES | int(11) | 0
            $update_data['address']     = $params['store_address'];// 地址 | YES | varchar(255) |
            $update_data['tel']         = $params['phone'];// 电话 | YES | varchar(255) |
            $this->update($update_data, $_is_exist['id']);
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
     * 根据ID查商户名字
     * @param $id
     *
     * @return string
     */
    public function getMerchnameById($id)
    {

        global $_GPC, $_W;

        $params = array(
            "id" => $id
        );

        $result = pdo_fetch(
            ' select merchname ' .
            ' from ' . tablename($this->table_name) .
            ' where id=:id '.
            ' limit 1',
            $params
        );

        if (empty($result)) {
            return '';
        } else {
            return $result['merchname'];
        }


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