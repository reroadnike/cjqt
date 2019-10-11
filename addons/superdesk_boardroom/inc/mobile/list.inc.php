<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 7/29/17
 * Time: 5:40 AM
 */

global $_GPC, $_W;
$pindex = max(1, intval($_GPC['page']));
$psize = 4;
$condition = '';
if (!empty($_GPC['ccate'])) {
    $cid = intval($_GPC['ccate']);
    $condition .= " AND ccate = '{$cid}'";
    $_GPC['pcate'] = pdo_fetchcolumn("SELECT parentid FROM " . tablename('superdesk_boardroom_s_category') . " WHERE id = :id", array(':id' => intval($_GPC['ccate'])));
} elseif (!empty($_GPC['pcate'])) {
    $cid = intval($_GPC['pcate']);
    $condition .= " AND pcate = '{$cid}'";
}
if (!empty($_GPC['keyword'])) {
    $condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
}
$children = array();
$category = pdo_fetchall("SELECT * FROM " . tablename('superdesk_boardroom_s_category') . " WHERE uniacid = '{$_W['uniacid']}' and enabled=1 ORDER BY parentid ASC, displayorder DESC", array(), 'id');
foreach ($category as $index => $row) {
    if (!empty($row['parentid'])) {
        $children[$row['parentid']][$row['id']] = $row;
        unset($category[$index]);
    }
}
$recommandcategory = array();
foreach ($category as &$c) {
    if ($c['isrecommand'] == 1) {
        $c['list'] = pdo_fetchall("SELECT * FROM " . tablename('superdesk_boardroom_s_goods') . " WHERE uniacid = '{$_W['uniacid']}' and deleted=0 AND status = '1'  and pcate='{$c['id']}'  ORDER BY displayorder DESC, sales DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
        $c['total'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('superdesk_boardroom_s_goods') . " WHERE uniacid = '{$_W['uniacid']}'  and deleted=0  AND status = '1' and pcate='{$c['id']}'");
        $c['pager'] = pagination($c['total'], $pindex, $psize, $url = '', $context = array('before' => 0, 'after' => 0, 'ajaxcallback' => ''));
        $recommandcategory[] = $c;
    }
    if (!empty($children[$c['id']])) {
        foreach ($children[$c['id']] as &$child) {
            if ($child['isrecommand'] == 1) {
                $child['list'] = pdo_fetchall("SELECT * FROM " . tablename('superdesk_boardroom_s_goods') . " WHERE uniacid = '{$_W['uniacid']}'  and deleted=0 AND status = '1'  and pcate='{$c['id']}' and ccate='{$child['id']}'  ORDER BY displayorder DESC, sales DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
                $child['total'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('superdesk_boardroom_s_goods') . " WHERE uniacid = '{$_W['uniacid']}'  and deleted=0  AND status = '1' and pcate='{$c['id']}' and ccate='{$child['id']}' ");
                $child['pager'] = pagination($child['total'], $pindex, $psize, $url = '', $context = array('before' => 0, 'after' => 0, 'ajaxcallback' => ''));
                $recommandcategory[] = $child;
            }
        }
        unset($child);
    }
}
unset($c);
$carttotal = $this->getCartTotal();
//幻灯片
$advs = pdo_fetchall("select * from " . tablename('superdesk_boardroom_s_adv') . " where enabled=1 and uniacid= '{$_W['uniacid']}' order by displayorder asc");
foreach ($advs as &$adv) {
    if (substr($adv['link'], 0, 5) != 'http:') {
        $adv['link'] = "http://" . $adv['link'];
    }
}
unset($adv);
//首页推荐
$rpindex = max(1, intval($_GPC['rpage']));
$rpsize = 4;
$condition = ' and isrecommand=1';
$rlist = pdo_fetchall("SELECT * FROM " . tablename('superdesk_boardroom_s_goods') . " WHERE uniacid = '{$_W['uniacid']}'  and deleted=0 AND status = '1' $condition ORDER BY displayorder DESC, sales DESC LIMIT " . ($rpindex - 1) * $rpsize . ',' . $rpsize);

include $this->template('list');