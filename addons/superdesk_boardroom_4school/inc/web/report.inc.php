<?php
/**
 * 投诉
 */

global $_W, $_GPC;

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';
$id = $_GPC['id'];

//查投诉子类 投诉主类ID=3
$categories = pdo_fetchall("SELECT * FROM" . tablename('superdesk_boardroom_4school_category') . "WHERE uniacid='{$_W['uniacid']}' AND type=3");

if ($op == 'list') {

    //搜索 type 1为报修，2为投诉
    $condtion = ' m.uniacid =:uniacid';
    $params[':uniacid'] = $_W['uniacid'];

    if (!empty($_GPC['category'])) {
        $condtion .= " AND m.category = :category";
        $params[':category'] = $_GPC['category'];
    }

    $status = intval($_GPC['status']);
    if (!empty($status)) {
        $condtion .= " AND m.status = :status";
        $params[':status'] = $status;
    }

    $starttime = strtotime($_GPC['birth']['start']);
    $endtime = strtotime($_GPC['birth']['end']);

    if (!empty($starttime) && $starttime == $endtime) {
        $endtime = $endtime + 86400 - 1;
    }

    if ($starttime && $endtime) {
        $condtion .= " AND m.createtime between '{$starttime}' and '{$endtime}'";
    }


    //显示投诉记录
    $pindex = max(1, intval($_GPC['page']));
    $psize = 10;

    $list = pdo_fetchall(
         " SELECT ".
         "      
                m.address,
                m.content,
                m.comment,
                m.category,
                m.createtime,
                m.status,
                m.id,
                m.resolve,
                m.resolver,
                m.resolvetime "
        ." FROM " . tablename('superdesk_boardroom_4school_report') . " as m "
        ." WHERE $condtion "
        ." AND m.type = 2 order by createtime desc LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);

    foreach ($list as $key => $value) {
        $list[$key]['cctime'] = date('Y-m-d H:i', $value['createtime']);
    }

    $total = pdo_fetchcolumn(
         " SELECT COUNT(*) "
        ." FROM" . tablename('superdesk_boardroom_4school_report') . "as m "
        ." WHERE $condtion AND m.type = 2", $params);

    if ($_GPC['export'] == 1) {
        $this->export($list, array(
            "title" => "意见建议数据-" . date('Y-m-d-H-i', time()),
            "columns" => array(
                array(
                    'title' => '姓名',
                    'field' => 'realname',
                    'width' => 12
                ),
                array(
                    'title' => '手机号',
                    'field' => 'mobile',
                    'width' => 12
                ),
                array(
                    'title' => '地址',
                    'field' => 'address',
                    'width' => 18
                ),
                array(
                    'title' => '投诉内容',
                    'field' => 'content',
                    'width' => 20
                ),
                array(
                    'title' => '评价',
                    'field' => 'comment',
                    'width' => 20
                ),
                array(
                    'title' => '时间',
                    'field' => 'cctime',
                    'width' => 12
                ),
            )
        ));
    }
    $pager = pagination($total, $pindex, $psize);
    load()->func('tpl');
    include $this->template('web/report/list');

} elseif ($op == 'add') {

    //对应ID的投诉记录查看
    $sql =
        " select 
                a.address,
                a.images,
                a.id,
                a.category,
                a.content,
                a.createtime,
                a.status,
                a.resolver,
                a.resolve,
                a.resolvetime,
                a.openid ".
        " from" . tablename("superdesk_boardroom_4school_report") . "as a ".
        " where a.uniacid='{$_W['uniacid']}' and a.id='{$id}' $condition ";

    $value = pdo_fetch($sql);
    $images = unserialize($value['images']);

    if ($images) {
        $picid = implode(',', $images);
        $imgs = pdo_fetchall("SELECT * FROM" . tablename('superdesk_boardroom_4school_images') . "WHERE id in({$picid})");
    }

    //组成一个新的数组
    $item = array();
    $item = array(
        'id' => $value['id'],
        'requirement' => $value['requirement'],
        'category' => $value['category'],
        'realname' => $value['realname'],
        'address' => $value['address'],
        'content' => $value['content'],
        'createtime' => $value['createtime'],
        'status' => $value['status'],
        'reply' => $reply,
        'img' => $imgs,
        'resolve' => $value['resolve'],
        'resolver' => $value['resolver'],
    );
    if ($_W['ispost']) {

        $resolver = empty($_GPC['resolver']) ? $_W['username'] : $_GPC['resolver'];
        $data = array(
            'status' => 1,
            'resolve' => $_GPC['resolve'],
            'resolver' => $resolver,
            'resolvetime' => $_W['timestamp'],
        );
        pdo_update("superdesk_boardroom_4school_report", $data, array('id' => $id));

        /* 模板消息通知
        if ($data['status'] == 1) {
            //模板消息通知
            $tpl = pdo_fetch("SELECT * FROM" . tablename('superdesk_boardroom_4school_wechat_tplid') . "WHERE uniacid=:uniacid", array(':uniacid' => $_W['uniacid']));
            $template_id = $tpl['grab_wc_tplid'];
            $content = array(
                'first' => array(
                    'value' => '您的意见建议已处理',
                ),
                'keyword1' => array(
                    'value' => $item['content'],
                ),
                'keyword2' => array(
                    'value' => $item['realname'],
                ),
                'keyword3' => array(
                    'value' => date('Y-m-d H:i', TIMESTAMP),
                ),
                'remark' => array(
                    'value' => '请到微信我的意见建议给我们评价，谢谢使用！',
                ),
            );
            $result = $this->sendtpl($value['openid'], $url, $template_id, $content);
        }
        */

        message('处理成功！', referer(), 'success');

    }
    include $this->template('web/report/add');

} elseif ($op == 'delete') {

    pdo_delete("superdesk_boardroom_4school_report", array('uniacid' => $_W['uniacid'], 'id' => $id));
    message('删除成功！', referer(), 'success');

} elseif ($op == 'category') {

    $list = pdo_fetchall(
         " SELECT * " .
         " FROM" . tablename('superdesk_boardroom_4school_category') .
         " WHERE uniacid=:uniacid AND type =3",
        array(':uniacid' => $_W['uniacid']));

    if (checksubmit('submit')) {
        $count = count($_GPC['names']);

        for ($i = 0; $i < $count; $i++) {
            $ids = $_GPC['ids'];
            $id = trim(implode(',', $ids), ',');
            $data = array(
                'name' => $_GPC['names'][$i],
                'uniacid' => $_W['uniacid'],
                'type' => 3,
            );
            if ($ids[$i]) {
                $r = pdo_update("superdesk_boardroom_4school_category", $data, array('id' => $ids[$i]));
            } else {
                $r = pdo_insert("superdesk_boardroom_4school_category", $data);
            }
        }
        message('提交成功', $this->createWebUrl('report', array('op' => 'list')));

    }

    include $this->template('web/report/category');

} elseif ($op == 'del') {

    if ($id) {
        pdo_delete("superdesk_boardroom_4school_category", array('id' => $id));
        message('删除成功。', referer(), 'success');
    }

}
