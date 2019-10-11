<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 1/23/18
 * Time: 3:12 PM
 */

if (!defined('IN_IA')) {
    exit('Access Denied');
}

include_once(IA_ROOT . '/addons/superdesk_core/model/organization.class.php');
include_once(IA_ROOT . '/addons/superdesk_core/model/virtualarchitecture.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/__plugin__/enterprise/enterprise_user.class.php');

//include_once(IA_ROOT . '/addons/superdesk_shopv2/model/__plugin__/merch/merch_x_enterprise.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/service/plugin/MerchService.class.php');



class Enterprise_SuperdeskShopV2Page extends PluginWebPage
{

    private $_organizationModel;
    private $_virtualarchitectureModel;

    private $_enterprise_userModel;

//    private $_merch_x_enterpriseModel;
    private $_plugin_merchService;

    public function __construct()
    {
        // mark welfare
        switch (SUPERDESK_SHOPV2_MODE_USER) {
            case 1:// 1 超级前台
                $this->_organizationModel        = new organizationModel();
                $this->_virtualarchitectureModel = new virtualarchitectureModel();
                break;
            case 2:// 2 福利商城
                $this->_enterprise_userModel = new enterprise_userModel();
                break;
        }

//        $this->_merch_x_enterpriseModel = new merch_x_enterpriseModel();
        $this->_plugin_merchService = new MerchService();
    }

    public function main()
    {
        global $_W;
        global $_GPC;

        $merchid = $_GPC['merchid'];

        if (empty($merchid)) {
            $this->message('请选择要服务企业的商户', webUrl('merch/user'), 'error');
        }

        $_merch_user = $this->_plugin_merchService->merchUserGetOne($merchid);




        $page      = $_GPC['page'];
        $page_size = 20;

        $where = array(
            'merchid' => $merchid
        );

        $result = $this->_plugin_merchService->merchXEnterpriseQueryAll($where, $page, $page_size);

        $total     = $result['total'];
        $page      = $result['page'];
        $page_size = $result['page_size'];
        $list      = $result['data'];

        $pager = pagination($total, $page, $page_size);

        load()->func('tpl');

        include $this->template();
    }

    public function add()
    {
        $this->post();
    }

    public function edit()
    {
        $this->post();
    }

    public function ajax()
    {

        global $_W;
        global $_GPC;

//        $organization_code = $_GPC['organization_code'];
//        $_organization = $this->_organizationModel->getOneByColumn(array("code" => $organization_code));
//        $_organization['id']

        $organization_id = $_GPC['organization_id'];

        if ($organization_id) {

            $_result  = $this->_virtualarchitectureModel->queryForUsersAjax(array(
                "organizationId" => $organization_id
            ), 1, 999);

            $virtuals = $_result['data'];


            die(json_encode($virtuals,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
        }
    }

    protected function post()
    {
        global $_W;
        global $_GPC;


        $merchid = intval($_GPC['merchid']);

        if (empty($merchid)) {
            $this->message('请选择要服务企业的商户', webUrl('merch/user'), 'error');
        }

        $_merch_user = $this->_plugin_merchService->merchUserGetOne($merchid);

//        socket_log(json_encode($_merch_user,JSON_UNESCAPED_UNICODE));

        /************ 初始化项目 ***********/
        // mark welfare
        switch(SUPERDESK_SHOPV2_MODE_USER){
            case 1:
                // 项目
                $result_organization   = $this->_organizationModel->querySelector(
                    array(
                        "isEnabled"      => 1,
                        "status"         => 1   //0-待审核;1-通过;2-不通过
                    ), 1, 999);
                $selector_organization = $result_organization['data'];

                socket_log(json_encode($selector_organization,JSON_UNESCAPED_UNICODE));

                //企业
                $selector_virtuals = array();

                if (sizeof($selector_organization) > 0) {



                    $item['organization_id'] = $selector_organization[0]['ID'];
        //            socket_log($item['organization_id']);

                    $result_virtuals   = $this->_virtualarchitectureModel->queryForUsersAjax(
                        array(
                            "organizationId"     => $item['organization_id'],
                            "isEnabled"      => 1,
                            "contractStatus" => 1,  //1-已签约;0-未签约
                            "status"         => 1   //0-待审核;1-通过;2-不通过
                        ), 1, 2000);
                    $selector_virtuals = $result_virtuals['data'];

                    $item['enterprise_id'] = $selector_virtuals[0]['id'];

        //            socket_log(json_encode($selector_virtuals,JSON_UNESCAPED_UNICODE));
                }
                break;
            case 2:
                // 企业
                $selector_virtuals = $this->_enterprise_userModel->getAllByWhere(' status=:status ',array(':status' => 1));
                break;
        }



        $item['enterprise_id'] = $selector_virtuals[0]['id'];

        /************ 初始化项目 ***********/

        if ($_W['ispost']) {

            $enterprise_id = intval($_GPC['enterprise_id']);


            if (empty($enterprise_id)) {
                show_json(0, '请选择企业!');
            }

            $params = array(
                'merchid'       => $merchid,
                'enterprise_id' => $enterprise_id
            );

            $this->_plugin_merchService->merchXEnterpriseSaveOrUpdateByColumn($params, $params);


            show_json(1, array('url' => webUrl('merch/enterprise', array('merchid' => $merchid))));
        }

        include $this->template();
    }

    public function delete()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        if (empty($id)) {
//            $id = ((is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0));
            $id = $_GPC['ids'];
        }

//        show_json(0,json_encode($id));

        $this->_plugin_merchService->merchXEnterpriseDelete($id);

        show_json(1);
    }
}