<?php

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/balance_detail.class.php');
include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/balance_detail_processing.class.php');


/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/23/17
 * Time: 4:57 PM
 */
class BalanceDetailService
{


    private $_balance_detailModel;
    private $_balance_detail_processingModel;

    function __construct()
    {
        $this->_balance_detailModel = new balance_detailModel();
        $this->_balance_detail_processingModel = new balance_detail_processingModel();

    }

    public function getbalanceDetailByOrderId($orderid){
        $data = array();

        if(empty($orderid)){
            return $data;
        }

        $where = array('orderId' => $orderid);
        $result = $this->_balance_detailModel->queryAll($where);
        $data = $result['data'];

        return $data;
    }

    /**
     * @param $target_record
     * @param $_id
     * 初始化测试
     *

        $t = array(
        'accountType' => 1,
        'processing' => 1,
        'process_result' => '123',
        'id'     => 13
        );

        $id = $t['id'];


        include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/BalanceDetailService.class.php');
        $_balanceDetailService = new BalanceDetailService();
        $_balanceDetailService->insertBlanceDetailAndProcessing($t, $id);
        die;
     */
    public function insertBlanceDetailAndProcessing($target_record, $_id){

        $this->_balance_detailModel->replace($target_record, $_id);

        $target_record = array(
            'processing' => $target_record['processing'],
            'process_result' => $target_record['process_result'],
            'id' => $target_record['id'],
        );
        $this->_balance_detail_processingModel->replace($target_record, $_id);
    }
}