<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Message_SuperdeskShopV2Model
{
    /**
     * 模板消息通知
     * @param        $touser
     * @param        $template_id
     * @param        $postdata
     * @param string $url
     * @param null   $account
     * @param array  $miniprogram
     *
     * @return null
     */
    public function sendTplNotice($touser, $template_id, $postdata, $url = '', $account = NULL, $miniprogram = array())
    {
        if (!$account) {
            $account = m('common')->getAccount();
        }

        if (!$account) {
            return NULL;
        }

        return $account->sendTplNotice($touser, $template_id, $postdata, $url, '#FF683F', $miniprogram);
    }

    /**
     * @param        $openid
     * @param        $msg
     * @param string $url
     * @param null   $account
     *
     * @return null
     */
    public function sendCustomNotice($openid, $msg, $url = '', $account = NULL)
    {
        if (!$account) {
            $account = m('common')->getAccount();
        }

        if (!$account) {
            return NULL;
        }

        $content = '';
        if (is_array($msg)) {
            foreach ($msg as $key => $value) {
                if (!empty($value['title'])) {
                    $content .= $value['title'] . ':' . $value['value'] . "\n";
                } else {
                    $content .= $value['value'] . "\n";
                    if ($key == 0) {
                        $content .= "\n";
                    }
                }
            }
        } else {
            $content = $msg;
        }

        if (!empty($url)) {
            $content .= '<a href=\'' . $url . '\'>点击查看详情</a>';
        }

        return $account->sendCustomNotice(array(
            'touser'  => $openid,
            'msgtype' => 'text',
            'text'    => array('content' => urlencode($content))
        ));
    }

    /**
     * 发送图片
     *
     * @param $openid
     * @param $mediaid
     *
     * @return mixed
     */
    public function sendImage($openid, $mediaid)
    {
        $account = m('common')->getAccount();
        return $account->sendCustomNotice(array(
            'touser'  => $openid,
            'msgtype' => 'image',
            'image'   => array('media_id' => $mediaid)
        ));
    }

    /**
     * @param      $openid
     * @param      $articles
     * @param null $account
     *
     * @return mixed
     */
    public function sendNews($openid, $articles, $account = NULL)
    {
        if (!$account) {
            $account = m('common')->getAccount();
        }
        return $account->sendCustomNotice(array(
            'touser'  => $openid,
            'msgtype' => 'news',
            'news'    => array('articles' => $articles)
        ));
    }


    /**
     * @param        $openid
     * @param        $content
     * @param string $url
     * @param null   $account
     *
     * @return mixed
     */
    public function sendTexts($openid, $content, $url = '', $account = NULL)
    {
        if (!$account) {
            $account = m('common')->getAccount();
        }

        if (!empty($url)) {
            $content .= "\n<a href='" . $url . '\'>点击查看详情</a>';
        }

        return $account->sendCustomNotice(array(
            'touser'  => $openid,
            'msgtype' => 'text',
            'text'    => array('content' => urlencode($content))
        ));
    }
}

