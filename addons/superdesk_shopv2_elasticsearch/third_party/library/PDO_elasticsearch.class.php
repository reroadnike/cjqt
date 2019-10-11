<?php


//include_once(IA_ROOT . '/addons/superdesk_shopv2_elasticsearch/vendor/qieangel2013/esparser/src/library/EsParser.php');

//echo dirname(__FILE__);
//include_once (dirname(__FILE__) . '../../vendor/qieangel2013/esparser/src/library/EsParser.php');

/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 4/8/18
 * Time: 11:05 AM
 */
abstract class PDO_elasticsearch
{
    public $__query = '';
    public $__result = null;
    public $__boundParams = Array();

    abstract protected function fetchAll($sql,$params);

    public function execute($sql, $params)
    {

        $__query = $sql;

        if (count($params) > 0) {


            foreach ($params as $k => $v) {

//                echo $k . $v;

                if (!is_int($k) || substr($k, 0, 1) === ':') { // 处理:xxx方式

                    if (!isset($tempf)) {
                        $tempf = $tempr = array();
                    }


                    array_push($tempf, $k);
                    array_push($tempr, $v);// 不安全 本地不报错
//                    array_push($tempr, '"' . @mysql_escape_string($v) . '"');// 本地不报错 正式服务报错
//                    array_push($tempr, '"' . @mysql_real_escape_string($v) . '"');// 本地报错




                } else { // 处理?号方式

                    $parse   = create_function('$v', 'return \'"\'.mysql_escape_string($v).\'"\';');
                    $__query = preg_replace("/(\?)/e", '$parse($array[$k++]);', $__query);

                    break;
                }
            }


            if (isset($tempf)) {

                foreach ($tempf as $k => $v) {
                    $search[$k] = '/' . preg_quote($tempf[$k], '`') . '\b/';
                }

                $__query = preg_replace($search, $tempr, $__query);
                //$__query = str_replace($tempf, $tempr, $__query);
            }
        }


//        if (is_null($this->__result = &$this->__uquery($__query)))
//            $keyvars = false;
//        else
//            $keyvars = true;
//        $this->__boundParams = array();


        return $__query;
    }




}