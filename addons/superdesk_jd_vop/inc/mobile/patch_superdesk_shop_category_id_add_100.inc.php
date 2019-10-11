<?php


/**
 * patch_ims_superdesk_shop_category_id_add_100.inc
 */
global $_GPC, $_W;


$tablename = "superdesk_shop_category_update_to_server";
//$tablename = "superdesk_shop_category";

$page      = 1;
$page_size = 1000;

$where_sql = "";
$where_sql .= " WHERE `uniacid` = :uniacid";
$params = array(
    ':uniacid' => $_W['uniacid'],
);

$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($tablename) . $where_sql, $params);
$list = pdo_fetchall(
    "SELECT * FROM " . tablename($tablename) .
    $where_sql .
    " ORDER BY id DESC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);


//DESC | ASC

foreach ($list as $index => $item){

    echo json_encode($item);

    echo "<br>";
//    $params = array();
//    $params['id'] = $item['id'] + 100;
//    $params['parentid'] = $item['parentid'] == 0 ? 0 : $item['parentid'] + 100;
//    $ret = pdo_update($tablename, $params, array('id' => $item['id']));

//    $params = array();
//    $params['id'] = $item['id'] - 100;
//    $ret = pdo_update($tablename, $params, array('id' => $item['id']));

}

?>
