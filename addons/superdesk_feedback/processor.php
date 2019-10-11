<?php

defined('IN_IA') or exit('Access Denied');

class superdesk_feedbackModuleProcessor extends WeModuleProcessor
{

    public $name = 'superdesk_feedbackModuleProcessor';

    public function isNeedInitContext()
    {
        return 0;
    }

    public function respond()
    {
        global $_W;
        $rid = $this->rule;

        if ($rid) {
            $reply = pdo_fetch("SELECT * FROM " . tablename('superdesk_feedback_reply') . " WHERE rid = :rid", array(':rid' => $rid));
            if ($reply) {
                $sql      = 'SELECT * FROM ' . tablename('superdesk_feedback_activity') . ' WHERE status=1 AND `uniacid`=:uniacid AND `id`=:id';
                $activity = pdo_fetch($sql, array(':uniacid' => $_W['uniacid'], ':id' => $reply['activityid']));
                $news     = array();
                $news[]   = array(
                    'title'       => $activity['title'],
                    'description' => strip_tags($activity['description']),
                    'picurl'      => $_W['attachurl'] . $activity['thumb'],
                    'url'         => $this->createMobileUrl('index', array('id' => $activity['id']))
                );
                return $this->respNews($news);
            }
        }
        return null;
    }

    public function isNeedSaveContext()
    {
        return false;
    }
}
