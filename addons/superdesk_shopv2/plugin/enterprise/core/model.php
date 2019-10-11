<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

class EnterpriseModel extends PluginModel
{
    static public $allPerms = array();
    static public $getLogTypes = array();
    static public $formatPerms = array();

    protected function build($condition, $params, $data)
    {
        foreach ($data as $key => $value) {
            if (($key == 'column') || ($key == 'field')) {
                continue;
            }
            if (stripos($key, 'in') === 0) {
                $key = str_ireplace('in', '', $key);
                if (is_array($value)) {
                    foreach ($value as &$val) {
                        $val = (int)$val;
                    }
                    unset($val);
                    $key = str_ireplace('in', '', $key);
                    $condition .= ' AND `' . $key . '` in(' . implode(',', $value) . ')';
                }
                continue;
            }
            if (stripos($key, 'orlike') === 0) {
                $key = str_ireplace('orlike', '', $key);
                if (is_array($value)) {
                    $condition .= ' OR (';
                    $i = 0;
                    foreach ($value as $k => $val) {
                        if ($i == 0) {
                            $condition .= '`' . $k . '`=:' . $k;
                            $params[':' . $k] = $val;
                        } else {
                            if ((stripos($val[0], 'and') !== false) || (stripos($val[0], 'or') !== false)) {
                                $condition .= ' ' . strtoupper($val[0]) . ' `' . $k . '` like :' . $k;
                                $params[':' . $k] = $val[1];
                            } else {
                                $condition .= ' AND `' . $k . '` like :' . $k;
                                $params[':' . $k] = '%' . $val . '%';
                            }
                        }
                        ++$i;
                    }
                    $condition .= ')';
                    continue;
                }
                $condition .= ' OR `' . $key . '` like :' . $key;
                $params[':' . $key] = '%' . $value . '%';
                continue;
            }
            if (stripos($key, 'like') === 0) {
                $key = str_ireplace('like', '', $key);
                if (is_array($value)) {
                    $condition .= ' AND (';
                    $i = 0;
                    foreach ($value as $k => $val) {
                        if ($i == 0) {
                            $condition .= '`' . $k . '` like :' . $k;
                            $params[':' . $k] = '%' . $val . '%';
                        } else {
                            if ((stripos($val[0], 'and') !== false) || (stripos($val[0], 'or') !== false)) {
                                $condition .= ' ' . strtoupper($val[0]) . ' `' . $k . '` like :' . $k;
                                $params[':' . $k] = $val[1];
                            } else {
                                $condition .= ' AND `' . $k . '` like :' . $k;
                                $params[':' . $k] = '%' . $val . '%';
                            }
                        }
                        ++$i;
                    }
                    $condition .= ')';
                    continue;
                }
                $condition .= ' AND `' . $key . '` like :' . $key;
                $params[':' . $key] = '%' . $value . '%';
                continue;
            }
            if (stripos($key, 'limit') === 0) {
                if (is_array($value)) {
                    if (isset($value[1])) {
                        $condition .= ' LIMIT ' . $value[0] . ',' . $value[1];
                    } else {
                        $condition .= ' LIMIT ' . $value[0];
                    }
                    continue;
                }
                $condition .= ' LIMIT ' . $value;
                continue;
            }
            if (stripos($key, 'orderby') === 0) {
                if (is_array($value)) {
                    $condition .= ' ORDER BY';
                    $i = 0;
                    foreach ($value as $k => $val) {
                        if ($i == 0) {
                            $condition .= ' ' . $k . ' ' . $val;
                        } else {
                            $condition .= ',' . $k . ' ' . $val;
                        }
                        ++$i;
                    }
                    continue;
                }
                $condition .= ' LIMIT ' . $value;
                continue;
            }
            if (stripos($key, 'or') === 0) {
                $key = str_ireplace('or', '', $key);
                if (is_array($value)) {
                    $condition .= ' OR (';
                    $i = 0;
                    foreach ($value as $k => $val) {
                        if ($i == 0) {
                            $condition .= '`' . $k . '`=:' . $k;
                            $params[':' . $k] = $val;
                        } else {
                            if ((stripos($val[0], 'and') !== false) || (stripos($val[0], 'or') !== false)) {
                                $condition .= ' ' . strtoupper($val[0]) . ' `' . $k . '`=:' . $k;
                                $params[':' . $k] = $val[1];
                            } else {
                                $condition .= ' AND `' . $k . '`=:' . $k;
                                $params[':' . $k] = $val;
                            }
                        }
                        ++$i;
                    }
                    $condition .= ')';
                    continue;
                }
                $condition .= ' OR `' . $key . '`=:' . $key;
                $params[':' . $key] = $value;
                continue;
            }
            if (stripos($key, 'and') === 0) {
                $key = str_ireplace('and', '', $key);
                if (is_array($value)) {
                    $condition .= ' AND (';
                    $i = 0;
                    foreach ($value as $k => $val) {
                        if ($i == 0) {
                            $condition .= '`' . $k . '`=:' . $k;
                            $params[':' . $k] = $val;
                        } else {
                            if ((stripos($val[0], 'and') !== false) || (stripos($val[0], 'or') !== false)) {
                                $condition .= ' ' . strtoupper($val[0]) . ' `' . $k . '`=:' . $k;
                                $params[':' . $k] = $val[1];
                            } else {
                                $condition .= ' AND `' . $k . '`=:' . $k;
                                $params[':' . $k] = $val;
                            }
                        }
                        ++$i;
                    }
                    $condition .= ')';
                    continue;
                }
                $condition .= ' OR `' . $key . '`=:' . $key;
                $params[':' . $key] = $value;
                continue;
            }
            $condition .= ' AND `' . $key . '`=:' . $key;
            $params[':' . $key] = $value;
        }
        if (isset($data['field'])) {
            if (is_array($data['field'])) {
                $field = '`' . implode('`,`', $data['field']) . '``';
            } else {
                $field = explode(',', $data['field']);
                foreach ($field as &$value) {
                    $temp = explode(' ', $value);
                    if (strpos($value, '(') === false) {
                        $value = str_replace($temp[0], '`' . $temp[0] . '`', $value);
                    }
                }
                unset($value);
                $field = implode(',', $field);
            }
        }

        return array(
            'condition' => $condition,
            'params'    => $params,
            'column'    => (isset($data['column']) ? $data['column'] : ''),
            'field'     => (isset($field) ? $field : '*')
        );
    }

    public function getGroups()
    {
        global $_W;
        return pdo_fetchall(
            ' select id,groupname ' .
            ' from ' . tablename('superdesk_shop_enterprise_group') .
            ' where uniacid=:uniacid and status=1 order by isdefault desc , id asc',
            array(
                ':uniacid' => $_W['uniacid']
            ),
            'id'
        );
    }

    public function getCategory($data = array())
    {
        global $_W;

        $condition = ' WHERE `uniacid` = :uniacid';

        $params = array(
            ':uniacid' => $_W['uniacid']
        );

        $res = $this->build($condition, $params, $data);

        return pdo_fetchall(
            ' select ' . $res['field'] .
            ' from ' . tablename('superdesk_shop_enterprise_category') . $res['condition'],
            $res['params'],
            $res['column']
        );
    }

    public function getCategorySwipe($data = array())
    {
        global $_W;

        $condition = ' WHERE `uniacid` = :uniacid';

        $params = array(
            ':uniacid' => $_W['uniacid']
        );

        $res = $this->build($condition, $params, $data);

        return pdo_fetchall(
            ' select ' . $res['field'] .
            ' from ' . tablename('superdesk_shop_enterprise_category_swipe') . $res['condition'],
            $res['params'],
            $res['column']
        );
    }

