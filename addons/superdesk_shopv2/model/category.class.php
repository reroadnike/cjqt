<?php
/**
* Created by PhpStorm.
* User: linjinyu
* Date: 6/19/17
* Time: 11:28 AM
*/

class categoryModel
{

    public $table_name = "superdesk_shop_category";

    public $table_column_all = "id,uniacid,name,thumb,parentid,isrecommand,description,displayorder,enabled,ishome,advimg,advurl,level,jd_vop_page_num,fiscal_code";

    /**
     * @param $params
     */
    public function insert($params)
    {
        global $_GPC, $_W;

        $params['uniacid'] = $_W['uniacid'];
//        $params['createtime'] = strtotime('now');

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
     * @param $params
     * @param string $id
     */
    public function saveOrUpdate($params, $id = '')
    {
        global $_GPC, $_W;

        $ret = 0;

        if (empty($id)) {
            $params['uniacid'] = $_W['uniacid'];
//            $params['createtime'] = strtotime('now');

            $ret = pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = pdo_insertid();
            }
        } else {
//            $params['updatetime'] = strtotime('now');
            $ret = pdo_update($this->table_name, $params, array('id' => $id));
        }

        return $ret;

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
//            $params['createtime'] = strtotime('now');

            $ret = pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = pdo_insertid();
            }

        } else {

//            $params['updatetime'] = strtotime('now');
            $ret = pdo_update($this->table_name, $params, $column);

            $id = $_is_exist['id'];

        }

        return $id;

    }

    /**
     * @param $params
     *
     *  {"catId":670,"parentId":0,"name":"\u7535\u8111\u3001\u529e\u516c","catClass":0,"state":1}
     *  {"catId":671,"parentId":670,"name":"\u7535\u8111\u6574\u673a","catClass":1,"state":1}
     *  {"catId":673,"parentId":671,"name":"\u53f0\u5f0f\u673a","catClass":2,"state":1}
     * @param $page_num
     */
    public function saveOrUpdateByJdVop($params , $page_num = 0){


        global $_GPC, $_W;

        $column = array();
        $column["id"] = $params['catId'];

        $_is_exist = $this->getOneByColumn($column);

        // 如果没找到会返回 false
        if (!$_is_exist) {

            $insert_data                 = array();
            $insert_data['id']           = $params['catId'];
            $insert_data['name']         = isset($params['name']) ? $params['name'] : '';
            $insert_data['thumb']        = '';
            $insert_data['parentid']     = $params['parentId'];
            $insert_data['description']  = isset($params['name']) ? $params['name'] : '';
            $insert_data['displayorder'] = 0;
            $insert_data['enabled']      = $params['state'];

            $insert_data['ishome']      = 0;
            $insert_data['isrecommand'] = 0;

            $insert_data['advimg'] = '';
            $insert_data['advurl'] = '';

            $insert_data['level']           = $params['catClass'] + 1;// 修正京东 与 商城 Level 的差1问题
            $insert_data['jd_vop_page_num'] = $params['catClass'] == 2 ? $page_num : 0; // catClass相当于level 从0开始

            $insert_data['uniacid'] = $_W['uniacid'];

            $ret = pdo_insert($this->table_name, $insert_data);
            if (!empty($ret)) {
                $id = pdo_insertid();
            }

        } 
//        else {
//
//            // 屏蔽是不更新了，转到redis做个定时删除
//            $update_data = array();
//
//            $update_data['name']            = isset($params['name']) ? $params['name'] : '';
//            $update_data['parentid']        = $params['parentId'];
//            $update_data['description']     = isset($params['name']) ? $params['name'] : '';
//            $update_data['enabled']         = $params['state'];
//            $update_data['level']           = $params['catClass'];
//            $update_data['jd_vop_page_num'] = $params['catClass'] == 2 ? $page_num : 0;
//
//            $ret = pdo_update($this->table_name, $update_data, $column);
//
//        }
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

    public function getNameById($id)
    {

        global $_GPC, $_W;

        $params = array(
            "id" => $id
        );

        $result = pdo_fetch(
            ' select name ' .
            ' from ' . tablename($this->table_name) .
            ' where id=:id '.
            ' limit 1',
            $params
        );

        if (empty($result)) {
            return '';
        } else {
            return $result['name'];
        }


    }

//    public function getOneByOldShopCateId($old_shop_cate_id)
//    {
//        global $_GPC, $_W;
//        return $this->getOneByColumn(array(
//            'old_shop_cate_id' => $old_shop_cate_id
//        ));
//    }

    /**
     * @param $jd_vop_page_num
     *
     * @return int
     */
    public function getIdByColumnJdVopPageNum($jd_vop_page_num){

        global $_GPC, $_W;

        $column = array(
            "jd_vop_page_num" => $jd_vop_page_num
        );

        $fields = array();
        $fields[] = "id";

        $result = pdo_get($this->table_name, $column , $fields);

        if(empty($result)){
            return 0;
        } else{
            return $result['id'];
        }



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

        $where_sql = "";
        $where_sql .= " WHERE `uniacid` = :uniacid";
        $params = array(
            ':uniacid' => $_W['uniacid'],
        );

        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_name) . $where_sql, $params);
        $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_name) . $where_sql . " ORDER BY id ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);
