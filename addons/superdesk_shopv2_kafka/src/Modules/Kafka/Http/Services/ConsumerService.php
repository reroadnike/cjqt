<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/13/18
 * Time: 2:11 PM
 */

namespace Modules\Kafka\Http\Services;


/**
 * 目前在微擎这边是没有要处理的 Consumer
 * Class ConsumerService
 *
 * @package Modules\Kafka\Http\Services
 */
class ConsumerService
{

    public function handle($message)
    {

        // 结构详看 /data/wwwroot/default/superdesk_boss/md/数据结构/Kafka-php_message.md
        $messageKey   = $message['message']['key'];
        $messageValue = json_decode($message['message']['value'], true);

//        switch ($messageKey) {
//
//            /****************************************** 超级前台原有的 ******************************************/
//
//            // TbOrganization 原_项目 start
//            case 'addOrUpdateOrg':
//
//                app(\Modules\Kafka\Http\Services\SyncSuperdeskCore\SyncOrganizationIncrememt::class)->replace($messageValue);
//                break;
//            case 'deleteOrg':
//                app(\Modules\Kafka\Http\Services\SyncSuperdeskCore\SyncOrganizationIncrememt::class)->delete($messageValue);
//                break;
//            // TbOrganization end
//
//
//            // 	tb_virtualarchitecture 原_企业信息 start
//            case 'addOrUpdateVirtualArchitecture':
//                app(\Modules\Kafka\Http\Services\SyncSuperdeskCore\SyncVirtualarchitectureIncrememt::class)->replace($messageValue);
//                break;
//            case 'deleteVirtualarchitecture':
//                app(\Modules\Kafka\Http\Services\SyncSuperdeskCore\SyncVirtualarchitectureIncrememt::class)->delete($messageValue);
//                break;
//            // 	tb_virtualarchitecture end
//
//
//            // TbUser start
//            case 'addOrUpdateUser':
//                app(\Modules\Kafka\Http\Services\SyncSuperdeskCore\SyncUserIncrement::class)->replace($messageValue);
//                break;
//            // TbUser end
//
//
//            /****************************************** 企业采购没有的 ******************************************/
//
//            case 'addOrUpdateBuild':
//                break;
//            case 'deleteBuild':
//                break;
//
//
//            // TbCompany start
//            case 'addOrUpdateCompany':
//                break;
//            case 'deleteCompany':
//                break;
//            // TbCompany end
//
//
//            default:
//                break;
//        }
    }
}