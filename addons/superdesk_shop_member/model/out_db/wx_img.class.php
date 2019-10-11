<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/11
 * Time: 17:02
 */

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/base_setting/SuperdeskShopMemberBaseOutDbModel.class.php');

class wx_imgModel extends SuperdeskShopMemberBaseOutDbModel
{

    public $table_name = "wx_img";

    public $table_column_all = "id,uid,uname,keyword,precisions,text,classid,classname,pic,showpic,info,url,createtime,uptatetime,click,token,title,usort,longitude,latitude,type,writer,texttype,usorts,is_focus,keyworduuid,stauts";

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
    $insert_data['uname'] = '';//  | YES | varchar(90) | 
    $insert_data['keyword'] = '';//  | YES | char(255) | 
    $insert_data['precisions'] = '0';//  | YES | tinyint(1) | 0
    $insert_data['text'] = '';// 简介 | YES | varchar(1000) | 
    $insert_data['classid'] = '';//  | YES | int(11) | 
    $insert_data['classname'] = '';//  | YES | varchar(60) | 
    $insert_data['pic'] = '';// 封面图片 | YES | char(255) | 
    $insert_data['showpic'] = '';// 图片是否显示封面 | YES | varchar(1) | 
    $insert_data['info'] = '';//  | YES | text | 
    $insert_data['url'] = '';// 图文外链地址 | YES | varchar(255) | 
    $insert_data['uptatetime'] = '';//  | YES | datetime | 
    $insert_data['click'] = '';//  | YES | int(11) | 
    $insert_data['token'] = '';//  | YES | char(30) | 
    $insert_data['title'] = '';//  | YES | varchar(60) | 
    $insert_data['usort'] = '1';//  | YES | int(11) | 1
    $insert_data['longitude'] = '0';//  | YES | varchar(20) | 0
    $insert_data['latitude'] = '0';//  | YES | varchar(20) | 0
    $insert_data['type'] = '0';//  | YES | tinyint(4) | 0
    $insert_data['writer'] = '';// 作者 | YES | varchar(200) | 
    $insert_data['texttype'] = '1';// 文本类型 | YES | int(11) | 1
    $insert_data['usorts'] = '1';// 分类文章排列顺序 | YES | int(11) | 1
    $insert_data['is_focus'] = '';//  | YES | tinyint(4) | 
    $insert_data['keyworduuid'] = '';//  | YES | varchar(50) | 
    $insert_data['stauts'] = '';// 1:表示启用，0表示停用 | YES | int(11) | 


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