    public function getEnterprise($data = array())
    {
        global $_W;

        $condition = ' WHERE `uniacid` = :uniacid';

        $params = array(
            ':uniacid' => $_W['uniacid']
        );

        $res = $this->build($condition, $params, $data);

        return pdo_fetchall(
            ' select ' . $res['field'] .
            ' from ' . tablename('superdesk_shop_enterprise_user') . $res['condition'],
            $res['params'],
            $res['column']
        );
    }

    public function updateSet($values = array(), $enterprise_id = 0)
    {
        global $_W;
        global $_GPC;

        $enterprise_id = ((empty($enterprise_id) ? $_W['enterprise_id'] : $enterprise_id));

        $keys = 'enterprise_sets_' . $enterprise_id;

        $sets = $this->getSet('', $enterprise_id, true);

        foreach ($values as $key => $value) {

            foreach ($value as $k => $v) {

                $sets[$key][$k] = $v;
            }

        }

        pdo_update(
            'superdesk_shop_enterprise_user',
            array(
                'sets' => iserializer($sets)
            ),
            array(
                'id' => $enterprise_id
            )
        );

        m('cache')->set($keys, $sets);
    }

    public function refreshSet($enterprise_id = 0)
    {
        global $_W;

        $enterprise_id = ((empty($enterprise_id) ? $_W['enterprise_id'] : $enterprise_id));

        $key = 'enterprise_sets_' . $enterprise_id;

        $enterprise_set = pdo_fetch(
            ' select sets ' .
            ' from ' . tablename('superdesk_shop_enterprise_user') .
            ' where uniacid=:uniacid ' .
            '       and id=:id ' .
            ' limit 1 ',
            array(
                ':uniacid' => $_W['uniacid'],
                ':id'      => $enterprise_id
            )
        );

        $allset = iunserializer($enterprise_set['sets']);

        if (!is_array($allset)) {
            $allset = array();
        }

        m('cache')->set($key, $allset);

        return $allset;
    }

    public function getSet($name = '', $enterprise_id = 0, $refresh = false)
    {
        global $_W;
        global $_GPC;
        $enterprise_id = ((empty($enterprise_id) ? $_W['enterprise_id'] : intval($enterprise_id)));
        $key     = 'enterprise_sets_' . $enterprise_id;
        if ($refresh) {
            return $this->refreshSet($enterprise_id);
        }
        $allset = m('cache')->getArray($key);
        if (!empty($name) && empty($allset[$name])) {
            $allset = $this->refreshSet($enterprise_id);
        }
        return ($name ? $allset[$name] : $allset);
    }

    public function getGoodsTotals()
    {
        global $_W;
        return array(
            'sale'  => pdo_fetchcolumn(
                ' select count(1) ' .
                ' from ' . tablename('superdesk_shop_goods') .
                ' where checked=0 ' .
                '       and status=1 ' .
                '       and deleted=0 ' .
                '       and total>0 ' .
                '       and uniacid=:uniacid ' .
                '       and enterprise_id=:enterprise_id',
                array(
                    ':uniacid' => $_W['uniacid'],
                    ':enterprise_id' => $_W['enterprise_id']
                )
            ),
            'out'   => pdo_fetchcolumn(
                ' select count(1) ' .
                ' from ' . tablename('superdesk_shop_goods') .
                ' where checked=0 ' .
                '       and status=1 ' .
                '       and deleted=0 ' .
                '       and total=0 ' .
                '       and uniacid=:uniacid ' .
                '       and enterprise_id=:enterprise_id',
                array(
                    ':uniacid' => $_W['uniacid'],
                    ':enterprise_id' => $_W['enterprise_id']
                )
            ),
            'check' => pdo_fetchcolumn(
                ' select count(1) ' .
                ' from ' . tablename('superdesk_shop_goods') .
                ' where checked=1 ' .
                '       and deleted=0 ' .
                '       and uniacid=:uniacid ' .
                '       and enterprise_id=:enterprise_id',
                array(
                    ':uniacid' => $_W['uniacid'],
                    ':enterprise_id' => $_W['enterprise_id']
                )
            ),
            'stock' => pdo_fetchcolumn(
                ' select count(1) ' .
                ' from ' . tablename('superdesk_shop_goods') .
                ' where checked=0 ' .
                '       and status=0 ' .
                '       and deleted=0 ' .
                '       and uniacid=:uniacid ' .
                '       and enterprise_id=:enterprise_id',
                array(
                    ':uniacid' => $_W['uniacid'],
                    ':enterprise_id' => $_W['enterprise_id']
                )
            ),
            'cycle' => pdo_fetchcolumn(
                ' select count(1) ' .
                ' from ' . tablename('superdesk_shop_goods') .
                ' where deleted=1 ' .
                '       and uniacid=:uniacid ' .
                '       and enterprise_id=:enterprise_id',
                array(
                    ':uniacid' => $_W['uniacid'],
                    ':enterprise_id' => $_W['enterprise_id']
                )
            )
        );
    }

    public function getOrderTotals()
    {
        global $_W;

        $paras = array(
            ':uniacid' => $_W['uniacid'],
            ':enterprise_id' => $_W['enterprise_id']
        );

        $condition = 'and isparent=0 and ismr=0';

        $totals['all']      = pdo_fetchcolumn(
            ' SELECT COUNT(1) FROM ' . tablename('superdesk_shop_order') . '' . // TODO 标志 楼宇之窗 openid shop_order 不处理
            ' WHERE uniacid = :uniacid ' . $condition . ' and enterprise_id = :enterprise_id and deleted=0',
            $paras
        );
        $totals['status_1'] = pdo_fetchcolumn(
            ' SELECT COUNT(1) FROM ' . tablename('superdesk_shop_order') . '' . // TODO 标志 楼宇之窗 openid shop_order 不处理
            ' WHERE uniacid = :uniacid ' . $condition . ' and enterprise_id = :enterprise_id and status=-1 and refundtime=0 and deleted=0',
            $paras
        );
        $totals['status0']  = pdo_fetchcolumn(
            ' SELECT COUNT(1) FROM ' . tablename('superdesk_shop_order') . '' . // TODO 标志 楼宇之窗 openid shop_order 不处理
            ' WHERE uniacid = :uniacid ' . $condition . ' and enterprise_id = :enterprise_id and status=0 and paytype<>3 and deleted=0',
            $paras
        );
        $totals['status1']  = pdo_fetchcolumn(
            ' SELECT COUNT(1) FROM ' . tablename('superdesk_shop_order') . '' .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' WHERE uniacid = :uniacid ' . $condition . ' and enterprise_id = :enterprise_id and ( status=1 or ( status=0 and paytype=3) ) and deleted=0',
            $paras
        );
        $totals['status2']  = pdo_fetchcolumn(
            ' SELECT COUNT(1) FROM ' . tablename('superdesk_shop_order') . '' .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' WHERE uniacid = :uniacid ' . $condition . ' and enterprise_id = :enterprise_id and status=2 and deleted=0',
            $paras
        );
        $totals['status3']  = pdo_fetchcolumn(
            ' SELECT COUNT(1) FROM ' . tablename('superdesk_shop_order') . '' .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' WHERE uniacid = :uniacid ' . $condition . ' and enterprise_id = :enterprise_id and status=3 and deleted=0',
            $paras
        );
        $totals['status4']  = pdo_fetchcolumn(
            ' SELECT COUNT(1) FROM ' . tablename('superdesk_shop_order') . '' .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' WHERE uniacid = :uniacid ' . $condition . ' and enterprise_id = :enterprise_id and refundstate>0 and refundid<>0 and deleted=0',
            $paras
        );
        $totals['status5']  = pdo_fetchcolumn(
            ' SELECT COUNT(1) FROM ' . tablename('superdesk_shop_order') . '' .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' WHERE uniacid = :uniacid ' . $condition . ' and enterprise_id = :enterprise_id and refundtime<>0 and deleted=0',
            $paras
        );

        return $totals;
    }