//        $pager = pagination($total, $page, $page_size);

        $pager = array();
        $pager['total'] = $total;
        $pager['page'] = $page;
        $pager['page_size'] = $page_size;
        $pager['data'] = $list;

        return $pager;

    }

    /**
     * 获取page num 不为0的分类
     * @param array $where
     * @param int $page
     * @param int $page_size
     * @return array
     */
    public function queryByJdVopApiProductGetSku($where = array(), $page = 0, $page_size = 50)
    {
        global $_GPC, $_W;//TIMESTAMP

        $page = max(1, intval($page));

        $where_sql = "";
        $where_sql .= " WHERE `uniacid` = :uniacid";
        $params = array(
            ':uniacid' => $_W['uniacid'],
        );

        $where_sql .= " and `jd_vop_page_num` <> 0 ";




        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_name) . $where_sql, $params);
        $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_name) . $where_sql . " ORDER BY id ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);
//        $pager = pagination($total, $page, $page_size);

        $pager = array();
        $pager['total'] = $total;
        $pager['page'] = $page;
        $pager['page_size'] = $page_size;
        $pager['data'] = $list;

        return $pager;

    }

    public function zTreeV3Data($where = array(),$page = 0, $page_size = 5000){

        global $_GPC, $_W;//TIMESTAMP

        $page = max(1, intval($page));

        $where_sql = "";
        $where_sql .= " WHERE `uniacid` = :uniacid";
        $params = array(
            ':uniacid' => $_W['uniacid'],
        );

        $enabled = isset($where['enabled']) ? $where['enabled'] : "" ;
        if (!empty($enabled)) {
            $where_sql .= " AND `enabled` = :enabled";
            $params[':enabled'] = $enabled;
        }

        if (!empty($where['cateType'])) {
            $where_sql .= " AND `cateType` = :cateType";
            $params[':cateType'] = $where['cateType'];
        }

        $list = pdo_fetchall(
            " SELECT id,id as tId,parentid as pId, name , jd_vop_page_num as page_num, " .
            " if(jd_vop_page_num = 0,1,0) as dropRoot ".
            " FROM " . tablename($this->table_name) . $where_sql . " ORDER BY displayorder DESC, id ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);

        foreach ($list as &$item) {
            if ($item['dropRoot'] == "0") {
                $item['dropRoot'] = false;
                $item['dropInner'] = false;
            } elseif ($item['dropRoot'] == "1") {
                $item['dropRoot'] = true;
            }
        }

        return json_encode($list );//, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE

    }

    public function zTreeV3Data4Test($where = array(),$page = 0, $page_size = 5000){

        global $_GPC, $_W;//TIMESTAMP

        $page = max(1, intval($page));

        $where_sql = "";
        $where_sql .= " WHERE `uniacid` = :uniacid";
        $params = array(
            ':uniacid' => $_W['uniacid'],
        );

        $enabled = isset($where['enabled']) ? $where['enabled'] : "" ;
        if (!empty($enabled)) {
            $where_sql .= " AND `enabled` = :enabled";
            $params[':enabled'] = $enabled;
        }

        $list = pdo_fetchall(
            " SELECT id,id as tId,parentid as pId, name , jd_vop_page_num as page_num " .
//            " ,if(jd_vop_page_num = 0,1,0) as dropRoot ".
            " FROM " . tablename($this->table_name) . $where_sql . " ORDER BY displayorder DESC, id ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);

        foreach ($list as &$item) {
//            $item['name'] = $item['name'] . ' ' . $item['id'];

//            if($item['page_num'] == 0){
//                $item['dropRoot'] = true;
//            }elseif($item['page_num'] != 0){
//                $item['dropRoot'] = false;
//                $item['isLastNode'] = true;
//            }

//            if ($item['dropRoot'] == "0") { // 有pageNum的不能放到根节点
//                $item['dropRoot'] = false;
//                $item['dropInner'] = false;
//                $item['isLastNode'] = true;
//
//            } elseif ($item['dropRoot'] == "1") {
//                $item['dropRoot'] = true;
//            }
        }

        return json_encode($list , JSON_UNESCAPED_UNICODE);//, JSON_PRETTY_PRINT |

    }
}