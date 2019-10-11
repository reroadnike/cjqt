<?php
/**
 * 微小区模块
 *
 * [本创] Copyright (c) 2016 grandway020.com
 */
/**
 * 微信端公告页面
 */
global $_GPC, $_W;
$op             = !empty($_GPC['op']) ? $_GPC['op'] : 'list';
$id             = intval($_GPC['id']);
$mode           = $_GPC['mode'];

if ($op == 'list') {

    if ($_W['isajax']) {
        $pindex = max(1, intval($_GPC['page']));
        $psize = 6;
        $sql =
            " select * ".
            " from " . tablename("superdesk_boardroom_4school_announcement") .
            " where uniacid='{$_W['uniacid']}' order by id desc LIMIT " . ($pindex - 1) * $psize . ',' . $psize;

        $row = pdo_fetchall($sql);

        $list = array();
        foreach ($row as $key => $value) {
            $r = pdo_fetch("SELECT * FROM" . tablename('superdesk_boardroom_4school_announcement_reading_member') . "WHERE aid=:aid AND openid=:openid", array(':aid' => $value['id'], ':openid' => $_W['fans']['from_user']));

            if ($r) {
                $list[$key]['rstatus'] = 1;
            }
            $list[$key]['id']           = $value['id'];
            $list[$key]['createtime']   = $value['createtime'];
            $list[$key]['title']        = $value['title'];
            $list[$key]['datetime']     = $value['datetime'];
            $list[$key]['location']     = $value['location'];
            $list[$key]['reason']       = $value['reason'];
            $list[$key]['remark']       = $value['remark'];

        }
        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM" . tablename('superdesk_boardroom_4school_announcement') . "WHERE uniacid='{$_W['uniacid']}'");

        $content = array();

        foreach ($list as $key => $value) {
            $r  = pdo_fetch("SELECT * FROM".tablename('superdesk_boardroom_4school_announcement_reading_member')."WHERE aid=:aid AND openid=:openid",array(':aid' => $value['id'],':openid' => $_W['fans']['from_user']));
            if ($r) {
                $rstatus = 1;
            }else{
                $rstatus = 0;
            }

            $url = $this->createMobileUrl('announcement',array('op' => 'detail','id' => $value['id']));

            ob_start();
            include $this->template('announcement/list_ajax');
            $content[]['html'] = ob_get_contents();
            ob_end_clean();
        }
        $r = array(
            'allhtml' => $content,
            'page_count' => $total,
        );
        print_r(json_encode($r));exit();
    }
    
    include $this->template('/announcement/list');
    
} elseif ($op == 'detail') {

    $item = pdo_fetch(
        " select * ".
        " from " . tablename("superdesk_boardroom_4school_announcement") .
        " where uniacid='{$_W['uniacid']}' and id =:id", array(':id' => $id));

    if($mode == 'full_screen'){
        include $this->template('announcement/detail_full_screen');
    } else{
        include $this->template('announcement/detail');
    }



} elseif ($op == 'ajax') {
    // 原版
    $r = pdo_fetch(
        " SELECT * ".
        " FROM" . tablename('superdesk_boardroom_4school_announcement_reading_member') .
        " WHERE aid=:aid AND openid=:openid", array(':aid' => $id, ':openid' => $_W['openid']));

    if (empty($r)) {
        $data = array(
            'uniacid' => $_W['uniacid'],
            'aid' => $id,
            'openid' => $_W['openid'],
//            'unionid' => $this->getUnionid(),
            'status' => 1,
        );
        $result = pdo_insert('superdesk_boardroom_4school_announcement_reading_member', $data);
        if ($result) {
            echo json_encode(array('s' => 1));
            exit();
        }
    }


}
	