    public function getListUser($list, $return = 'all')
    {
        global $_W;

        if (!is_array($list)) {
            return $this->getListUserOne($list);
        }

        $enterprise_ = array();

        foreach ($list as $value) {

            $enterprise_id = $value['enterprise_id'];

            if (empty($enterprise_id)) {
                $enterprise_id = 0;
            }

            if (empty($enterprise_[$enterprise_id])) {
                $enterprise_[$enterprise_id] = array();
            }

            array_push($enterprise_[$enterprise_id], $value);
        }

        if (!empty($enterprise_)) {

            $enterprise_ids = array_keys($enterprise_);

            $enterprise_user = pdo_fetchall(
                ' select * ' .
                ' from ' . tablename('superdesk_shop_enterprise_user') .
                ' where uniacid=:uniacid ' .
                '       and id in(' . implode(',', $enterprise_ids) . ')',

                array(':uniacid' => $_W['uniacid']), 'id');

            $all = array(
                'enterprise'      => $enterprise_,
                'enterprise_user' => $enterprise_user
            );

            return ($return == 'all' ? $all : $all[$return]);
        }
        return array();
    }

    public function getListUserOne($enterprise_id)
    {
        global $_W;

        $enterprise_id = intval($enterprise_id);

        if ($enterprise_id) {
            $enterprise_user = pdo_fetch(
                ' select * ' .
                ' from ' . tablename('superdesk_shop_enterprise_user') .
                ' where uniacid=:uniacid ' .
                '       and id=' . $enterprise_id,
                array(':uniacid' => $_W['uniacid']));
            return $enterprise_user;
        }

        return false;
    }

    public function allPerms()
    {
        if (empty($allPerms)) {

            $perms = array(
                'order'      => $this->perm_order(),
                'member'      => $this->perm_member(),
                'perm'       => $this->perm_perm(),
                //'statistics'       => $this->perm_statistics(),   //不确定要不要.暂时屏蔽
                'sysset'   => $this->perm_sysset()
            );

            self::$allPerms = $perms;
        }

        return self::$allPerms;
    }

    protected function perm_sysset()
    {
        return array(
            'text'          => '设置',
            'enterprise'           => array(
                'text'   => '设置',
                'main'   => '设置',
                'add'    => '添加-log',
                'edit'   => '修改-log',
                'delete' => '删除-log',
                'xxx'    => array(
                    'displayorder' => 'edit',
                    'enabled'      => 'edit'
                )
            )
        );
    }

    protected function perm_statistics()
    {
        return array(
            'text'            => '数据统计',
            'sale'            => array(
                'text'   => '销售统计',
                'main'   => '查看',
                'export' => '导出-log'
            ),
            'sale_analysis'   => array(
                'text' => '销售指标',
                'main' => '查看'
            ),
            'order'           => array(
                'text'   => '订单统计',
                'main'   => '查看',
                'export' => '导出-log'
            ),
            'goods'           => array(
                'text'   => '商品销售明细',
                'main'   => '查看',
                'export' => '导出-log'
            ),
            'goods_rank'      => array(
                'text'   => '商品销售排行',
                'main'   => '查看',
                'export' => '导出-log'
            ),
            'goods_trans'     => array(
                'text'   => '商品销售转化率',
                'main'   => '查看',
                'export' => '导出-log'
            ),
            'member_cost'     => array(
                'text'   => '会员消费排行',
                'main'   => '查看',
                'export' => '导出-log'
            ),
            'member_increase' => array(
                'text' => '会员增长趋势',
                'main' => '查看'
            )
        );
    }

    protected function perm_member()
    {
        return array(
            'text'      => '员工',
            'list'      => array(
                'text'   => '员工列表',
                'main'   => '浏览全部员工',
                'edit'   => '详情-log',
                'delete' => '删除-log',
            ),
            'detail'    => array(
                'text' => '员工详情',
                'edit' => '编辑',
                'edit_pwd' => '修改密码'
            ),
            'import'    => array(
                'text' => '自定义导入-log',
                'main' => '浏览页面',
                'xxx'  => array(
                    'save'        => 'main',
                    'delete'      => 'main',
                    'gettemplate' => 'main',
                    'reset'       => 'main'
                )
            ),
            'import_log'    => array(
                'text' => '导入日志',
                'main' => '浏览页面'
            ),
        );
    }

    protected function perm_order()
    {
        return array(
            'text'      => '订单',
            'detail'    => array(
                'text' => '订单详情',
                'edit' => '编辑'
            ),
            'list'      => array(
                'text'     => '订单管理',
                'main'     => '浏览全部订单',
//                'status_1' => '浏览关闭订单',
//                'status0'  => '浏览待付款订单',
//                'status1'  => '浏览已付款订单',
//                'status2'  => '浏览已发货订单',
//                'status3'  => '浏览完成的订单',
//                'status4'  => '浏览退货申请订单',
//                'status5'  => '浏览已退货订单'
            )
        );
    }

    protected function perm_perm()
    {
        return array(
            'text' => '权限系统',
            'log'  => array(
                'text' => '操作日志',
                'main' => '查看列表'),
            'role' => array(
                'text'   => '角色管理',
                'main'   => '查看列表',
                'add'    => '添加-log',
                'edit'   => '修改-log',
                'delete' => '删除-log',
                'xxx'    => array(
                    'status' => 'edit',
                    'query'  => 'main'
                )
            ),
            'user' => array(
                'text'   => '操作员管理',
                'main'   => '查看列表',
                'add'    => '添加-log',
                'edit'   => '修改-log',
                'delete' => '删除-log',
                'xxx'    => array(
                    'status' => 'edit'
                )
            )
        );
    }

    public function check_edit($permtype = '', $item = array())
    {
        if (empty($permtype)) {
            return false;
        }
        if (!$this->check_perm($permtype)) {
            return false;
        }
        if (empty($item['id'])) {
            $add_perm = $permtype . '.add';
            if (!$this->check($add_perm)) {
                return false;
            }
            return true;
        }
        $edit_perm = $permtype . '.edit';
        if (!$this->check($edit_perm)) {
            return false;
        }
        return true;
    }

    public function check_perm($permtypes = '')
    {
        global $_W;
        $check = true;
        if (empty($permtypes)) {
            return false;
        }
        if (!strexists($permtypes, '&') && !strexists($permtypes, '|')) {
            $check = $this->check($permtypes);
        } else if (strexists($permtypes, '&')) {
            $pts = explode('&', $permtypes);
            foreach ($pts as $pt) {
                $check = $this->check($pt);
                if ($check) {
                    continue;
                }
                break;
            }
        } else if (strexists($permtypes, '|')) {
            $pts = explode('|', $permtypes);
            foreach ($pts as $pt) {
                $check = $this->check($pt);
                if (!($check)) {
                    continue;
                }
                break;
            }
        }
        return $check;
    }

