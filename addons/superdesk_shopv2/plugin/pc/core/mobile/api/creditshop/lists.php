<?php

if (!(defined("IN_IA"))) {
    exit("Access Denied");
}
require SUPERDESK_SHOPV2_PLUGIN . "creditshop/core/page_mobile.php";

class Lists_SuperdeskShopV2Page extends CreditshopMobilePage
{
    public function main()
    {
        global $_W;
        global $_GPC;
        $member = m('member')->getMember($_W['openid'], $_W['core_user']);
        $shop   = m('common')->getSysset('shop');

        $uniacid = $_W['uniacid'];

        $cateid = intval($_GPC['cate']);

        if (!(empty($cateid))) {
            $cate = pdo_fetch(
                'select id,name ' .
                ' from ' . tablename('superdesk_shop_creditshop_category') .
                ' where 1 ' .
                '       and id=:id ' .
                '       and uniacid=:uniacid ' .
                ' limit 1',
                array(
                    ':id'      => $cateid,
                    ':uniacid' => $uniacid
                )
            );
        }

        $category = pdo_fetchall(
            'select id,name,thumb,isrecommand ' .
            ' from ' . tablename('superdesk_shop_creditshop_category') . "\n\t\t\t\t\t\t" .
            ' where 1 ' .
            '       and uniacid=:uniacid ' .
            '       and enabled=1 ' .
            ' order by displayorder desc',
            array(
                ':uniacid' => $uniacid
            )
        );
        $category = set_medias($category, 'thumb');

        $_W['shopshare'] = array(
            'title'  => $this->set['share_title'],
            'imgUrl' => tomedia($this->set['share_icon']),
            'link'   => mobileUrl('creditshop', array(), true),
            'desc'   => $this->set['share_desc']
        );

        $com = p('commission');
        if ($com) {

            $cset = $com->getSet();

            if (!(empty($cset))) {

                if (($member['isagent'] == 1) && ($member['status'] == 1)) {

                    $_W['shopshare']['link'] = mobileUrl('creditshop', array('mid' => $member['id']), true);

                    if (empty($cset['become_reg']) && (empty($member['realname']) || empty($member['mobile']))) {
                        $trigger = true;
                    }

                } else if (!(empty($_GPC['mid']))) {

                    $_W['shopshare']['link'] = mobileUrl('creditshop/detail', array('mid' => $_GPC['mid']), true);
                }
            }
        }

        include $this->template();
    }

    public function getlist()
    {
        global $_W;
        global $_GPC;

        $member = m('member')->getMember($_W['openid'], $_W['core_user']);
        $shop   = m('common')->getSysset('shop');

        $uniacid = $_W['uniacid'];

        $cateid = intval($_GPC['cate']);

        $cate = pdo_fetch(
            'select id,name ' .
            ' from ' . tablename('superdesk_shop_creditshop_category') .
            ' where 1 ' .
            '       and id=:id ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':id'      => $cateid,
                ':uniacid' => $uniacid
            )
        );

        $pindex = max(1, intval($_GPC['page']));
        $psize  = 10;

        $condition =
            ' and uniacid = :uniacid ' .
            ' and status=1 ' .
            ' and deleted=0 ';

        $params = array(
            ':uniacid' => $_W['uniacid']
        );

        if (!(empty($cate))) {
            $condition .= ' and cate=' . $cateid;
        }

        $keywords = trim($_GPC['keywords']);
        if (!(empty($keywords))) {
            $condition .= ' AND title like \'%' . $keywords . '%\' ';
        }

        $sql =
            'SELECT COUNT(*) ' .
            ' FROM ' . tablename('superdesk_shop_creditshop_goods') .
            ' where 1 ' .
            $condition;

        $total = pdo_fetchcolumn($sql, $params);

        $list = array();

        if (!(empty($total))) {

            $sql =
                'SELECT id,title,thumb,subtitle,`type`,price,credit,money,goodstype ' .
                ' FROM ' . tablename('superdesk_shop_creditshop_goods') .
                ' where 1 ' .
                $condition .
                ' ORDER BY displayorder desc,id DESC ' .
                ' LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize;

            $list = pdo_fetchall($sql, $params);
            $list = set_medias($list, 'thumb');

            foreach ($list as &$row) {

                if ((0 < $row['credit']) & (0 < $row['money'])) {
                    $row['acttype'] = 0;
                } else if (0 < $row['credit']) {
                    $row['acttype'] = 1;
                } else if (0 < $row['money']) {
                    $row['acttype'] = 2;
                }

                if ((intval($row['money']) - $row['money']) == 0) {
                    $row['money'] = intval($row['money']);
                }
            }

            unset($row);
        }

        show_json(1, array(
                "list"     => $list,
                'pagesize' => $psize,
                'total'    => $total
            )
        );
    }
}