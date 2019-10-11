<?php

include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');

include_once(IA_ROOT . '/addons/superdesk_core/model/organization.class.php');

include_once(IA_ROOT . '/addons/superdesk_core/model/out_db/tb_organization.class.php');

/**
 * 项目
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 1/30/18
 * Time: 2:27 PM
 */
class OrganizationService
{

    private $_organizationModel;

    private $_tb_organizationModel;

    private $_redis;

    public function __construct()
    {
        $this->_redis                = new RedisUtil();
        $this->_organizationModel    = new organizationModel();
        $this->_tb_organizationModel = new superdesk_core\model\out_db\tb_organizationModel();
    }

    public function getOneByOrganizationId($organizationId)
    {

        return $this->_organizationModel->getOne($organizationId);
    }

    /**
     * 全量更新
     * 392 -> 459
     */
    public function syncAll()
    {
        global $_GPC, $_W;

        $source_records   = $this->_tb_organizationModel->syncAll();
        $source_total     = $source_records['total'];
        $source_page      = $source_records['page'];
        $source_page_size = $source_records['page_size'];
        $source_list      = $source_records['data'];


        $this->replace($source_list);
    }

    public function checkSyncCreateTime()
    {
        global $_GPC, $_W;

        $result = $this->_organizationModel->checkSyncCreateTime();

        if (sizeof($result) > 0) {
            return $result[0]['createTime'];
        } else {
            return '0000-00-00 00:00:00';
        }
    }

    public function checkSyncModifyTime()
    {
        global $_GPC, $_W;

        $result = $this->_organizationModel->checkSyncModifyTime();

        if (sizeof($result) > 0) {
            return $result[0]['modifyTime'];
        } else {
            return '0000-00-00 00:00:00';
        }

    }

    /**
     * 按新增时间增量同步
     */
    public function syncIncrementCreateTime()
    {
        global $_GPC, $_W;

        $list_create_time = $this->checkSyncCreateTime();

        echo '同步 CreateTime > '.$list_create_time;
        echo PHP_EOL;


        $source_records   = $this->_tb_organizationModel->syncIncrementCreateTime($list_create_time);
        $source_total     = $source_records['total'];
        $source_page      = $source_records['page'];
        $source_page_size = $source_records['page_size'];
        $source_list      = $source_records['data'];

        echo '同步 total > '.$source_total;
        echo PHP_EOL;
        echo '同步 page > '.$source_page;
        echo PHP_EOL;
        echo '同步 pageSize > '.$source_page_size;
        echo PHP_EOL;

        $this->replace($source_list);
    }

    /**
     * 按更新时间增量同步
     */
    public function syncIncrementModifyTime()
    {

        global $_GPC, $_W;

        $list_modify_time = $this->checkSyncModifyTime();

        echo '同步 ModifyTime > '.$list_modify_time;
        echo PHP_EOL;

        $source_records   = $this->_tb_organizationModel->syncIncrementModifyTime($list_modify_time);
        $source_total     = $source_records['total'];
        $source_page      = $source_records['page'];
        $source_page_size = $source_records['page_size'];
        $source_list      = $source_records['data'];

        echo '同步 total > '.$source_total;
        echo PHP_EOL;
        echo '同步 page > '.$source_page;
        echo PHP_EOL;
        echo '同步 pageSize > '.$source_page_size;
        echo PHP_EOL;

        $this->replace($source_list);

    }

    private function replace($source_list){

        global $_GPC, $_W;

        foreach ($source_list as $index => $target_record) {

            $_id = $target_record['ID'];
            unset($target_record['ID']);
            unset($target_record['isSyncNeigou']);


            $target_record['id'] = $_id;

            echo json_encode($target_record, JSON_UNESCAPED_UNICODE);
            echo PHP_EOL;

            $this->_organizationModel->replace($target_record, $_id);

        }
    }
}