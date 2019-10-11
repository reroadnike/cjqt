<?php
include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');

include_once(IA_ROOT . '/addons/superdesk_core/model/virtualarchitecture.class.php');

include_once(IA_ROOT . '/addons/superdesk_core/model/out_db/tb_virtualarchitecture.class.php');

/**
 * 企业
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 1/24/18
 * Time: 11:19 AM
 */
class VirtualarchitectureService
{

    private $_virtualarchitectureModel;

    private $_tb_virtualarchitectureModel;

    private $_redis;

    public function __construct()
    {
        $this->_redis                       = new RedisUtil();
        $this->_virtualarchitectureModel    = new virtualarchitectureModel();
        $this->_tb_virtualarchitectureModel = new superdesk_core\model\out_db\tb_virtualarchitectureModel();
    }

    /**
     * 根据ID查 enterprise_name
     *
     * @param $id
     *
     * @return bool
     */
    public function getEnterpriseNameById($id)
    {
        return $this->_virtualarchitectureModel->getEnterpriseNameById($id);
    }

    public function getOneByVirtualArchId($virtualArchId)
    {

        return $this->_virtualarchitectureModel->getOne($virtualArchId);
    }

    public function getCacheEnterprise2virtualarchitecture($e_id)
    {

        global $_W, $_GPC;

        $cache_key = 'superdesk_shop_member_' . 'cache_enterprise2virtualarchitecture' . ':' . $_W['uniacid'];

        if ($this->_redis->ishExists($cache_key, $e_id) == 1) {

            $cache_data = $this->_redis->hget($cache_key, $e_id);
            return $cache_data;

        } else {
            return 0;
        }
    }

    /**
     * @param zc_enterprise .$e_id
     * @param zc_enterprise .$e_number
     *
     * @return int
     */
    public function cacheMappingByCodeNumber($e_id, $e_number)
    {

        global $_W, $_GPC;

        $is_mapping = 0;

        $cache_key = 'superdesk_shop_member_' . 'cache_enterprise2virtualarchitecture' . ':' . $_W['uniacid'];

        if ($this->_redis->ishExists($cache_key, $e_id) == 1) {

//            $cache_data = $this->_redis->hget($cache_key, $e_id);
            $is_mapping = 1;

        } else {
            $item = $this->_virtualarchitectureModel->getOneByCodeNumber($e_number);

            if ($item) {
                $this->_redis->hset($cache_key, $e_id, $item['id']);
                $is_mapping = 1;
            }
        }

        return $is_mapping;


    }

    /**
     * 全量更新
     * 1232 -> 1532
     */
    public function syncAll()
    {
        global $_W, $_GPC;

        $source_records   = $this->_tb_virtualarchitectureModel->syncAll();
        $source_total     = $source_records['total'];
        $source_page      = $source_records['page'];
        $source_page_size = $source_records['page_size'];
        $source_list      = $source_records['data'];

        $this->replace($source_list);


    }

    public function checkSyncCreateTime()
    {
        global $_GPC, $_W;

        $result = $this->_virtualarchitectureModel->checkSyncCreateTime();

        if (sizeof($result) > 0) {
            return $result[0]['createTime'];
        } else {
            return '0000-00-00 00:00:00';
        }
    }

    public function checkSyncModifyTime()
    {
        global $_GPC, $_W;

        $result = $this->_virtualarchitectureModel->checkSyncModifyTime();

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

        echo '同步 CreateTime > ' . $list_create_time;
        echo PHP_EOL;


        $source_records   = $this->_tb_virtualarchitectureModel->syncIncrementCreateTime($list_create_time);
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
     */
    public function syncIncrementModifyTime()
    {

        global $_GPC, $_W;

        $list_modify_time = $this->checkSyncModifyTime();

        echo '同步 ModifyTime > ' . $list_modify_time;
        echo PHP_EOL;

        $source_records   = $this->_tb_virtualarchitectureModel->syncIncrementModifyTime($list_modify_time);
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

    private function replace($source_list)
    {

        global $_GPC, $_W;

        foreach ($source_list as $index => $target_record) {

            $_id = $target_record['id'];

            echo json_encode($target_record, JSON_UNESCAPED_UNICODE);
            echo PHP_EOL;

            $this->_virtualarchitectureModel->replace($target_record, $_id);

        }
    }
}