    private function check($permtype = '')
    {
        global $_W;
        global $_GPC;

        if ($_W['enterprise_isfounder'] == 1) {
            return true;
        }

        $uid = $_W['enterprise_uid'];

        if (empty($permtype)) {
            return false;
        }

        $user = pdo_fetch(
            ' select u.status as userstatus,r.status as rolestatus,r.perms as roleperms,u.roleid ' .
            ' from ' . tablename('superdesk_shop_enterprise_account') . ' u ' .// TODO 标志 楼宇之窗 openid shop_enterprise_account 不处理
            '       left join ' . tablename('superdesk_shop_enterprise_perm_role') . ' r on u.roleid = r.id ' .
            ' where u.id=:enterprise_uid ' .
            ' limit 1 ',
            array(
                ':enterprise_uid' => $uid
            )
        );

        if (empty($user) || empty($user['userstatus'])) {
            return false;
        }

        if (!empty($user['roleid']) && empty($user['rolestatus'])) {
            return false;
        }

        $perms = explode(',', $user['roleperms']);

        if (empty($perms)) {
            return false;
        }

        $is_xxx = $this->check_xxx($permtype);

        if ($is_xxx) {

            if (!in_array($is_xxx, $perms)) {
                return false;
            }

        } else if (!in_array($permtype, $perms)) {

            return false;

        }

        return true;
    }

    public function check_xxx($permtype)
    {
        if ($permtype) {
            $allPerm = $this->allPerms();
            $permarr = explode('.', $permtype);
            if (isset($permarr[3])) {
                $is_xxx = ((isset($allPerm[$permarr[0]][$permarr[1]][$permarr[2]]['xxx'][$permarr[3]]) ? $allPerm[$permarr[0]][$permarr[1]][$permarr[2]]['xxx'][$permarr[3]] : false));
            } else if (isset($permarr[2])) {
                $is_xxx = ((isset($allPerm[$permarr[0]][$permarr[1]]['xxx'][$permarr[2]]) ? $allPerm[$permarr[0]][$permarr[1]]['xxx'][$permarr[2]] : false));
            } else if (isset($permarr[1])) {
                $is_xxx = ((isset($allPerm[$permarr[0]]['xxx'][$permarr[1]]) ? $allPerm[$permarr[0]]['xxx'][$permarr[1]] : false));
            } else {
                $is_xxx = false;
            }
            if ($is_xxx) {
                $permarr = explode('.', $permtype);
                array_pop($permarr);
                $is_xxx = implode('.', $permarr) . '.' . $is_xxx;
            }
            return $is_xxx;
        }
        return false;
    }

    public function getLogName($type = '', $logtypes = NULL)
    {
        if (!$logtypes) {
            $logtypes = $this->getLogTypes();
        }
        foreach ($logtypes as $t) {
            if (!($t['value'] == $type)) {
                continue;
            }
            return $t['text'];
        }
        return '';
    }

