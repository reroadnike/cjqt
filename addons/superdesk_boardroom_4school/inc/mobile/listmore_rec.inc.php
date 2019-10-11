<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 7/29/17
 * Time: 5:42 AM
 */

global $_GPC, $_W;
$pindex = max(1, intval($_GPC['page']));
$psize = 4;
$condition = ' and isrecommand=1 ';
$list = pdo_fetchall("SELECT * FROM " . tablename('superdesk_boardroom_4school_s_goods') . " WHERE uniacid = '{$_W['uniacid']}'  and deleted=0 AND status = '1' $condition ORDER BY displayorder DESC, sales DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
include $this->template('list_more');