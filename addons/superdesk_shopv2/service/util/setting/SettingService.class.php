<?php

/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 1/22/18
 * Time: 7:09 PM
 */
class SettingService
{

    public function getSetting()
    {
        global $_W;

        $sql      =
            ' SELECT `settings` ' .
            ' FROM ' . tablename('uni_account_modules') .
            ' WHERE ' .
            '   `uniacid` = :uniacid ' .
            '   AND `module` = :module';
        $settings = pdo_fetchcolumn(
            $sql,
            array(
                ':uniacid' => $_W['uniacid'],
                ':module'  => SUPERDESK_SHOPV2_MODULE_NAME,
            )
        );
        $settings = iunserializer($settings);

        return $settings;

    }

    public function getSettingColumn($column = 'jd_vop_order_submit_bNeedGift')
    {
        global $_W;

        $settings = $this->getSetting();

        if ($column == 'jd_vop_order_submit_bNeedGift') { // 0 否 1 是 | integer

            $column_value = isset($settings[$column]) ? $settings[$column] : 1;
            $column_value = (bool)$column_value;

            // 文档是这样写的 表示是否需要增品，默认不给增品，默认值为：false，如果需要增品bNeedGift请给true,建议该参数都给true,但如果实在不需要增品可以给false;
            // 业务上 我们 默认 是需要增品

        } else {

            $column_value = true;

        }

        return $column_value;

    }
}