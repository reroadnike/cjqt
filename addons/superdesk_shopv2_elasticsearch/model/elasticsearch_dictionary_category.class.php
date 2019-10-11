<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/05/17
 * Time: 18:53
 */



class elasticsearch_dictionary_categoryModel
{

    public $table_name = "elasticsearch_dictionary_category";

    public $table_column_all = "id,uniacid,parentid,name,description,displayorder,enabled,level";

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

//        $params['updatetime'] = strtotime('now');
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

        if (empty($id)) {
            $params['uniacid'] = $_W['uniacid'];
            //$params['createtime'] = strtotime('now');

            $ret = pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = pdo_insertid();
            }
        } else {
//            $params['updatetime'] = strtotime('now');
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
//            $params['createtime'] = strtotime('now');

            $ret = pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = pdo_insertid();
            }

        } else {

//            $params['updatetime'] = strtotime('now');

            $ret = pdo_update($this->table_name, $params, $column);

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
    $insert_data['parentid'] = '0';// 上级分类ID,0为第一级 | YES | int(11) | 0
    $insert_data['name'] = '';// 分类名称 | YES | varchar(50) | 
    $insert_data['description'] = '';// 分类介绍 | YES | varchar(500) | 
    $insert_data['displayorder'] = '0';// 排序 | YES | tinyint(3) unsigned | 0
    $insert_data['enabled'] = '1';// 是否开启 | YES | tinyint(1) | 1
    $insert_data['level'] = '';// 分类是在几级 | YES | tinyint(3) | 


            $insert_data['uniacid'] = $_W['uniacid'];
//            $insert_data['createtime'] = strtotime('now');

            $ret = pdo_insert($this->table_name, $insert_data);
            if (!empty($ret)) {
                $id = pdo_insertid();
            }

        } else {

            $update_data = array();

            // TODO

//            $update_data['updatetime'] = strtotime('now');

            $ret = pdo_update($this->table_name, $update_data, $column);

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
    public function queryAll($where = array(), $page = 0, $page_size = 50)
    {
        global $_GPC, $_W;//TIMESTAMP

        $page = max(1, intval($page));

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
     * @param array $where
     */
    public function queryAllNotLimit($where = array())
    {
        global $_GPC, $_W;//TIMESTAMP

        $where_sql = " WHERE `uniacid` = :uniacid";
        $params = array(
            ':uniacid' => $_W['uniacid'],
        );

        if(!empty($where['level'])){
            $where_sql .= ' AND `level` = :level';
            $params[':level'] = $where['level'];
        }

        $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_name) . $where_sql . " ORDER BY id ASC ", $params);

        return $list;

    }

    public function getFullCategory($fullname = false, $enabled = false)
    {
        global $_W;
        $allcategory = array();
        $sql = 'SELECT * FROM ' . tablename($this->table_name) . ' WHERE uniacid=:uniacid ';
        if ($enabled) {
            $sql .= ' AND enabled=1';
        }
        $sql .= ' ORDER BY parentid ASC, displayorder DESC';
        $category = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));

        if (empty($category)) {
            return array();
        }

        foreach ($category as &$c) {
            if (empty($c['parentid'])) {
                $allcategory[] = $c;
                foreach ($category as &$c1) {
                    if ($c1['parentid'] != $c['id']) {
                        continue;
                    }
                    if ($fullname) {
                        $c1['name'] = $c['name'] . '-' . $c1['name'];
                    }
                    $allcategory[] = $c1;
                    foreach ($category as &$c2) {
                        if ($c2['parentid'] != $c1['id']) {
                            continue;
                        }
                        if ($fullname) {
                            $c2['name'] = $c1['name'] . '-' . $c2['name'];
                        }
                        $allcategory[] = $c2;
                        foreach ($category as &$c3) {
                            if ($c3['parentid'] != $c2['id']) {
                                continue;
                            }
                            if ($fullname) {
                                $c3['name'] = $c2['name'] . '-' . $c3['name'];
                            }
                            $allcategory[] = $c3;
                        }
                        unset($c3);
                    }
                    unset($c2);
                }
                unset($c1);
            }
            unset($c);
        }

        return $allcategory;
    }
}