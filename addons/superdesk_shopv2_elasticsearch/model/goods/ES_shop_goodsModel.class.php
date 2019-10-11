<?php


include_once(IA_ROOT . '/addons/superdesk_shopv2_elasticsearch/third_party/library/PDO_elasticsearch.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2_elasticsearch/vendor/qieangel2013/esparser/src/library/EsParser.php');

/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 4/8/18
 * Time: 1:49 PM
 */
class ES_shop_goodsModel extends PDO_elasticsearch
{

    private $es_config;

    public function __construct()
    {
        $this->es_config = array(
            'index'   => "db_super_desk",
            'type'    => "ims_superdesk_shop_goods",
            'url'     => "http://localhost:9200",
            //            'url'     => "http://47.107.240.183:9200",
            'version' => "6.1.1" //1.x 2.x 5.x 6.x,可以不配置，系统会请求获取版本，这样会多一次请求,建议配置一下
        );
    }

    function fetchAll($sql, $params)
    {

//        $sql =
//            ' SELECT  id,title,thumb,marketprice,productprice,minprice,maxprice,isdiscount,isdiscount_time,isdiscount_discounts,sales,total,description,bargain,jd_vop_sku  '.
//            ' FROM ims_superdesk_shop_goods '.
//            ' WHERE '.
//            '       uniacid = 16 '.
//            '       AND deleted = 0 '.
//            '       and status = true '.
//            '       and checked = 0 '.
//            '       and merchid in ( 8,11,13,14,15,16,18,19,20) '.
//            '       and minprice > 0.00 '.
//            ' ORDER BY  displayorder desc,createtime desc  LIMIT 0,10';
//
//        $params = array(
//        //    ':uniacid' => 16
//        );

        $select_fields =
            " id,title,thumb,marketprice,productprice,minprice,maxprice,isdiscount,isdiscount_time,isdiscount_discounts,sales,total,description,bargain,jd_vop_sku ";

        $select_fields_array = explode(',', trim($select_fields));

//        if($params[':status'] == 1){
//            $params[':status'] = true;
//        }

//        socket_log(json_encode($params,JSON_UNESCAPED_UNICODE));
        //优利德
//        if(isset($params[':keywords0']) && $params[':keywords0']=='%优利德%'){
//            $params[':keywords0']='%uni%';
//        }
//
//        if(isset($params[':keywords0']) && $params[':keywords0']=='%超宝%'){
//            $params[':keywords0']='%chaobao%';
//        }

        load()->func('logging');
//        logging_run($sql, 'trace', 'Elasticsearch');
//        socket_log('debug'.$sql);
        $__query = $this->execute($sql, $params);
        logging_run($__query, 'trace', 'Elasticsearch');

        $parser = new EsParser($__query, false, $this->es_config);//第三个参数是es的配置参数，一定要配置
//        print_r($parser->result);//打印结果
//        print_r($parser->explain()); //打印dsl

//        socket_log('Elasticsearch dsl' . $parser->explain());
		logging_run($parser->explain(), 'trace', 'Elasticsearch');
        //WeUtility::logging('Elasticsearch sql ', $parser->explain());
//        WeUtility::logging('Elasticsearch dsl ', $parser->explain());
//        WeUtility::logging('Elasticsearch result ', $parser->result);

//        $result = json_decode($parser->result,true);// old

        $result = json_decode($parser->build(), true);
        socket_log('Elasticsearch result' . json_encode($result, JSON_UNESCAPED_UNICODE));
		

        if (isset($result['error']['root_cause'])) {

//            echo json_encode($result,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
//            foreach ($result['error']['root_cause'] as $item){
//
//                $item['reason'] = str_replace('failed to create query: ','',$item['reason']);
//                echo 'failed to create query: ';
//                echo PHP_EOL;
//                echo json_encode(json_decode($item['reason'],true),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
//                socket_log(json_encode(json_decode($item['reason'],true),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
//            }

            return array('list' => array(), 'total' => 0, 'error' => json_encode($result['error'], JSON_UNESCAPED_UNICODE));

        } else {

//            die(json_encode($result,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));

            $goods_list = array();
            foreach ($result['result'] as $index => $es_goods) {

//                foreach ($es_goods['_source'] as $field => $_value_){
//                    if(!in_array($field,$select_fields_array)){
//                        unset($es_goods['_source'][$field]);
//                    }
//                }

                $goods_list[] = $es_goods['_source'];

            }

//            die(json_encode($goods_list,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));


            return array('list' => $goods_list, 'total' => $result['total']);
        }
    }
	
}
