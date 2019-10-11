<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 7/29/17
 * Time: 4:59 AM
 */

global $_W, $_GPC;
$this->checkAuth();
$operation = $_GPC['op'];

if ($operation == 'post') {
    $id = intval($_GPC['id']);
    $data = array(
        'uniacid' => $_W['uniacid'],
        'uid' => $_W['fans']['uid'],
        'username' => $_GPC['realname'],
        'mobile' => $_GPC['mobile'],
        'province' => $_GPC['province'],
        'city' => $_GPC['city'],
        'district' => $_GPC['area'],
        'address' => $_GPC['address'],
    );
    if (empty($data['username']) || empty($data['mobile']) || empty($data['address'])) {
        message('请输完善您的资料！');
    }
    if (!empty($id)) {
        unset($data['uniacid']);
        unset($data['uid']);
        pdo_update('mc_member_address', $data, array('id' => $id));
        message($id, '', 'ajax');
    } else {
        pdo_update('mc_member_address', array('isdefault' => 0), array('uniacid' => $_W['uniacid'], 'uid' => $_W['fans']['uid']));
        $data['isdefault'] = 1;
        pdo_insert('mc_member_address', $data);
        pdo_update('mc_members', array('address' => $data['province'] . $data['city'] . $data['district'] . $data['address']), array('uniacid' => $_W['uniacid'], 'uid' => $_W['fans']['uid']));
        $id = pdo_insertid();
        if (!empty($id)) {
            message($id, '', 'ajax');
        } else {
            message(0, '', 'ajax');
        }
    }
} elseif ($operation == 'default') {
    $id = intval($_GPC['id']);
    $sql = 'SELECT * FROM ' . tablename('mc_member_address') . ' WHERE `id` = :id AND `uniacid` = :uniacid
					 AND `uid` = :uid';
    $params = array(':id' => $id, ':uniacid' => $_W['uniacid'], ':uid' => $_W['fans']['uid']);
    $address = pdo_fetch($sql, $params);

    if (!empty($address) && empty($address['isdefault'])) {
        pdo_update('mc_member_address', array('isdefault' => 0), array('uniacid' => $_W['uniacid'], 'uid' => $_W['fans']['uid']));
        pdo_update('mc_member_address', array('isdefault' => 1), array('uniacid' => $_W['uniacid'], 'uid' => $_W['fans']['uid'], 'id' => $id));
        pdo_update('mc_members', array('address' => $address['province'] . $address['city'] . $address['district'] . $address['address']), array('uniacid' => $_W['uniacid'], 'uid' => $_W['fans']['uid']));
    }
    message(1, '', 'ajax');
} elseif ($operation == 'detail') {
    $id = intval($_GPC['id']);
    $sql = 'SELECT * FROM ' . tablename('mc_member_address') . ' WHERE `id` = :id';
    $row = pdo_fetch($sql, array(':id' => $id));
    message($row, '', 'ajax');
} elseif ($operation == 'remove') {
    $id = intval($_GPC['id']);
    if (!empty($id)) {
        $where = ' AND `uniacid` = :uniacid AND `uid` = :uid';
        $sql = 'SELECT `isdefault` FROM ' . tablename('mc_member_address') . ' WHERE `id` = :id' . $where;
        $params = array(':id' => $id, ':uniacid' => $_W['uniacid'], ':uid' => $_W['fans']['uid']);
        $address = pdo_fetch($sql, $params);

        if (!empty($address)) {
            pdo_delete('mc_member_address', array('id' => $id));
            // 如果删除的是默认地址，则设置是新的为默认地址
            if ($address['isdefault'] > 0) {
                $sql = 'SELECT MAX(id) FROM ' . tablename('mc_member_address') . ' WHERE 1 ' . $where;
                unset($params[':id']);
                $maxId = pdo_fetchcolumn($sql, $params);
                if (!empty($maxId)) {
                    pdo_update('mc_member_address', array('isdefault' => 1), array('id' => $maxId));
                    die(json_encode(array("result" => 1, "maxid" => $maxId)));
                }
            }
        }
    }
    die(json_encode(array("result" => 1, "maxid" => 0)));
} else {
    $sql = 'SELECT * FROM ' . tablename('mc_member_address') . ' WHERE `uniacid` = :uniacid AND `uid` = :uid';
    $params = array(':uniacid' => $_W['uniacid']);
    if (empty($_W['member']['uid'])) {
        $params[':uid'] = $_W['fans']['openid'];
    } else {
        $params[':uid'] = $_W['member']['uid'];
    }
    $addresses = pdo_fetchall($sql, $params);
    $carttotal = $this->getCartTotal();
    include $this->template('address');
}