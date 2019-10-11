<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 3/6/18
 * Time: 2:37 PM
 */

global $_W, $_GPC;


$uniacid = $this->_uniacid;


$from_user = trim($_GPC['from_user']);

$parentid = intval($_GPC['parentid']);

$type       = trim($_GPC['type']);
$username   = trim($_GPC['username']);
$content    = trim($_GPC['content']);
$telphone   = trim($_GPC['telphone']);
$issue_type = trim($_GPC['issue_type']);

$this->_fromuser = $from_user;

$nickname   = trim($_GPC['nickname']);
$headimgurl = trim($_GPC['headimgurl']);


if (empty($from_user)) {
    $this->showMessage('会话已过期，请重新发送关键字!');
}

if (empty($content)) {
    $this->showMessage('请输入回复内容!');
}

if ($type == 'feedback') { //反馈
    $parentid = 0;

    if (empty($username)) {
        $this->showMessage('请输入名称!');
    }
} else { //回复

    $item = pdo_fetch(
        " SELECT * " .
        " FROM " . tablename($this->modulename . '_feedback') .
        " WHERE id=:id AND uniacid=:uniacid AND status=1 LIMIT 1",
        array(
            ':id'      => $parentid,
            ':uniacid' => $uniacid
        )
    );

    if (empty($item)) {
        $this->showMessage('要回复的反馈可能已经被删除了!' . $parentid);
    }

    $username = $nickname;
}

$setting = pdo_fetch("select * from " . tablename($this->modulename . '_setting') . " where uniacid =:uniacid LIMIT 1", array(':uniacid' => $uniacid));

if (empty($setting)) {
    $status = 0;
} else {
    $status = intval($setting['ischeck']) == 1 ? 0 : 1;
}

$data = array(
    'uniacid'    => $uniacid,
    'from_user'  => $from_user,
    'parentid'   => $parentid,
    'username'   => $username,
    'nickname'   => $nickname,
    'headimgurl' => $headimgurl,
    'status'     => $status,
    'telphone'   => $telphone,
    'issue_type' => $issue_type,
    'content'    => $content,
    'dateline'   => TIMESTAMP
);

pdo_insert('superdesk_feedback_feedback', $data);

if ($status == 0) {
    $this->showMessage('您的反馈,我们已经收到!', 1);//　要审核的msg
} else {
    $this->showMessage('您的反馈,我们已经收到!', 1);
}