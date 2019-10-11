



ALTER TABLE `ims_superdesk_feedback_feedback`
ADD `telphone` VARCHAR(16) NOT NULL DEFAULT '' COMMENT 'telphone' AFTER `headimgurl`,
ADD `issue_type` VARCHAR(32) NOT NULL COMMENT 'issue_type' AFTER `telphone`;