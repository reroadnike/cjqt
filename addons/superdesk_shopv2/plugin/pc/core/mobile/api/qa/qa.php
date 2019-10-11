<?php

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/accessControl.php";

if (!(defined("IN_IA"))) {
    exit("Access Denied");
}

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/page_mobile.php";

class Qa_SuperdeskShopV2Page extends PcMobilePage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        $condition =
            ' and uniacid=:uniacid ' .
            ' and status=1 ';

        $params = array(
            ':uniacid' => $_W['uniacid']
        );

        $category = pdo_fetchall(
            'SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_qa_category') .
            ' where ' .
            '       enabled=1 ' .
            '       and uniacid=:uniacid ' .
            ' order by displayorder desc ',
            $params
        );

        $sql =
            'SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_qa_question') .
            ' where  1 ' .
            $condition .
            ' ORDER BY displayorder DESC,id DESC ';

        $list = pdo_fetchall($sql, $params);

        show_json(1, compact('category', 'list'));
    }

    public function detail()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        if (!(empty($id))) {

            $item = pdo_fetch(
                'select * ' .
                ' from ' . tablename('superdesk_shop_qa_question') .
                ' where ' .
                '       id=:id ' .
                '       and status=1 ' .
                '       and uniacid=:uniacid ' .
                ' limit 1',
                array(
                    ':id'      => $id,
                    ':uniacid' => $_W['uniacid']
                )
            );

            if (empty($item)) {

                show_json(0);
                //$this->message('问题不存在!');
            }

            //$item['content'] = iunserializer($item['content']);
            //$item['content'] = htmlspecialchars_decode($item['content']);
        }

        show_json(1, $item);
    }
}