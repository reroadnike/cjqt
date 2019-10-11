<?php
/**
 * 微点餐
 *
 * 作者:微赞科技
 *
 */
defined('IN_IA') or exit('Access Denied');

class superdesk_dishModule extends WeModule
{
    public $name = 'superdesk_dishModule';

    public function fieldsFormDisplay($rid = 0)
    {
        global $_W;
    }

    public function fieldsFormSubmit($rid = 0)
    {
        global $_GPC, $_W;
    }
}