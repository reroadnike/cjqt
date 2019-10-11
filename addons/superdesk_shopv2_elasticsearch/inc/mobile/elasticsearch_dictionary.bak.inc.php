<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/05/17
 * Time: 18:53
 *
 * view-source:http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_shopv2_elasticsearch&do=elasticsearch_dictionary
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




if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {

    $browserCachedCopyTimestamp = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);

    if ($browserCachedCopyTimestamp == $browserCachedCopyTimestamp) {
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 3600) . " GMT");
        //    header('Expires: ' . gmdate ('D, d M Y H:i:s', time()));
        header('Last-Modified: ' . $_SERVER['HTTP_IF_MODIFIED_SINCE']);
        header('HTTP/1.1 304');
        die();
    } else{
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . " GMT");

        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 3600) . " GMT");
//    header('Expires: ' . gmdate ('D, d M Y H:i:s', time()));

        header("Cache-Control:max-age=3600");
        //header('Cache-Control: max-age=86400,must-revalidate');

        $gmdate_modified = gmdate ('D, d M Y H:i:s') . ' GMT';
        header('Last-Modified: ' . $gmdate_modified);
        header('Etag:' . $gmdate_modified);


        include_once(IA_ROOT . '/addons/superdesk_shopv2_elasticsearch/model/elasticsearch_dictionary.class.php');
        $elasticsearch_dictionary = new elasticsearch_dictionaryModel();


        $result = $elasticsearch_dictionary->queryAll();

        $total     = $result['total'];
        $page      = $result['page'];
        $page_size = $result['page_size'];
        $list      = $result['data'];

        foreach ($list as $item){

            echo $item['word'];
            echo PHP_EOL;
        }
    }

} else {

    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . " GMT");

    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 3600) . " GMT");
//    header('Expires: ' . gmdate ('D, d M Y H:i:s', time()));

    header("Cache-Control:max-age=3600");
    //header('Cache-Control: max-age=86400,must-revalidate');

    $gmdate_modified = gmdate ('D, d M Y H:i:s') . ' GMT';
    header('Last-Modified: ' . $gmdate_modified);
    header('Etag:' . $gmdate_modified);


    include_once(IA_ROOT . '/addons/superdesk_shopv2_elasticsearch/model/elasticsearch_dictionary.class.php');
    $elasticsearch_dictionary = new elasticsearch_dictionaryModel();


    $result = $elasticsearch_dictionary->queryAll();

    $total     = $result['total'];
    $page      = $result['page'];
    $page_size = $result['page_size'];
    $list      = $result['data'];

    foreach ($list as $item){

        echo $item['word'];
        echo PHP_EOL;
    }
}

