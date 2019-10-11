<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Shop_SuperdeskShopV2Model
{
    public function getCategory($refresh = false)
    {
        global $_W;

        $allcategory = m('cache')->getArray('allcategory');

        if (empty($allcategory) || $refresh) {

            $parents  = array();
            $children = array();

            $category = pdo_fetchall(
                ' SELECT * ' .
                ' FROM ' . tablename('superdesk_shop_category') .
                ' WHERE uniacid =:uniacid ' .
                '       AND enabled=1 ' .
                ' ORDER BY parentid ASC, displayorder DESC',
                array(
                    ':uniacid' => $_W['uniacid']
                )
            );

            foreach ($category as $index => $row) {
                if (!empty($row['parentid']) || $row['parentid'] != 0) {
                    $children[$row['parentid']][] = $row;
                    unset($category[$index]);
                } else {
                    $parents[] = $row;
                }
            }

            $allcategory = array(
                'parent' => $parents,
                'children' => $children
            );

            m('cache')->set('allcategory', $allcategory);
        }

        return $allcategory;
    }

    public function getFullCategory($fullname = false, $enabled = false)
    {
        global $_W;

        $allcategorynames = m('cache')->getArray('allcategorynames');
        $shopset          = m('common')->getSysset('shop');

        $allcategory = array();

        $sql =
            'SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_category') .
            ' WHERE uniacid=:uniacid ';

        if ($enabled) {
            $sql .= ' AND enabled=1';
        }

        $sql .= ' ORDER BY parentid ASC, displayorder DESC';

        $category = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));
        $category = set_medias($category, array('thumb', 'advimg'));

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

    public function checkClose()
    {
        if (strexists($_SERVER['REQUEST_URI'], '/web/')) {
            return NULL;
        }

        global $_S;

        $close = $_S['close'];

        if (!empty($close['flag'])) {
            if (!empty($close['url'])) {
                header('location: ' . $close['url']);
                exit();
            }
            exit(
                '<!DOCTYPE html>' . "\r\n\t\t\t\t\t" .
                '<html>' . "\r\n\t\t\t\t\t\t" .
                '<head>' . "\r\n\t\t\t\t\t\t\t" .
                '<meta name=\'viewport\' content=\'width=device-width, initial-scale=1, user-scalable=0\'>' . "\r\n\t\t\t\t\t\t\t" .
                '<title>抱歉，商城暂时关闭</title><meta charset=\'utf-8\'><meta name=\'viewport\' content=\'width=device-width, initial-scale=1, user-scalable=0\'><link rel=\'stylesheet\' type=\'text/css\' href=\'https://res.wx.qq.com/connect/zh_CN/htmledition/style/wap_err1a9853.css\'>' . "\r\n\t\t\t\t\t\t" .
                '</head>' . "\r\n\t\t\t\t\t\t" .
                '<body>' . "\r\n\t\t\t\t\t\t" .
                '<style type=\'text/css\'>' . "\r\n\t\t\t\t\t\t" . 'body { background:#fbfbf2; color:#333;}' . "\r\n\t\t\t\t\t\t" . 'img { display:block; width:100%;}' . "\r\n\t\t\t\t\t\t" . '.header {' . "\r\n\t\t\t\t\t\t" . 'width:100%; padding:10px 0;text-align:center;font-weight:bold;}' . "\r\n\t\t\t\t\t\t" .
                '</style>' . "\r\n\t\t\t\t\t\t" .
                '<div class=\'page_msg\'>' . "\r\n\t\t\t\t\t\t\r\n\t\t\t\t\t\t" .
                '<div class=\'inner\'><span class=\'msg_icon_wrp\'><i class=\'icon80_smile\'></i></span>' . $close['detail'] .
                '</div></div>' . "\r\n\t\t\t\t\t\t" .
                '</body>' . "\r\n\t\t\t\t\t" .
                '</html>');
        }
    }

    public function getAllCategory($refresh = false)
    {
        global $_W;

        $allcategory = m('cache')->getArray('allcategoryarr');

        if (empty($allcategory) || $refresh) {

            $allcategory = pdo_fetchall(
                ' SELECT ' .
                '       id,parentid,uniacid,name,thumb ' .
                ' FROM ' . tablename('superdesk_shop_category') .
                ' WHERE ' .
                '       uniacid = \'' . $_W['uniacid'] . '\'',
                array(),
                'id'
            );

            m('cache')->set('allcategoryarr', $allcategory);
        }

        return $allcategory;
    }

    /**
     * @param int $psize
     * @param int $page
     *
     * @return array
     * 楼层显示的必要条件:
     * 1.该分类存在
     * 2.楼层分类有设置
     * 3.该分类状态为显示
     * 4.该楼层分类状态为显示.
     * 假如不满足其中任意一条都会视为该条数据无效
     * 假如楼层分类中所有数据无效,则默认显示分类中状态为显示的前五条
     */
    public function getRefreshFloorCategory($psize = 5, $page = 1)
    {
        global $_W;

        $cache_key = $_W['uniacid'] . '_index_floor_category_goods';
        $offset    = ($page - 1) * $psize;

        //从楼层分类设置中拿
        $parent_category = pdo_fetchall(
            ' SELECT c.id,c.name,c.description,c.advimg ' .
            ' FROM ' . tablename('superdesk_shop_pc_floor_category') . ' as fc ' .
            ' LEFT JOIN ' . tablename('superdesk_shop_category') . ' as c on fc.category_id=c.id ' .
            ' WHERE fc.uniacid =:uniacid ' .
            '       AND fc.enabled=1 ' .
            '       AND c.enabled=1 ' .
            ' ORDER BY fc.displayorder DESC ' .
            ' LIMIT ' . $offset . ',' . $psize,
            array(
                ':uniacid' => $_W['uniacid']
            )
        );

        //假如没有设置楼层分类,那就直接从分类中拿几个出来
        if (empty($parent_category)) {

            $parent_category = pdo_fetchall(
                ' SELECT id,name,description,advimg ' .
                ' FROM ' . tablename('superdesk_shop_category') .
                ' WHERE uniacid =:uniacid ' .
                '       AND enabled=1 ' .
                '       AND parentid=0 ' .
                ' ORDER BY displayorder DESC ' .
                ' LIMIT ' . $offset . ',' . $psize,
                array(
                    ':uniacid' => $_W['uniacid']
                )
            );
        }

        $children_category = pdo_fetchall(
            ' SELECT id,name,parentid ' .
            ' FROM ' . tablename('superdesk_shop_category') .
            ' WHERE uniacid =:uniacid ' .
            '       AND enabled=1     ' .
            '       AND parentid in (' . implode(',', array_column($parent_category, 'id')) . ')' .
            ' ORDER BY parentid ASC, displayorder DESC',
            array(
                ':uniacid' => $_W['uniacid']
            )
        );

        $cache_category = array();

        foreach ($parent_category as $k => &$v) {
            $id = null;
            foreach ($children_category as $ck => $cv) {
                if ($v['id'] == $cv['parentid']) {
                    $v['children'][] = $cv;
                }
            }
            $v['advimg']      = tomedia($v['advimg']);
            $cache_category[] = $v;
        }
        unset($v);

        m('cache')->set($cache_key, $cache_category);

        return $parent_category;
    }
}