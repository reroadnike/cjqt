<?php

include_once(IA_ROOT . '/addons/superdesk_core/sdk/superdesk_core_sdk.php');

/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 1/30/18
 * Time: 4:51 PM
 */
class UserInfoService
{

    private $_superdeskcoreSdk;

    public function __construct()
    {

        $this->_superdeskcoreSdk = new SuperDeskCoreSDK();
    }

    /**
     * @param $userMobile
     *
     * @return mixed
     * 根据电话号码获取用户openid,获取access_token
     */
    public function getOneByUserMobile($userMobile)
    {
        $user_info    = $this->_superdeskcoreSdk->userInfo($userMobile);
        $access_token = $this->_superdeskcoreSdk->accessToken();

        $user_info    = json_decode($user_info, true);
        $access_token = json_decode($access_token, true);




        $data['open_id']      = $user_info['returnDTO']['openId'];
        $data['access_token'] = $access_token['returnDTO']['accessToken'];
        
        return $data;
    }

    /**
     * @param $core_user
     *
     * @return mixed
     * 根据电话号码获取用户openid,获取access_token 弃置
     * 转用core_user
     */
    public function getOneByCoreUser($core_user)
    {
        $user_info    = $this->_superdeskcoreSdk->userInfo($core_user);
        $access_token = $this->_superdeskcoreSdk->accessToken();

        $user_info    = json_decode($user_info, true);
        $access_token = json_decode($access_token, true);
//        var_dump($user_info);
//        var_dump($access_token);
//        die;




        $data['open_id']      = $user_info['returnDTO']['openId'];
        $data['access_token'] = $access_token['returnDTO']['accessToken'];
//        var_dump($data);
//        die;

        return $data;
    }
}