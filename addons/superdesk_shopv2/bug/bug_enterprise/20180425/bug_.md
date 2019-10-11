Warning: Use of undefined constant FRAME - assumed 'FRAME' (this will throw an Error in a future version of PHP) in /data/wwwroot/default/plugins/web/source/site/__init.php on line 42

Warning: Use of undefined constant FRAME - assumed 'FRAME' (this will throw an Error in a future version of PHP) in /data/wwwroot/default/plugins/web/common/common.func.php on line 171

Warning: Use of undefined constant FRAME - assumed 'FRAME' (this will throw an Error in a future version of PHP) in /data/wwwroot/default/plugins/web/source/site/__init.php on line 43

Fatal error: Allowed memory size of 268435456 bytes exhausted (tried to allocate 20480 bytes) in /data/wwwroot/default/plugins/framework/class/db.class.php on line 166



````
// 发现傻X做法
//        $sql =
//            ' SELECT g.id ' .
//            ' FROM ' . tablename('superdesk_shop_goods') . 'g' .
//            $sqlcondition .
//            $condition .
//            $groupcondition;
//        $total_all = pdo_fetchall($sql, $params);
//        $total     = count($total_all);
//        unset($total_all);
        // 修正
        $sql =
            ' SELECT COUNT(g.id) ' .
            ' FROM ' . tablename('superdesk_shop_goods') . 'g' .
            $sqlcondition .
            $condition .
            $groupcondition;
        $total = pdo_fetchcolumn(
            $sql,
            $params
        );
````