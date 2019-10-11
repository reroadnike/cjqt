<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 6/11/18
 * Time: 4:11 PM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_search_search
 */


include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/SearchService.class.php');
$_searchService = new SearchService();

$response = $_searchService->searchSearch("联想");

die(json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
