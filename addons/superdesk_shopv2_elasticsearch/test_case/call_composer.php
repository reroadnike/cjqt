<?php
/**
 *
 * 有问题
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 4/3/18
 * Time: 9:22 PM
 * http://192.168.1.124/superdesk/addons/superdesk_shopv2_elasticsearch/call_composer.php
 */

require __DIR__ . '/vendor/autoload.php';


//$sql = 'select * from alp_dish_sales_saas where sid in(994,290) limit 1,10';
//$sql='update alp_dish_sales_saas set mid=3  where adsid=15125110';
//$sql='delete from alp_dish_sales_saas where adsid=15546509';
//$sql="select *,concat_ws('_',category_name.keyword,dish_name.keyword,sku_name.keyword) as dfg from alp_dish_sales_saas where sale_date>'2017-01-01' and sale_date<'2017-09-02' group by dfg order by total_count desc";


// init
// curl -X PUT 'localhost:9200/superdesk_shop_goods'
// {"acknowledged":true,"shards_acknowledged":true,"index":"superdesk_shop_goods"}[l[linjinyu@loc[linjinyu@localhost ~]$[linjinyu@localhost ~][linjinyu@localh[l[[[[li[lin[linj[l[l[[[[[[l[[[[[[[[linjinyu@localhost ~]$
// 服务器返回一个 JSON 对象，里面的acknowledged字段表示操作成功。


// curl -X GET 'localhost:9200/superdesk_shop_goods/_mapping'


//$sql       = 'select *,DATE_FORMAT(sale_date,"%Y-%m-%d") as days from alp_dish_sales_saas group by days ';
$sql       = 'select * from superdesk_shop_goods';
$es_config = array(
    'index'   => "superdesk_shop_goods",
//    'type'    => "superdesk_shop_goods",
    'url'     => "http://localhost:9200",
    'version' => "6.1.1" //1.x 2.x 5.x 6.x,可以不配置，系统会请求获取版本，这样会多一次请求,建议配置一下
);
$parser    = new EsParser($sql, true, $es_config);//第三个参数是es的配置参数，一定要配置
//print_r($parser->result);//打印结果
//print_r($parser->explain());//打印dsl