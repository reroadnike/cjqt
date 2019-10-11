<?php
/**
 * 公告
 */
global $_GPC, $_W;
$op = $_GPC['op'];
$json = json_decode(trim(file_get_contents('php://input')), true);
$unionid = $json['uid'];
$id = intval($json['id']);        //通知id
//判断是否已经注册成为房主且已经被管理员通过
$member = $this->app_changemember($unionid);

$region = pdo_fetch("SELECT title FROM" . tablename('superdesk_boardroom_4school_region') . "WHERE id='{$member['regionid']}'");

//通知列表，带分页模式
if ($op == 'list') {
    $pindex = max(1, intval($json['page']));
    $psize = 6;
    $sql = "select * from " . tablename("superdesk_boardroom_4school_announcement") . " where uniacid='{$_W['uniacid']}' order by id desc LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
    $row = pdo_fetchall($sql);

    $list = array();
    foreach ($row as $key => $value) {
        if ($value['regionid'] != 'N;') {
            $regions = unserialize($value['regionid']);
            if (@in_array($member['regionid'], $regions)) {
                $r = pdo_fetch("SELECT * FROM" . tablename('superdesk_boardroom_4school_announcement_reading_member') . "WHERE aid=:aid AND unionid=:unionid", array(':aid' => $value['id'], ':unionid' => $unionid));
                if ($r) {
                    $list[$key]['rstatus'] = 1;                            //1启用，2禁用
                }
                $list[$key]['id'] = $value['id'];                //报事id
                $list[$key]['createtime'] = $value['createtime'];        //创建时间
                $list[$key]['title'] = $value['title'];            //主题
                $list[$key]['datetime'] = $value['datetime'];        //通知时间
                $list[$key]['location'] = $value['location'];        //地址
                $list[$key]['reason'] = $value['reason'];            //原因
                $list[$key]['remark'] = $value['remark'];            //备注
            }
        }
    }

    foreach ($list as $key => $value) {
        $r = pdo_fetch("SELECT * FROM" . tablename('superdesk_boardroom_4school_announcement_reading_member') . "WHERE aid=:aid AND unionid=:unionid", array(':aid' => $value['id'], ':unionid' => $unionid));
        if ($r) {
            $list[$key]['rstatus'] = 1;
        } else {
            $list[$key]['rstatus'] = 0;
        }
        $list[$key]['createtime'] = date('Y/m/d', $value['createtime']);
    }

    $ret['code'] = '200';
    $ret['msg'] = '请求成功';
    $ret['data'] = $list;
    echo json_encode($ret, JSON_UNESCAPED_UNICODE);
    exit();
} elseif ($op == 'detail') { //通知详细
    $item = pdo_fetch("select id,title,createtime,datetime,location,reason,remark from " . tablename("superdesk_boardroom_4school_announcement") . " where uniacid='{$_W['uniacid']}' and id =:id", array(':id' => $id));

    if (!empty($item)) {
        if (!$item['uid']) {
            $item['uid'] = '';
        }
        $item['createtime'] = date('Y/m/d', $item['createtime']);
    }


    $r = pdo_fetch("SELECT * FROM" . tablename('superdesk_boardroom_4school_announcement_reading_member') . "WHERE aid=:aid AND unionid=:unionid", array(':aid' => $id, ':unionid' => $unionid));
    if (empty($r)) {
        $data = array(
            'uniacid' => $_W['uniacid'],
            'aid' => $id,
            'unionid' => $unionid,
            'status' => 1,
        );
        $result = pdo_insert('superdesk_boardroom_4school_announcement_reading_member', $data);
    }

    $ret['code'] = '200';
    $ret['msg'] = '请求成功';
    $ret['data'] = $item;                // 1
    echo json_encode($ret, JSON_UNESCAPED_UNICODE);
    exit();
}


//点击后已读
//if( $op=='readed' ){
//
//
//}