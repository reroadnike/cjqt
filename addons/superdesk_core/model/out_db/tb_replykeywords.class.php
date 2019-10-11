<?php
namespace superdesk_core\model\out_db;
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/22
 * Time: 16:20
 */

include_once(IA_ROOT . '/addons/superdesk_core/model/out_db/base_setting/SuperdeskCoreBaseOutDbModel.class.php');

class tb_replykeywordsModel extends SuperdeskCoreBaseOutDbModel
{

    public $table_name = "tb_replykeywords";

    public $table_column_all = "ReplyKeywordsId,DevelopId,Name,Remark,State,CreateTime,ReplyContentType,CreatePerson";

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
    $insert_data['ReplyKeywordsId'] = '';//  | NO | int(11) | 
    $insert_data['DevelopId'] = '';// 开发者ID | YES | varchar(40) | 
    $insert_data['Name'] = '';// 关键字 | YES | varchar(45) | 
    $insert_data['Remark'] = '';// 备注 | YES | varchar(128) | 
    $insert_data['State'] = '';// 全用状态 1启用 0禁用 | YES | char(1) | 
    $insert_data['CreateTime'] = '';// 创建时间 | YES | datetime | 
    $insert_data['ReplyContentType'] = '';// 回复类型1文字+图片+语音 2、图文 | YES | char(1) | 
    $insert_data['CreatePerson'] = '';// 创建人 | YES | varchar(45) | 


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