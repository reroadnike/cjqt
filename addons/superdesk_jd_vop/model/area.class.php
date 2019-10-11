<?php

/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2017/11/13
 * Time: 17:18
 */
class areaModel
{

    public $table_name = "superdesk_jd_vop_area";

    public $table_column_all = "code,parent_code,text,level,state,createtime,updatetime";

    /**
     * @param $params
     */
    public function insert($params)
    {
        global $_GPC, $_W;

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
     * @param     $response
     * @param int $parent_code
     * @param int $level
     *
     * @return array
     */
    public function saveOrUpdateByJdVopApiAreaBatch(
        $response/*京东VOP Area api 返回的json string*/,
        $parent_code = 0,
        $level = 0)
    {
        global $_GPC, $_W;

        $return_array = array();

        $response = json_decode($response, true);

        if ($response['success'] == false) {

            $params               = array();
            $params['remark']     = $response['resultCode'] . "-" . $response['resultMessage'];
            $params['updatetime'] = strtotime('now');
            $column               = array(
                "code" => $parent_code
            );
            $ret                  = pdo_update($this->table_name, $params, $column);
            // TODO
            return $return_array;
        }

        foreach ($response['result'] as $key => $code) {

            $params                = array();
            $params['code']        = $code;// code | NO | int(11) |
            $params['parent_code'] = $parent_code;// parent_code | NO | int(11) |
            $params['text']        = $key;// text | NO | varchar(128) |
            $params['level']       = $level;

//            echo json_encode($params, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
//            echo "<br>";

            $column    = array(
                "code" => $params['code']
            );
            $_is_exist = $this->getOneByColumn($column);

            // 如果没找到会返回 false
            if (!$_is_exist) {

                $params['state']      = '1';// state | NO | tinyint(3) | 1
                $params['createtime'] = strtotime('now');

                $ret = pdo_insert($this->table_name, $params);
                if (!empty($ret)) {
                    $id = pdo_insertid();
                }

            } else {

                $params['updatetime'] = strtotime('now');
                $ret                  = pdo_update($this->table_name, $params, $column);

            }

            $return_array[] = $params;
        }

        $this->getDiff($response['result'],$parent_code);

        return $return_array;

    }

    public function getDiff($jd, $parentId = 0)
    {

        pdo_update(
            'superdesk_jd_vop_area',
            array(
                'state' => 0)
            ,
            array(
                'parent_code' => $parentId
            )
        );

        $diff = array();
        if (!empty($jd)) {
            $diff = pdo_fetchall(
                ' select code,text '.
                ' from ' . tablename('superdesk_jd_vop_area') .
                ' where '.
                '   `parent_code` = ' . $parentId .
                '   and code not in (' . implode(",", $jd) . ')');

            include_once(IA_ROOT . '/framework/library/logs/LogsUtil.class.php');
            LogsUtil::logging('info', json_encode($diff, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), 'jd_area');

            $rs = pdo_query(
                'update ' . tablename('superdesk_jd_vop_area') .
                ' set `state` = 1 '.
                ' where '.
                '   `parent_code` = ' . $parentId .
                '   and code in (' . implode(",", $jd) . ')');
        }


        return $diff;
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



    public function getTextByCode($code)
    {

        global $_GPC, $_W;

        if(empty($code)){
            return '';
        }

        $params = array(
            "code" => $code
        );

        $result = pdo_fetch(
            ' select text ' .
            ' from ' . tablename($this->table_name) .
            ' where code=:code '.
            ' limit 1',
            $params
        );

        if (empty($result)) {
            return '';
        } else {
            return $result['text'];
        }


    }

    public function getCodeByText($text)
    {

        global $_GPC, $_W;

        $params = array(
            "text" => $text
        );

        $result = pdo_fetch(
            ' select code ' .
            ' from ' . tablename($this->table_name) .
            ' where text=:text '.
            ' limit 1',
            $params
        );

        if (empty($result)) {
            return 0;
        } else {
            return $result['code'];
        }


    }

    /**
     * @param array $where
     * @param int   $page
     * @param int   $page_size
     *
     * @return array
     */
    public function queryAll($where = array(), $page = 0, $page_size = 10000)
    {
        global $_GPC, $_W;//TIMESTAMP

        $page = max(1, intval($page));

        $where_sql = "";
        $params = array();

        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_name) . $where_sql, $params);
        $list  = pdo_fetchall("SELECT * FROM " . tablename($this->table_name) . $where_sql . " ORDER BY code ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);
//        $pager = pagination($total, $page, $page_size);

        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        return $pager;

    }

    public function zTreeV3CityData()
    {
        global $_W;
        global $_GPC;

        $state     = 1;
        $where_sql = "";

//        $where_sql .= " WHERE `state` = :state";
//        $params = array(
//            ':state' => $state,
//        );
//
//        $where_sql .= " AND `level` <= :level";
//        $params[':level'] = $level;


        $list = pdo_fetchall(
            "SELECT code as id,parent_code as pId,text as name " .
            "FROM " . tablename($this->table_name) . $where_sql . " ORDER BY code ASC,parent_code ASC ", $params, 'code');


        return json_encode($list);//, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    }

    /**
     * jquery 第四级异步请求接口
     * @param $code
     *
     * @return mixed|string
     */
    public function cityPickerDataLevel3($code)
    {


        global $_W;
        global $_GPC;

        $state     = 1;
        $where_sql = "";

        $where_sql .= " WHERE `state` = :state";
        $params = array(
            ':state' => $state,
        );

        $where_sql .= " AND `level` = :level";
        $params[':level'] = 3;
        $where_sql .= " AND `parent_code` = :parent_code";
        $params[':parent_code'] = $code;

        $list = pdo_fetchall(
            "SELECT code,parent_code,text,level " .
            "FROM " . tablename($this->table_name) . $where_sql . " ORDER BY code ASC,parent_code ASC ", $params, 'code');

//        die(json_encode($list , JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $_cityPickerData = $this->rendercityPickerData($list, "parent_code", "code");

        return json_encode($_cityPickerData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }


//code,parent_code,text,level,state,createtime,updatetime

    /**
     * 京东Vop 简单 select 异步 ,　
     * @param $parent_code
     *
     * @return mixed|string
     */
    public function jdVopAreaCascade($parent_code = 0){

        global $_W;
        global $_GPC;

        $state     = 1;
        $where_sql = "";

        $where_sql .= " WHERE `state` = :state";
        $params = array(
            ':state' => $state,
        );

        $where_sql .= " AND `parent_code` = :parent_code";
        $params[':parent_code'] = $parent_code;

        $list = pdo_fetchall(
            " SELECT code,text " .
            " FROM " . tablename($this->table_name) . $where_sql .
            " ORDER BY code ASC,parent_code ASC ",
            $params, 'code'
        );



        return json_encode($list, JSON_UNESCAPED_UNICODE);
    }


    public function cityPickerData($level = 2)
    {

        global $_W;
        global $_GPC;

        $state     = 1;
        $where_sql = "";

        $where_sql .= " WHERE `state` = :state";
        $params = array(
            ':state' => $state,
        );

        $where_sql .= " AND `level` BETWEEN 1 AND :level";
        $params[':level'] = $level;

        $list = pdo_fetchall(
            "SELECT code,parent_code,text,level " .
            "FROM " . tablename($this->table_name) . $where_sql . " ORDER BY code ASC,parent_code ASC ", $params, 'code');

//        die(json_encode($list , JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $_cityPickerData = $this->rendercityPickerData($list, "parent_code", "code");

        return json_encode($_cityPickerData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    private function rendercityPickerData($flat, $pidKey, $idKey = null)
    {


        $grouped = array();

        foreach ($flat as $sub) {

            $parent_code                         = $sub[$pidKey];
            $grouped[$parent_code][$sub[$idKey]] = $sub['text'];
        }

        die(json_encode($grouped, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

//        $fnBuilder = function ($siblings) use (&$fnBuilder, $grouped, $idKey) {
//            foreach ($siblings as $k => $sibling) {
//                $id = $sibling[$idKey];
//
//
//                if (isset($grouped[$id])) {
//                    $sibling['children'] = $fnBuilder($grouped[$id]);
//                }
//
//
//                if($sibling['level'] < 3){
//
//                    unset($sibling[$idKey]);
//                    unset($sibling['level']);
//                    $siblings[$k] = $sibling;
//
//                }  elseif($sibling['level']==3){
//                    $siblings[$k] = $sibling['text'];
//                }
//
//            }
//
//            return $siblings;
//        };
//
//        $tree = $fnBuilder($grouped[0]);
//
//        return $tree;
    }


    /**
     * 对等结构 {"code":"code",text":"北京市","children":[]}
     * @param int $level
     *
     * @return mixed|string
     */
    public function jQueryCityData($level = 3)
    {

        global $_W;
        global $_GPC;

        $state     = 1;
        $where_sql = "";

        $where_sql .= " WHERE `state` = :state";
        $params = array(
            ':state' => $state,
        );

        $where_sql .= " AND `level` <= :level";
        $params[':level'] = $level;


        $list = pdo_fetchall(
            "SELECT code,parent_code,text,level " .
            "FROM " . tablename($this->table_name) . $where_sql . " ORDER BY code ASC,parent_code ASC ", $params, 'code');


        // 方法2 内部类，优雅点
        $foxUICityData = $this->renderjQueryCityData($list, "parent_code", "code");
//        print_r($foxUICityData);
//        exit();

        return json_encode($foxUICityData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);


    }

    private function renderjQueryCityData($flat, $pidKey, $idKey = null)
    {
        $grouped = array();

        foreach ($flat as $sub) {

            $parent_code = $sub[$pidKey];
//            unset($sub['level']);
            unset($sub[$pidKey]);
            $grouped[$parent_code][] = $sub;
        }

        $fnBuilder = function ($siblings) use (&$fnBuilder, $grouped, $idKey) {
            foreach ($siblings as $k => $sibling) {
                $id = $sibling[$idKey];

//                unset($sibling[$idKey]);

                if (isset($grouped[$id])) {
                    $sibling['children'] = $fnBuilder($grouped[$id]);
                }
//                else {
//                    $sibling['children'] = array();
//                }
                $siblings[$k] = $sibling;
            }

            return $siblings;
        };

        $tree = $fnBuilder($grouped[0]);

        return $tree;
    }

    public function foxUICityData($level = 3)
    {

        global $_W;
        global $_GPC;

        $state     = 1;
        $where_sql = "";

        $where_sql .= " WHERE `state` = :state";
        $params = array(
            ':state' => $state,
        );

        $where_sql .= " AND `level` <= :level";
        $params[':level'] = $level;


        $list = pdo_fetchall(
            "SELECT code,parent_code,text,level " .
            "FROM " . tablename($this->table_name) . $where_sql . " ORDER BY code ASC,parent_code ASC ", $params, 'code');


        // 方法2 内部类，优雅点
        $foxUICityData = $this->renderFoxUICityData($list, "parent_code", "code");
//        print_r($foxUICityData);
//        exit();

        return json_encode($foxUICityData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    }

    private function renderFoxUICityData($flat, $pidKey, $idKey = null)
    {


        $grouped = array();

        foreach ($flat as $sub) {

            $parent_code = $sub[$pidKey];
            unset($sub[$pidKey]);
            $grouped[$parent_code][] = $sub;
        }

        $fnBuilder = function ($siblings) use (&$fnBuilder, $grouped, $idKey) {
            foreach ($siblings as $k => $sibling) {
                
                $id = $sibling[$idKey];

//                unset($sibling[$idKey]);

                if (isset($grouped[$id])) {
                    $sibling['children'] = $fnBuilder($grouped[$id]);
                }


                if ($sibling['level'] < 3) {

                    unset($sibling[$idKey]);
                    unset($sibling['level']);
                    $siblings[$k] = $sibling;
//                    $siblings[$k] = array(
//                        'text' => $sibling['text']
//                    );

                } elseif ($sibling['level'] == 3) {
                    $siblings[$k] = $sibling['text'];
                }

            }

            return $siblings;
        };

        $tree = $fnBuilder($grouped[0]);

        return $tree;
    }


}