<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/05/17
 * Time: 18:53
 *
 * view-source:http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_shopv2_elasticsearch&do=elasticsearch_dictionary
 * view-source:https://wxm.avic-s.com//app/index.php?i=16&c=entry&m=superdesk_shopv2_elasticsearch&do=elasticsearch_dictionary
 */

//Response Headers
//HTTP/1.1 200 OK
//Server: nginx
//Date: Thu, 17 May 2018 13:54:29 GMT
//Content-Type: text/html; charset=utf-8
//Transfer-Encoding: chunked
//Connection: keep-alive
//Vary: Accept-Encoding
//Expires: Thu, 19 Nov 1981 08:52:00 GMT
//Cache-Control: no-store, no-cache, must-revalidate
//Pragma: no-cache
//Content-Encoding: gzip


global $_GPC, $_W;


//echo $_SERVER['HTTP_IF_NONE_MATCH'];echo PHP_EOL;
//echo $_SERVER['HTTP_IF_MODIFIED_SINCE'];echo PHP_EOL;
//
//
//header('Last-Modified: '.gmdate('D, d M Y H:i:s',time())." GMT");
//header('Etag: '.md5('djlfkjlasjfidsjaf'));
//header("Cache-Control:max-age=3600");
//die;

//echo $_SERVER['HTTP_IF_NONE_MATCH'];echo PHP_EOL;//       2 etag
//echo $_SERVER['HTTP_IF_MODIFIED_SINCE'];echo PHP_EOL;

//$gmdate_modified = gmdate ('D, d M Y H:i:s') . ' GMT';// 这个是错的


include_once(IA_ROOT . '/addons/superdesk_shopv2_elasticsearch/model/elasticsearch_dictionary.class.php');
$elasticsearch_dictionary = new elasticsearch_dictionaryModel();


$params = array(
    ':TABLE_SCHEMA' => 'db_super_desk',
    ':TABLE_NAME'   => 'ims_' . $elasticsearch_dictionary->table_name

);

$table_last_updatetime = pdo_fetchcolumn(
    ' select UPDATE_TIME ' .
    ' from information_schema.TABLES ' .
    ' where TABLE_SCHEMA=:TABLE_SCHEMA ' .
    '       and TABLE_NAME=:TABLE_NAME ',
    $params);

//SELECT UPDATE_TIME FROM `TABLES` WHERE TABLE_SCHEMA='db_super_desk' and TABLE_NAME = 'ims_elasticsearch_dictionary'

//echo $table_last_updatetime;
//die();

$gmdate_modified = gmdate('D, d M Y H:i:s', strtotime($table_last_updatetime)) . " GMT";

$browserCachedCopyTimestamp = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);

if ($browserCachedCopyTimestamp == strtotime($table_last_updatetime)) {
    header('Last-Modified: ' . $_SERVER['HTTP_IF_MODIFIED_SINCE']);
    header("Cache-Control:max-age=3600");
    header('HTTP/1.1 304');
    die();

} else {
    header('Last-Modified: ' . $gmdate_modified);
    header("Cache-Control:max-age=3600");
//    header('Etag:' . $gmdate_modified);

    //调整获取热词数量 luoxt 20190621
    $result    = $elasticsearch_dictionary->queryAll([], 0, 10000);
    $total     = $result['total'];
    $page      = $result['page'];
    $page_size = $result['page_size'];
    $list      = $result['data'];
    foreach ($list as $item) {
        echo $item['word'];
        echo PHP_EOL;
    }
}