    public function getLogTypes($all = false)
    {
        if (empty($getLogTypes)) {
            $perms = $this->allPerms();
            $array = array();
            foreach ($perms as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $ke => $val) {
                        if (!is_array($val)) {
                            if ($all) {
                                if ($ke == 'text') {
                                    $text = str_replace('-log', '', $value['text']);
                                } else {
                                    $text = str_replace('-log', '', $value['text'] . '-' . $val);
                                }
                                $array[] = array('text' => $text, 'value' => str_replace('.text', '', $key . '.' . $ke));
                            } else if (strexists($val, '-log')) {
                                $text = str_replace('-log', '', $value['text'] . '-' . $val);
                                if ($ke == 'text') {
                                    $text = str_replace('-log', '', $value['text']);
                                }
                                $array[] = array('text' => $text, 'value' => str_replace('.text', '', $key . '.' . $ke));
                            }
                        }
                        if (is_array($val) && ($ke != 'xxx')) {
                            foreach ($val as $k => $v) {
                                if (!is_array($v)) {
                                    if ($all) {
                                        if ($ke == 'text') {
                                            $text = str_replace('-log', '', $value['text'] . '-' . $val['text']);
                                        } else {
                                            $text = str_replace('-log', '', $value['text'] . '-' . $val['text'] . '-' . $v);
                                        }
                                        $array[] = array('text' => $text, 'value' => str_replace('.text', '', $key . '.' . $ke . '.' . $k));
                                    } else if (strexists($v, '-log')) {
                                        $text = str_replace('-log', '', $value['text'] . '-' . $val['text'] . '-' . $v);
                                        if ($k == 'text') {
                                            $text = str_replace('-log', '', $value['text'] . '-' . $val['text']);
                                        }
                                        $array[] = array('text' => $text, 'value' => str_replace('.text', '', $key . '.' . $ke . '.' . $k));
                                    }
                                }
                                if (is_array($v) && ($k != 'xxx')) {
                                    foreach ($v as $kk => $vv) {
                                        if (!is_array($vv)) {
                                            if ($all) {
                                                if ($ke == 'text') {
                                                    $text = str_replace('-log', '', $value['text'] . '-' . $val['text'] . '-' . $v['text']);
                                                } else {
                                                    $text = str_replace('-log', '', $value['text'] . '-' . $val['text'] . '-' . $v['text'] . '-' . $vv);
                                                }
                                                $array[] = array('text' => $text, 'value' => str_replace('.text', '', $key . '.' . $ke . '.' . $k . '.' . $kk));
                                            } else if (strexists($vv, '-log')) {
                                                if (empty($val['text'])) {
                                                    $text = str_replace('-log', '', $value['text'] . '-' . $v['text'] . '-' . $vv);
                                                } else {
                                                    $text = str_replace('-log', '', $value['text'] . '-' . $val['text'] . '-' . $v['text'] . '-' . $vv);
                                                }
                                                if ($kk == 'text') {
                                                    $text = str_replace('-log', '', $value['text'] . '-' . $val['text'] . '-' . $v['text']);
                                                }
                                                $array[] = array('text' => $text, 'value' => str_replace('.text', '', $key . '.' . $ke . '.' . $k . '.' . $kk));
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            self::$getLogTypes = $array;
        }
        return self::$getLogTypes;
    }

    public function log($type = '', $op = '')
    {
        global $_W;

        $this->check_xxx($type);

        if ($is_xxx = $this->check_xxx($type)) {
            $type = $is_xxx;
        }

        static $_logtypes;

        if (!$_logtypes) {
            $_logtypes = $this->getLogTypes();
        }

        $log = array(
            'uniacid'    => $_W['uniacid'],
            'uid'        => $_W['enterprise_uid'],
            'enterprise_id'    => $_W['enterprise_id'],
            'name'       => $this->getLogName($type, $_logtypes),
            'type'       => $type,
            'op'         => $op,
            'ip'         => CLIENT_IP,
            'createtime' => time()
        );

        pdo_insert('superdesk_shop_enterprise_perm_log', $log);
    }

    public function formatPerms()
    {
        if (empty($formatPerms)) {
            $perms = $this->allPerms();
            $array = array();
            foreach ($perms as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $ke => $val) {
                        if (!is_array($val)) {
                            $array['parent'][$key][$ke] = $val;
                        }
                        if (is_array($val) && ($ke != 'xxx')) {
                            foreach ($val as $k => $v) {
                                if (!is_array($v)) {
                                    $array['son'][$key][$ke][$k] = $v;
                                }
                                if (is_array($v) && ($k != 'xxx')) {
                                    foreach ($v as $kk => $vv) {
                                        if (!is_array($vv)) {
                                            $array['grandson'][$key][$ke][$k][$kk] = $vv;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            self::$formatPerms = $array;
        }
        return self::$formatPerms;
    }

    public function select_operator($enterprise_id = 0)
    {
        global $_W;

        $enterprise_id = ((empty($enterprise_id) ? $_W['enterprise_id'] : intval($enterprise_id)));

        $total = pdo_fetchcolumn(
            ' SELECT count(*) ' .
            ' FROM ' . tablename('superdesk_shop_enterprise_account') .// TODO 标志 楼宇之窗 openid shop_enterprise_account 不处理
            ' WHERE 1 ' .
            '       and uniacid = :uniacid ' .
            '       and enterprise_id = :enterprise_id ' .
            '       and isfounder<>1',
            array(
                ':uniacid' => $_W['uniacid'],
                ':enterprise_id' => $_W['enterprise_id']
            )
        );
        return $total;
    }

    public function getEnoughs($set)
    {
        global $_W;

        $allenoughs = array();
        $enoughs    = $set['enoughs'];

        if ((0 < floatval($set['enoughmoney'])) && (0 < floatval($set['enoughdeduct']))) {

            $allenoughs[] = array(
                'enough' => floatval($set['enoughmoney']),
                'money'  => floatval($set['enoughdeduct'])
            );

        }

        if (is_array($enoughs)) {
            foreach ($enoughs as $e) {
                if ((0 < floatval($e['enough'])) && (0 < floatval($e['give']))) {

                    $allenoughs[] = array(
                        'enough' => floatval($e['enough']),
                        'money'  => floatval($e['give'])
                    );

                }
            }
        }
        usort($allenoughs, 'enterprise_sort_enoughs');
        return $allenoughs;
    }

    public function getEnterprises($enterprise_array)
    {
        $enterprise_s = array();
        if (!empty($enterprise_array)) {
            foreach ($enterprise_array as $key => $value) {
                $enterprise_id = $key;
                if (0 < $enterprise_id) {
                    $enterprise_s[$enterprise_id]['enterprise_id'] = $enterprise_id;
                    $enterprise_s[$enterprise_id]['goods']   = $value['goods'];
                    $enterprise_s[$enterprise_id]['ggprice'] = $value['ggprice'];
                }
            }
        }
        return $enterprise_s;
    }

    public function getUserTotals()
    {
        global $_W;
        global $_GPC;

        $totals = array(
            'reg0'  => pdo_fetchcolumn(
                ' select count(*) ' .
                ' from ' . tablename('superdesk_shop_enterprise_reg') .// TODO 标志 楼宇之窗 openid shop_enterprise_reg 不处理
                ' where uniacid=:uniacid ' .
                '       and status=0 ' .
                ' limit 1',
                array(
                    ':uniacid' => $_W['uniacid']
                )
            ),
            'reg_1' => pdo_fetchcolumn(
                ' select count(*) ' .
                ' from ' . tablename('superdesk_shop_enterprise_reg') .// TODO 标志 楼宇之窗 openid shop_enterprise_reg 不处理
                ' where uniacid=:uniacid ' .
                '       and status=-1 ' .
                ' limit 1',
                array(
                    ':uniacid' => $_W['uniacid']
                )
            ),
            'user0' => pdo_fetchcolumn(
                ' select count(*) ' .
                ' from ' . tablename('superdesk_shop_enterprise_user') .
                ' where uniacid=:uniacid ' .
                '       and status=0 ' .
                ' limit 1',
                array(
                    ':uniacid' => $_W['uniacid']
                )
            ),
            'user1' => pdo_fetchcolumn(
                ' select count(*) ' .
                ' from ' . tablename('superdesk_shop_enterprise_user') .
                ' where uniacid=:uniacid ' .
                '       and status=1 ' .
                ' limit 1',
                array(
                    ':uniacid' => $_W['uniacid']
                )
            ),
            'user2' => pdo_fetchcolumn(
                ' select count(*) ' .
                ' from ' . tablename('superdesk_shop_enterprise_user') .
                ' where uniacid=:uniacid ' .
                '       and status=2 ' .
                ' limit 1',
                array(
                    ':uniacid' => $_W['uniacid']
                )
            ),
            'user3' => pdo_fetchcolumn(
                ' select count(*) ' .
                ' from ' . tablename('superdesk_shop_enterprise_user') .
                ' where uniacid=:uniacid ' .
                '       and status=1 ' .
                '       and TIMESTAMPDIFF(DAY,now(),FROM_UNIXTIME(accounttime))<=30 ' .
                ' limit 1',
                array(
                    ':uniacid' => $_W['uniacid']
                )
            )
        );

        return $totals;
    }

    public function getClearTotals()
    {
        global $_W;
        global $_GPC;

        $totals = array(
            'status0' => pdo_fetchcolumn(
                ' select count(*) ' .
                ' from ' . tablename('superdesk_shop_enterprise_clearing') .
                ' where uniacid=:uniacid ' .
                '       and status=0 ' .
                ' limit 1',
                array(
                    ':uniacid' => $_W['uniacid']
                )
            ),
            'status1' => pdo_fetchcolumn(
                ' select count(*) ' .
                ' from ' . tablename('superdesk_shop_enterprise_clearing') .
                ' where uniacid=:uniacid ' .
                '       and status=1 ' .
                ' limit 1',
                array(
                    ':uniacid' => $_W['uniacid']
                )
            ),
            'status2' => pdo_fetchcolumn(
                ' select count(*) ' .
                ' from ' . tablename('superdesk_shop_enterprise_clearing') .
                ' where uniacid=:uniacid ' .
                '       and status=2 ' .
                ' limit 1',
                array(
                    ':uniacid' => $_W['uniacid']
                )
            )
        );

        return $totals;
    }

    public function getEnterpriseOrderTotals($type = 0)
    {
        global $_W;

        $condition = ' and o.uniacid=:uniacid and o.enterprise_id>0 and o.isparent=0';

        if ($type == 0) {
            $condition .= ' and o.status >= 0 ';
        } else if ($type == 1) {
            $condition .= ' and o.status >= 1 ';
        } else if ($type == 3) {
            $condition .= ' and o.status = 3 ';
        }

        $params = array(':uniacid' => $_W['uniacid']);
        $condition .= ' and o.deleted = 0 ';

        $sql =
            ' select sum(o.price) as totalmoney ' .
            ' from ' . tablename('superdesk_shop_order') . ' o ' .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' left join ' . tablename('superdesk_shop_enterprise_user') . ' u on u.id = o.enterprise_id ' .
            ' where 1 ' .
            $condition . ' ';

        $price = pdo_fetch($sql, $params);

        $totalmoney = round($price['totalmoney'], 2);

        $sql =
            ' select count(o.id) as totalcount ' .
            ' from ' . tablename('superdesk_shop_order') . ' o ' .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' left join ' . tablename('superdesk_shop_enterprise_user') . ' u on u.id = o.enterprise_id ' .
            ' where 1 ' .
            $condition . ' ';

        $totalcount = pdo_fetchcolumn($sql, $params);

        $data               = array();
        $data['totalmoney'] = $totalmoney;
        $data['totalcount'] = $totalcount;

        return $data;
    }

    public function sendMessage($sendData, $message_type)
    {
        $notice = m('common')->getPluginset('enterprise');

        $tm = $notice['tm'];

        $templateid = $tm['templateid'];

        if (($message_type == 'enterprise_apply') && empty($usernotice['enterprise_apply'])) {

            $tm['msguser'] = 0;

            $data = array(
                '[企业名称]' => $sendData['enterprise_name'],
                '[主营项目]' => $sendData['salecate'],
                '[联系人]'  => $sendData['realname'],
                '[手机号]'  => $sendData['mobile'],
                '[申请时间]' => date('Y-m-d H:i:s', $sendData['applytime'])
            );

            $message = array(
                'keyword1' => (!empty($tm['enterprise_applytitle']) ? $tm['enterprise_applytitle'] : '企业入驻申请'),
                'keyword2' => (!empty($tm['enterprise_apply']) ? $tm['enterprise_apply'] : '[企业名称]在[申请时间]提交了入驻申请，请到后台查看~')
            );

            return $this->sendNotice($tm, 'enterprise_apply_advanced', $data, $message);
        }
        if (($message_type == 'enterprise_apply_money') && empty($usernotice['enterprise_apply_money'])) {

            $tm['msguser'] = 1;

            $data = array(
                '[企业名称]' => $sendData['enterprise_name'],
                '[金额]'   => $sendData['money'],
                '[联系人]'  => $sendData['realname'],
                '[手机号]'  => $sendData['mobile'],
                '[申请时间]' => date('Y-m-d H:i:s', $sendData['applytime'])
            );

            $message = array(
                'keyword1' => (!empty($tm['enterprise_applymoneytitle']) ? $tm['enterprise_applymoneytitle'] : '企业提现申请'),
                'keyword2' => (!empty($tm['enterprise_applymoney']) ? $tm['enterprise_applymoney'] : '[企业名称]在[申请时间]提交了提现申请,提现金额' . $sendData['money'] . '.[联系人] [手机号].请到后台查看~')
            );

            return $this->sendNotice($tm, 'enterprise_apply_advanced', $data, $message);
        }
    }

    protected function sendNotice($tm, $tag, $datas, $message)
    {
        global $_W;

        if (!empty($tm['is_advanced']) && !empty($tm[$tag])) {

            $advanced_template = pdo_fetch(
                ' select * ' .
                ' from ' . tablename('superdesk_shop_member_message_template') .
                ' where id=:id ' .
                '       and uniacid=:uniacid ' .
                ' limit 1',
                array(
                    ':id'      => $tm[$tag],
                    ':uniacid' => $_W['uniacid']
                )
            );
            if (!empty($advanced_template)) {

                $url = ((!empty($advanced_template['url']) ? $this->replaceArray($datas, $advanced_template['url']) : ''));

                $advanced_message = array(
                    'first'  => array('value' => $this->replaceArray($datas, $advanced_template['first']), 'color' => $advanced_template['firstcolor']),
                    'remark' => array('value' => $this->replaceArray($datas, $advanced_template['remark']), 'color' => $advanced_template['remarkcolor'])
                );

                $data = iunserializer($advanced_template['data']);

                foreach ($data as $d) {
                    $advanced_message[$d['keywords']] = array('value' => $this->replaceArray($datas, $d['value']), 'color' => $d['color']);
                }

                $tm['templateid'] = $advanced_template['template_id'];
                $this->sendMoreAdvanced($tm, $tag, $advanced_message, $url);

            }
        } else {
            $tag = str_replace('_advanced', '', $tag);
            $this->sendMore($tm, $message, $datas);
        }
        return true;
    }

    protected function sendMore($tm, $message, $datas)
    {
        $message['keyword2'] = $this->replaceArray($datas, $message['keyword2']);

        $msg = array(
            'keyword1' => array('value' => $message['keyword1'], 'color' => '#73a68d'),
            'keyword2' => array('value' => $message['keyword2'], 'color' => '#73a68d')
        );

        if ($tm['msguser'] == 1) {
            $openid = $tm['applyopenid'];
        } else {
            $openid = $tm['openid'];
        }

        if (!empty($openid)) {

            foreach ($openid as $openid) {

                $send = m('message')->sendTplNotice($openid, $tm['templateid'], $msg);

                if (is_error($send)) {

                    m('message')->sendCustomNotice($openid, $msg);

                }
            }
        }
    }

    protected function sendMoreAdvanced($tm, $tag, $msg, $url)
    {
        if ($tm['msguser'] == 1) {
            $openid = $tm['applyopenid'];
        } else {
            $openid = $tm['openid'];
        }
        if (!empty($openid)) {
            foreach ($openid as $openid) {
                if (!empty($tm[$tag]) && !empty($tm['templateid'])) {
                    m('message')->sendTplNotice($openid, $tm['templateid'], $msg, $url);
                } else {
                    m('message')->sendCustomNotice($openid, $msg, $url);
                }
            }
        }
    }

    protected function replaceArray(array $array, $message)
    {
        foreach ($array as $key => $value) {
            $message = str_replace($key, $value, $message);
        }
        return $message;
    }

    public function getEnterpriseOrderTotalPrice($enterprise_id)
    {
        global $_W;
        $data = array();

        $list = $this->getEnterprisePrice($enterprise_id, 1);

        $data['status0'] = $list['realprice'];

        $params = array(
            ':uniacid' => $_W['uniacid'],
            ':enterprise_id' => $enterprise_id
        );

        $condition = ' and uniacid=:uniacid and enterprise_id=:enterprise_id';


        $sql   =
            ' select sum(realprice) as totalmoney ' .
            ' from ' . tablename('superdesk_shop_enterprise_bill') .
            ' where 1 ' .
            $condition .
            ' and status=1';
        $price = pdo_fetch($sql, $params);

        $data['status1'] = round($price['totalmoney'], 2);

        $sql =
            ' select sum(realprice) as totalmoney ' .
            ' from ' . tablename('superdesk_shop_enterprise_bill') .
            ' where 1 ' .
            $condition .
            ' and status=2';

        $price = pdo_fetch($sql, $params);

        $data['status2'] = round($price['totalmoney'], 2);

        $sql =
            ' select sum(finalprice) as totalmoney ' .
            ' from ' . tablename('superdesk_shop_enterprise_bill') .
            ' where 1 ' .
            $condition .
            ' and status=3';

        $price = pdo_fetch($sql, $params);

        $data['status3'] = round($price['totalmoney'], 2);

        return $data;
    }

    public function getEnterprisePrice($enterprise_id, $flag = 0)
    {
        global $_W;
        $enterprise_data = m('common')->getPluginset('enterprise');
        if (!empty($enterprise_data['deduct_commission'])) {
            $deduct_commission = 1;
        } else {
            $deduct_commission = 0;
        }
        $condition = ' and u.uniacid=:uniacid and u.id=:enterprise_id and o.status=3 and o.isparent=0 and o.enterprise_apply<=0';

        $params = array(
            ':uniacid' => $_W['uniacid'],
            ':enterprise_id' => $enterprise_id
        );

        $con =
            'u.id,u.enterprise_name,u.payrate,sum(o.price) price,sum(o.goodsprice) goodsprice,sum(o.dispatchprice) dispatchprice,sum(o.discountprice) discountprice,sum(o.deductprice) deductprice,sum(o.deductcredit2) deductcredit2,sum(o.isdiscountprice) isdiscountprice,sum(o.deductenough) deductenough,sum(o.enterprise_deductenough) enterprise_deductenough,sum(o.enterprise_isdiscountprice) enterprise_isdiscountprice,sum(o.changeprice) changeprice';

        $tradeset = m('common')->getSysset('trade');

        $refunddays = intval($tradeset['refunddays']);

        if (0 < $refunddays) {
            $finishtime = intval(time() - ($refunddays * 3600 * 24));
            $condition .= ' and o.finishtime<:finishtime';
            $params['finishtime'] = $finishtime;
        }

        $sql =
            ' select ' . $con .
            ' from ' . tablename('superdesk_shop_enterprise_user') . ' u ' .
            ' left join ' . tablename('superdesk_shop_order') . ' o on u.id=o.enterprise_id' .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' where 1 ' .
            $condition .
            ' limit 1';

        $list             = pdo_fetch($sql, $params);
        $enterprise_couponprice = pdo_fetchcolumn(
            ' select sum(o.couponprice) ' .
            ' from ' . tablename('superdesk_shop_enterprise_user') . ' u ' .
            ' left join ' . tablename('superdesk_shop_order') . ' o on u.id=o.enterprise_id' .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' where o.couponenterprise_id>0 ' . $condition .
            ' limit 1',
            $params
        );

        if (0 < $flag) {
            $sql        =
                ' select o.id,o.agentid from ' . tablename('superdesk_shop_enterprise_user') . ' u ' .
                ' left join ' . tablename('superdesk_shop_order') . ' o on u.id=o.enterprise_id' . // TODO 标志 楼宇之窗 openid shop_order 不处理
                ' where 1 ' . $condition;
            $order      = pdo_fetchall($sql, $params);
            $orderids   = array();
            $commission = 0;
            if (!empty($order)) {
                foreach ($order as $k => $v) {
                    $orderids[] = $v['id'];
                    $commission += m('order')->getOrderCommission($v['id'], $v['agentid']);
                }
            }
            $list['orderids']   = $orderids;
            $list['commission'] = $commission;
        }
        $list['orderprice'] = $list['goodsprice'] + $list['dispatchprice'] + $list['changeprice'];
        $list['realprice']  = $list['orderprice'] - $list['enterprise_deductenough'] - $list['enterprise_isdiscountprice'] - $enterprise_couponprice;
        if ($deduct_commission) {
            $list['realprice'] -= $list['commission'];
        }
        $list['realpricerate']    = ((100 - floatval($list['payrate'])) * $list['realprice']) / 100;
        $list['enterprise_couponprice'] = $enterprise_couponprice;
        return $list;
    }

    public function getEnterprisePriceList($enterprise_id, $orderid = 0, $flag = 0)
    {
        global $_W;

        $enterprise_data = m('common')->getPluginset('enterprise');

        if (!empty($enterprise_data['deduct_commission'])) {
            $deduct_commission = 1;
        } else {
            $deduct_commission = 0;
        }

        $condition = ' and u.uniacid=:uniacid and u.id=:enterprise_id and o.status=3 and o.isparent=0 ';
        $params    = array(':uniacid' => $_W['uniacid'], ':enterprise_id' => $enterprise_id);
        switch ($flag) {
            case 0:
                $condition .= ' and o.enterprise_apply <= 0';
                break;
            case 1:
                $condition .= ' and o.enterprise_apply = 1';
                break;
            case 2:
                $condition .= ' and o.enterprise_apply = 2';
                break;
            case 3:
                $condition .= ' and o.enterprise_apply = 3';
                break;
        }

        $tradeset   = m('common')->getSysset('trade');
        $refunddays = intval($tradeset['refunddays']);

        if (0 < $refunddays) {

            $finishtime = intval(time() - ($refunddays * 3600 * 24));
            $condition .= ' and o.finishtime<:finishtime';
            $params['finishtime'] = $finishtime;

        }

        if (!empty($orderid)) {

            $condition .= ' and o.id=:id Limit 1';
            $params['id'] = $orderid;

        }
        $con =
            'o.id,u.enterprise_name,u.payrate,o.price,o.goodsprice,o.dispatchprice,discountprice,' .
            'o.deductprice,o.deductcredit2,o.isdiscountprice,o.deductenough,o.changeprice,o.agentid,' .
            'o.enterprise_deductenough,o.enterprise_isdiscountprice,o.couponenterprise_id,o.couponprice,o.couponenterprise_id,o.ordersn,o.finishtime,o.enterprise_apply';
        $sql =
            ' select ' . $con .
            ' from ' . tablename('superdesk_shop_enterprise_user') . ' u ' .
            ' left join ' . tablename('superdesk_shop_order') . ' o on u.id=o.enterprise_id' .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' where 1 ' .
            $condition;

        $order = pdo_fetchall($sql, $params);

        $enterprise_couponprice = 0;

        if (0 < $list['couponenterprise_id']) {
            $enterprise_couponprice = $list['couponprice'];
        }

        $list['commission'] = m('order')->getOrderCommission($list['id'], $list['agentid']);
        $list['orderprice'] = $list['goodsprice'] + $list['dispatchprice'] + $list['changeprice'];
        $list['realprice']  = $list['orderprice'] - $list['enterprise_deductenough'] - $list['enterprise_isdiscountprice'] - $enterprise_couponprice;

        if ($deduct_commission) {
            $list['realprice'] -= $list['commission'];
        }

        $list['realpricerate']    = ((100 - floatval($list['payrate'])) * $list['realprice']) / 100;
        $list['enterprise_couponprice'] = $enterprise_couponprice;

        unset($list);
        if (!empty($orderid)) {
            return $order[0];
        }

        return $order;
    }

    public function getOneApply($id)
    {
        global $_W;

        $condition = ' and u.uniacid=:uniacid and b.id=:id';

        $params = array(
            ':uniacid' => $_W['uniacid'],
            ':id'      => $id
        );

        $sql  =
            ' select b.*,u.enterprise_name,u.realname,u.mobile ' .
            ' from ' . tablename('superdesk_shop_enterprise_bill') . ' b ' .
            ' left join ' . tablename('superdesk_shop_enterprise_user') . ' u on b.enterprise_id = u.id' .
            ' where 1 ' . $condition .
            ' Limit 1';
        $data = pdo_fetch($sql, $params);
        return $data;
    }

    public function getPassApplyPrice($enterprise_id, $orderids)
    {
        global $_W;

        $data                  = array();
        $data['realprice']     = 0;
        $data['realpricerate'] = 0;
        $data['orderprice']    = 0;

        if (!empty($orderids)) {

            foreach ($orderids as $key => $orderid) {

                $item = $this->getEnterprisePriceList($enterprise_id, $orderid, 1);
                $data['realprice'] += $item['realprice'];
                $data['realpricerate'] += $item['realpricerate'];
                $data['orderprice'] += $item['orderprice'];

            }
        }

        return $data;
    }

    public function getAllUserTotals()
    {
        global $_W;
        $totals = pdo_fetchcolumn(
            ' select count(1) ' .
            ' from ' . tablename('superdesk_shop_enterprise_user') .
            ' where uniacid=:uniacid',
            array(
                ':uniacid' => $_W['uniacid']
            )
        );
        return $totals;
    }

    public function checkMaxEnterpriseUser($type = 0)
    {
        global $_W;
        $totals    = $this->getAllUserTotals();
        $max_enterprise_ = $this->getMaxEnterpriseUser();
        $flag      = 0;
        if (0 < $max_enterprise_) {
            if ($max_enterprise_ <= $totals) {
                if ($type == 1) {
                    $flag = 1;
                } else {
                    show_json(0, '已经达到最大企业数量,不能再添加企业.');
                }
            }
        }
        return $flag;
    }

    public function getMaxEnterpriseUser()
    {
        global $_W;

        $data = pdo_fetch(
            ' select datas ' .
            ' from ' . tablename('superdesk_shop_perm_plugin') .
            ' where acid=:acid ' .
            ' Limit 1',
            array(
                ':acid' => $_W['uniacid']
            )
        );

        $max_enterprise_ = 0;

        if (!empty($data['datas'])) {

            $datas     = json_decode($data['datas']);
            $max_enterprise_ = $datas->max_enterprise_;

            if (empty($max_enterprise_)) {

                $max_enterprise_ = 0;

            }
        }

        return $max_enterprise_;
    }

    public function getInsertData($fields, $memberdata)
    {
        global $_W;
        return '';
    }

    public function tempData($type, $enterprise_id = 0)
    {
        global $_W;
        global $_GPC;
        $enterprise_id = ((empty($enterprise_id) ? $_W['enterprise_id'] : $enterprise_id));
        $pindex  = max(1, intval($_GPC['page']));
        $psize   = 20;

        $condition = ' uniacid = :uniacid and type=:type and enterprise_id=:enterprise_id';
        $params    = array(':uniacid' => $_W['uniacid'], ':type' => $type, ':enterprise_id' => $enterprise_id);

        if (!empty($_GPC['keyword'])) {
            $_GPC['keyword'] = trim($_GPC['keyword']);
            $condition .= ' AND expressname LIKE :expressname';
            $params[':expressname'] = '%' . trim($_GPC['keyword']) . '%';
        }

        $sql =
            ' SELECT id,expressname,expresscom,isdefault ' .
            ' FROM ' . tablename('superdesk_shop_exhelper_express') .
            ' where  1 and ' .
            $condition .
            ' ORDER BY isdefault desc, id DESC ' .
            ' LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize;

        $list = pdo_fetchall($sql, $params);

        $total = pdo_fetchcolumn(
            ' SELECT COUNT(*) ' .
            ' FROM ' . tablename('superdesk_shop_exhelper_express') .
            ' where 1 and ' .
            $condition,
            $params
        );

        $pager = pagination($total, $pindex, $psize);

        return array(
            'list'  => $list,
            'total' => $total,
            'pager' => $pager,
            'type'  => $type
        );
    }

    public function setDefault($id, $type, $enterprise_id = 0)
    {
        global $_W;

        $enterprise_id = ((empty($enterprise_id) ? $_W['enterprise_id'] : $enterprise_id));

        $item = pdo_fetch(
            ' SELECT id,expressname,type ' .
            ' FROM ' . tablename('superdesk_shop_exhelper_express') .
            ' WHERE id=:id ' .
            '       and type=:type ' .
            '       AND uniacid=:uniacid ' .
            '       and enterprise_id=:enterprise_id',
            array(
                ':id'      => $id,
                ':type'    => $type,
                ':uniacid' => $_W['uniacid'],
                ':enterprise_id' => $enterprise_id
            )
        );

        if (!empty($item)) {

            pdo_update(
                'superdesk_shop_exhelper_express',
                array(
                    'isdefault' => 0
                ),
                array(
                    'type'    => $type,
                    'uniacid' => $_W['uniacid'],
                    'enterprise_id' => $enterprise_id
                )
            );

            pdo_update(
                'superdesk_shop_exhelper_express',
                array(
                    'isdefault' => 1
                ),
                array(
                    'id'      => $id,
                    'enterprise_id' => $enterprise_id
                )
            );

            if ($type == 1) {
                mplog(
                    'enterprise.exhelper.temp.express.setdefault',
                    '设置默认快递单 ID: ' . $item['id'] . '， 模板名称: ' . $item['expressname'] . ' ');
                return NULL;
            }

            if ($type == 2) {
                mplog(
                    'enterprise.exhelper.temp.invoice.setdefault',
                    '设置默认发货单 ID: ' . $item['id'] . '， 模板名称: ' . $item['expressname'] . ' ');
            }
        }
    }

    public function tempDelete($id, $type, $enterprise_id = 0)
    {
        global $_W;

        $enterprise_id = ((empty($enterprise_id) ? $_W['enterprise_id'] : $enterprise_id));

        $items = pdo_fetchall(
            ' SELECT id,expressname ' .
            ' FROM ' . tablename('superdesk_shop_exhelper_express') .
            ' WHERE id in( ' . $id . ' ) ' .
            '       and type=:type ' .
            '       and uniacid=:uniacid ' .
            '       and enterprise_id=:enterprise_id',
            array(
                ':type'    => $type,
                ':uniacid' => $_W['uniacid'],
                ':enterprise_id' => $enterprise_id
            )
        );

        foreach ($items as $item) {

            pdo_delete(
                'superdesk_shop_exhelper_express',
                array(
                    'id'      => $item['id'],
                    'uniacid' => $_W['uniacid'],
                    'enterprise_id' => $enterprise_id
                )
            );

            if ($type == 1) {
                mplog(
                    'enterprise.exhelper.temp.express.delete',
                    '删除 快递助手 快递单模板 ID: ' . $item['id'] . '， 模板名称: ' . $item['expressname'] . ' ');
            } else if ($type == 2) {
                mplog(
                    'enterprise.exhelper.temp.invoice.delete',
                    '删除 快递助手 发货单模板 ID: ' . $item['id'] . '， 模板名称: ' . $item['expressname'] . ' ');
            }
        }
    }

    public function getTemp($enterprise_id = 0)
    {
        global $_W;
        global $_GPC;

        $enterprise_id = ((empty($enterprise_id) ? $_W['enterprise_id'] : $enterprise_id));

        $temp_sender = pdo_fetchall(
            ' SELECT id,isdefault,sendername,sendertel ' .
            ' FROM ' . tablename('superdesk_shop_exhelper_senduser') .
            ' WHERE uniacid=:uniacid ' .
            '       and enterprise_id=:enterprise_id ' .
            ' order by isdefault desc ',
            array(
                ':uniacid' => $_W['uniacid'],
                ':enterprise_id' => $enterprise_id
            )
        );

        $temp_express = pdo_fetchall(
            ' SELECT id,type,isdefault,expressname ' .
            ' FROM ' . tablename('superdesk_shop_exhelper_express') .
            ' WHERE type=1 ' .
            '       and uniacid=:uniacid ' .
            '       and enterprise_id=:enterprise_id ' .
            ' order by isdefault desc ',
            array(
                ':uniacid' => $_W['uniacid'],
                ':enterprise_id' => $enterprise_id
            )
        );

        $temp_invoice = pdo_fetchall(
            ' SELECT id,type,isdefault,expressname ' .
            ' FROM ' . tablename('superdesk_shop_exhelper_express') .
            ' WHERE type=2 ' .
            '       and uniacid=:uniacid ' .
            '       and enterprise_id=:enterprise_id ' .
            ' order by isdefault desc ',
            array(
                ':uniacid' => $_W['uniacid'],
                ':enterprise_id' => $enterprise_id
            )
        );

        return array(
            'temp_sender'  => $temp_sender,
            'temp_express' => $temp_express,
            'temp_invoice' => $temp_invoice
        );
    }
}

function enterprise_sort_enoughs($a, $b)
{
    $enough1 = floatval($a['enough']);
    $enough2 = floatval($b['enough']);
    if ($enough1 == $enough2) {
        return 0;
    }
    return ($enough1 < $enough2 ? 1 : -1);
}

?>