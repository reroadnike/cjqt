<?php

include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');
include_once(IA_ROOT . '/addons/superdesk_core/model/tb_user.class.php');
include_once(IA_ROOT . '/addons/superdesk_core/model/out_db/tb_user.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/member/member.class.php');


/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 1/30/18
 * Time: 4:51 PM
 */
class TbuserService
{

    private $_redis;

    private $_tb_userModel;
    private $_out_tb_userModel;

    private $_memberModel;


    public function __construct()
    {
        $this->_redis = new RedisUtil();


        $this->_tb_userModel     = new tb_userModel();
        $this->_out_tb_userModel = new superdesk_core\model\out_db\tb_userModel();

        $this->_memberModel = new memberModel();

    }

    public function getOneByUserMobile($userMobile)
    {

        return $this->_tb_userModel->getOneByUserMobile($userMobile);
    }

    public function getOneByCoreUser($core_user)
    {

        return $this->_tb_userModel->getOne($core_user);
    }

    public function getOneByUserMobileVirIdOrgId($userMobile, $virId, $orgId)
    {
        return $this->_tb_userModel->getOneByUserMobileVirIdOrgId($userMobile, $virId, $orgId);
    }

    /**
     * 全量更新
     * 0 -> 4076
     */
    public function syncAll()
    {

        $source_records   = $this->_out_tb_userModel->syncAll();
        $source_total     = $source_records['total'];
        $source_page      = $source_records['page'];
        $source_page_size = $source_records['page_size'];
        $source_list      = $source_records['data'];

        $this->replace($source_list);

    }

    public function checkSyncCreateTime()
    {
        global $_GPC, $_W;

        $result = $this->_tb_userModel->checkSyncCreateTime();

        if (sizeof($result) > 0) {
            return $result[0]['createTime'];
        } else {
            return '0000-00-00 00:00:00';
        }
    }

    /**
     * @return string
     */
    public function checkSyncModifyTime()
    {
        global $_GPC, $_W;

        $result = $this->_tb_userModel->checkSyncModifyTime();

        if (sizeof($result) > 0) {
            return $result[0]['modifyTime'];
        } else { // 没记录的情况
            return '0000-00-00 00:00:00';
        }

    }

    /**
     * 按新增时间增量同步
     *
     * @param $separate_time
     */
    public function syncIncrementCreateTime($syncInit = false)
    {
        global $_GPC, $_W;

        if ($syncInit) {
            $list_create_time = '0000-00-00 00:00:00';
        } else {
            $list_create_time = $this->checkSyncCreateTime();
        }

        echo '同步 CreateTime > ' . $list_create_time;
        echo PHP_EOL;

        $source_records   = $this->_out_tb_userModel->syncIncrementCreateTime($list_create_time);
        $source_total     = $source_records['total'];
        $source_page      = $source_records['page'];
        $source_page_size = $source_records['page_size'];
        $source_list      = $source_records['data'];

        echo '同步 total > ' . $source_total;
        echo PHP_EOL;
        echo '同步 page > ' . $source_page;
        echo PHP_EOL;
        echo '同步 pageSize > ' . $source_page_size;
        echo PHP_EOL;

        $this->replace($source_list);
    }

    /**
     * 按更新时间增量同步
     *
     * @param string $list_modify_time
     */
    public function syncIncrementModifyTime($syncInit = false)
    {

        global $_GPC, $_W;

        if ($syncInit) {
            $list_modify_time = '0000-00-00 00:00:00';
        } else {
            $list_modify_time = $this->checkSyncModifyTime();
        }

        echo '同步 ModifyTime > ' . $list_modify_time;
        echo PHP_EOL;

        $source_records   = $this->_out_tb_userModel->syncIncrementModifyTime($list_modify_time);
        $source_total     = $source_records['total'];
        $source_page      = $source_records['page'];
        $source_page_size = $source_records['page_size'];
        $source_list      = $source_records['data'];

        echo '同步 total > ' . $source_total;
        echo PHP_EOL;
        echo '同步 page > ' . $source_page;
        echo PHP_EOL;
        echo '同步 pageSize > ' . $source_page_size;
        echo PHP_EOL;

        $this->replace($source_list);

    }

//{
//    "ID": "3208",
//    "userName": "吴仕雄",
//    "nickName": null,
//    "userMobile": "15070961064",
//    "userType": null,
//    "userSex": null,
//    "userCardNo": null,
//    "birthday": null,
//    "userPhotoUrl": null,
//    "password": null,
//    "status": "1",
//    "suggestion": null,
//    "address": null,
//    "imageUrl01": "",
//    "imageUrl02": "",
//    "imageUrl03": "",
//    "organizationId": "474",
//    "virtualArchId": "1619",
//    "userNumber": "",
//    "enteringTime": null,
//    "positionName": "南昌公司>>九江经开区综合服务中心",
//    "departmentId": null,
//    "facePlusUserId": null,
//    "roleType": "2",
//    "noticePower": "0",
//    "creator": null,
//    "createTime": "2017-08-15 00:00:00",
//    "modifier": null,
//    "modifyTime": "2018-06-11 18:28:25",
//    "isEnabled": "1",
//    "isSyncNeigou": "0",
//    "isSyncSpaceHome": null
//}
    /**
     * @param $source_list
     */
    private function replace($source_list)
    {

        global $_GPC, $_W;

        foreach ($source_list as $index => $target_record) {

//            $_id = $target_record['id'];// 妈的一坑 是大写的
            $_id = $target_record['ID'];

            echo json_encode($target_record, JSON_UNESCAPED_UNICODE);
            echo PHP_EOL;

            // 解决 超级前台tb_user 替换 superdesk_core_tb_user
            $this->_tb_userModel->replace($target_record, $_id);




            // 解决 同步后 superdesk_shop_member 企业 不对问题
            $this->_memberModel->updateByColumn(array(

//                'core_enterprise' => $target_record['virtualArchId'],
//                'realname'        => $target_record['userName'],

                'mobile'            => $target_record['userMobile'],
                'core_enterprise'   => $target_record['virtualArchId'],
                'core_organization' => $target_record['organizationId'],
                'realname'          => $target_record['userName'],

            ), array(
//                'mobile' => $target_record['userMobile']
                'core_user' => $_id
            ));


        }
    }
}