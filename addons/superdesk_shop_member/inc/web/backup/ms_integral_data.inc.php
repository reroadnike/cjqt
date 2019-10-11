<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=ms_integral_data */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/ms_integral_data.class.php');
$ms_integral_data = new ms_integral_dataModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $ms_integral_data->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'integral_data_id' => $_GPC['integral_data_id'],// 
    'integral_standard_pkcode' => $_GPC['integral_standard_pkcode'],// 对应积分规则PKCODE
    'integral_data_name' => $_GPC['integral_data_name'],// 积分名称
    'integral_data_pkcode' => $_GPC['integral_data_pkcode'],// 积分对外的唯一id
    'integral_data_number' => $_GPC['integral_data_number'],// 
    'integral_data_expiretype' => $_GPC['integral_data_expiretype'],// 到期类型：0:表示每天到期，1：每月到期，2：每年到期，3：自定到期时间
    'integral_data_begintime' => $_GPC['integral_data_begintime'],// 若到期类型为3时，这个为券有效时间的开始时间
    'integral_data_endtime' => $_GPC['integral_data_endtime'],// 若到期类型为2或者3时，这个为券有效时间的结束时间
    'integral_data_ctime' => $_GPC['integral_data_ctime'],// 
    'integral_data_useproducttype' => $_GPC['integral_data_useproducttype'],// 适用商品规则，0：全场通用，1：指定供应商
    'integral_data_state' => $_GPC['integral_data_state'],// 0：表示未启用，1：表示可使用，2：已使用，3：已过期，4：已失效
    'integral_m_id' => $_GPC['integral_m_id'],// 会员ID
    'integral_use_time' => $_GPC['integral_use_time'],// 积分使用时间
    'integral_data_isReturn' => $_GPC['integral_data_isReturn'],// 是否是退还积分，1：是

        );
        $ms_integral_data->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('ms_integral_data', array('op' => 'list')), 'success');


    }
    include $this->template('ms_integral_data_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $ms_integral_data->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('ms_integral_data', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $ms_integral_data->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('ms_integral_data_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $ms_integral_data->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $ms_integral_data->delete($id);

    message('删除成功！', referer(), 'success');
}

