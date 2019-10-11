<?php
/**
<form action="{php echo murl('entry' , array('m'=>'superdesk_boardroom_4school' , 'do'=>'boardroom_select_time'))}" method="post" id="form_submit">
<input type="hidden" name="id" value="{$id}"/>
<input type="hidden" name="select_time_bar" value="{$select_time_bar}"/>
<input type="hidden" name="order_goodsid" value="{$order_goodsid}"/>

<input type="text" id="date_start" name="date_start" value="{$date_start}"/>
<input type="text" id="date_end" name="date_end" value="{$date_end}"/>
<input type="time" id="time_start" name="time_start" value="{$time_start}"/>
<input type="time" id="time_end" name="time_end" value="{$time_end}"/>
</form>
 */

/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 8/10/17
 * Time: 3:19 PM
 */

global $_GPC, $_W;

$id = $_GPC['id'];
$select_time_bar = $_GPC['select_time_bar'];

$order_goodsid = $_GPC['order_goodsid'];

$date_start = $_GPC['date_start'];
$date_end = $_GPC['date_end'];

$time_start = $_GPC['time_start'];
$time_end = $_GPC['time_end'];

echo $select_time_bar;

//include $this->template('test_form');