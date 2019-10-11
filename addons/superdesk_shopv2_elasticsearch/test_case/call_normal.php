<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 4/3/18
 * Time: 9:23 PM
 *
 * view-source:http://192.168.1.124/superdesk/addons/superdesk_shopv2_elasticsearch/call_normal.php
 */

require_once dirname(__FILE__) . '/vendor/qieangel2013/esparser/src/library/EsParser.php';
//$sql = 'select * from alp_dish_sales_saas where sid in(994,290) limit 1,10';
//$sql='update alp_dish_sales_saas set mid=3  where adsid=15125110';
//$sql='delete from alp_dish_sales_saas where adsid=15546509';
//$sql="select *,concat_ws('_',category_name.keyword,dish_name.keyword,sku_name.keyword) as dfg from alp_dish_sales_saas where sale_date>'2017-01-01' and sale_date<'2017-09-02' group by dfg order by total_count desc";




//curl -XGET 'http://localhost:9200/db_super_desk/ims_superdesk_shop_goods/_search?pretty'

//./logstash-plugin install logstash-input-jdbc

//./logstash-plugin install logstash-output-elasticsearch


//$sql       = 'select * from ims_superdesk_shop_goods';

$select_fields =
    " id,title,thumb,marketprice,productprice,minprice,maxprice,isdiscount,isdiscount_time,isdiscount_discounts,sales,total,description,bargain,jd_vop_sku ";

$select_fields_array = explode(',',trim($select_fields));

$sql =
    ' SELECT  id,title,thumb,marketprice,productprice,minprice,maxprice,isdiscount,isdiscount_time,isdiscount_discounts,sales,total,description,bargain,jd_vop_sku  '.
    ' FROM ims_superdesk_shop_goods '.
    ' WHERE '.
    '       uniacid = 16 '.
    '       AND deleted = 0 '.
    '       and status = true '.
    '       and checked = 0 '.
    '       and merchid in ( 8,11,13,14,15,16,18,19,20) '.
    '       and minprice > 0.00 '.
    ' ORDER BY  displayorder desc,createtime desc  LIMIT 0,10';

$es_config = array(
    'index'   => "db_super_desk",
    'type'    => "ims_superdesk_shop_goods",
    'url'     => "http://localhost:9200",
    'version' => "6.1.1" //1.x 2.x 5.x 6.x,可以不配置，系统会请求获取版本，这样会多一次请求,建议配置一下
);
$parser    = new EsParser($sql, true, $es_config);//第三个参数是es的配置参数，一定要配置
//print_r($parser->result);//打印结果
//print_r($parser->explain()); //打印dsl

//

$result = json_decode($parser->result,true);

if(isset($result['error']['root_cause'])){
    foreach ($result['error']['root_cause'] as $item){

        $item['reason'] = str_replace('failed to create query: ','',$item['reason']);
        echo 'failed to create query: ';
        echo PHP_EOL;
        echo json_encode(json_decode($item['reason'],true),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
}


else {

    $goods_list = array();
    foreach ($result['result'] as $index => $es_goods){

        foreach ($es_goods['_source'] as $field => $_value_){
            if(!in_array($field,$select_fields_array)){
                unset($es_goods['_source'][$field]);
            }
        }

        $goods_list[] = $es_goods['_source'];

    }


    die(json_encode($goods_list,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
}

//{
//    "from": 0,
//    "size": "10",
//    "query": {
//    "bool": {
//        "filter": [
//                {
//                    "bool": {
//                    "must": [
//                            {
//                                "match_phrase": {
//                                "uniacid": {
//                                    "query": ":uniacid"
//                                    }
//                                }
//                            }
//                        ]
//                    }
//                },
//                {
//                    "bool": {
//                    "must": [
//                            {
//                                "match_phrase": {
//                                "deleted": {
//                                    "query": "0"
//                                    }
//                                }
//                            }
//                        ]
//                    }
//                },
//                {
//                    "bool": {
//                    "must": [
//                            {
//                                "match_phrase": {
//                                "status": {
//                                    "query": "1"
//                                    }
//                                }
//                            }
//                        ]
//                    }
//                },
//                {
//                    "bool": {
//                    "must": [
//                            {
//                                "match_phrase": {
//                                "checked": {
//                                    "query": "0"
//                                    }
//                                }
//                            }
//                        ]
//                    }
//                },
//                {
//                    "terms": {
//                    "merchid": [
//                        "8",
//                        "11",
//                        "13",
//                        "14",
//                        "15",
//                        "16",
//                        "18",
//                        "19",
//                        "20"
//                    ]
//                    }
//                },
//                {
//                    "range": {
//                    "minprice": {
//                        "gt": "0.00",
//                            "time_zone": "+08:00"
//                        }
//                    }
//                }
//            ]
//        }
//    },
//    "sort": [
//        {
//            "displayorder": {
//            "order": "DESC"
//            }
//        },
//        {
//            "createtime": {
//            "order": "DESC"
//            }
//        }
//    ]
//}