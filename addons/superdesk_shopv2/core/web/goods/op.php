<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Op_SuperdeskShopV2Page extends WebPage
{

    public function checked()
    {
        global $_W;
        global $_GPC;

        $opdata = $this->opData();
        extract($opdata);

        if ($_W['ispost']) {
            $checked  = intval($_GPC['checked']);

            $checkedValue = ($checked == 0 ? '审核通过' : ($checked == 1 ? '待审核' : '审核驳回'));

            pdo_update('superdesk_shop_goods', array('checked' => intval($_GPC['checked']),'updatetime' => time()), array('id' => $item['id']));
            plog('goods.edit', '修改商品审核状态<br/>ID: ' . $item['id'] . '<br/>商品名称: ' . $item['title'] . '<br/>状态: ' . $checkedValue);

            show_json(1);
        }

        include $this->template();
    }

    protected function opData()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        $item = pdo_fetch(
            ' SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_goods') .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' WHERE ' .
            '       id = :id ' .
            '       and uniacid=:uniacid',
            array(
                ':id'      => $id,
                ':uniacid' => $_W['uniacid']
            )
        );

        if (empty($item)) {
            if ($_W['isajax']) {
                show_json(0, '未找到商品!');
            }

            $this->message('未找到商品!', '', 'error');
        }

        return array(
            'id'   => $id,
            'item' => $item
        );
    }
}