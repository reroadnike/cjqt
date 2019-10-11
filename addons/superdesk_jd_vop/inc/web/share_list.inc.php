<?php

/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 6/10/17
 * Time: 3:33 AM
 *
 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=thethinking_20170610&do=brand
 */

global $_GPC, $_W;
load()->func('tpl');
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'video';

if ($op == 'video_add') {
    global $_W, $_GPC;

    $sql = 'SELECT COUNT(*) FROM ' . tablename($this->tb_thethinking_20170610_video) . ' WHERE `uniacid` = :uniacid ORDER BY `displayorder` ASC ';
    $total = pdo_fetchcolumn($sql, array(':uniacid' => $_W['uniacid']));

    if (!empty($_GPC['id'])) {
        $item = pdo_get($this->tb_thethinking_20170610_video, array('id' => $_GPC['id']));
    } else {
        if ($total >= 60) {
            message('宣传视频最多上传6个！', $this->createWebUrl('brand', array('op' => 'video')), 'success');
        }
    }

    $item = pdo_get($this->tb_thethinking_20170610_video, array('id' => $_GPC['id']));

    if (checksubmit('submit')) {
        load()->func('file');
        load()->func('logging');

        if (empty($_FILES)) {
            message('宣传视频上传出错！', $this->createWebUrl('brand', array('op' => 'video')), 'error');
        } else {
            $files = $_FILES['file_video'];
            $maxSize = 50 * 1024 * 1024;
            if ($files['size'] > $maxSize) {
                message('请选择50M内的视频文件！');
                exit;
            }

            $file_status = file_upload($files, 'file');

            $add_data = array(
                'title'     => $_GPC['title'],
                'name'      => $files['name'],
                'type'      => $files['type'],
                'error'     => $files['error'],
                'size'      => $files['size'],
                'thumbnail' => $_GPC['thumbnail'],
                'uniacid'   => $_W['uniacid']
            );

//            logging_run($files);
//            logging_run($file_status);

            if ($file_status['success']) {

                $ext = pathinfo($files['name'], PATHINFO_EXTENSION);
                $ext = strtolower($ext);

                // TODO 记录生成的文件名对原文件名
                $add_data['ext'] = $ext;
                $add_data['path'] = $file_status['path'];
                $add_data['addtime'] = time();
                if (!empty($_GPC['id'])) {
                    pdo_update($this->tb_thethinking_20170610_video, $add_data, array('id' => $_GPC['id'], 'uniacid' => $_W['uniacid']));
                } else {
                    pdo_insert($this->tb_thethinking_20170610_video, $add_data);
                }
                message('宣传视频上传成功！', $this->createWebUrl('brand', array('op' => 'video')), 'success');

            } else {

                message('宣传视频上传出错！' . json_encode($file_status), $this->createWebUrl('brand', array('op' => 'video')), 'error');
            }

        }

        message('宣传视频上传成功！', $this->createWebUrl('brand', array('op' => 'video')), 'success');
    }
    include $this->template('brand_video_add');

} elseif ($op == 'video') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {
            pdo_update($this->tb_thethinking_20170610_video, array('displayorder' => $displayorder), array('id' => $id, 'uniacid' => $_W['uniacid']));
        }
        message('显示顺序更新成功！', $this->createWebUrl('Brand', array('op' => 'video')), 'success');
    }


    $sql = 'SELECT * FROM ' . tablename($this->tb_thethinking_20170610_video) . '  WHERE uniacid = :uniacid ORDER BY `displayorder` ASC , `id` ASC';
    $list = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));

//            $list = pdo_getall($this->tb_thethinking_20170610_video);
    foreach ($list as $key => $value) {
        $list[$key]['thumbnail'] = tomedia($value['thumbnail']);
        $list[$key]['size'] = round($value['size'] / 1024 / 1024, 3);
    }
    include $this->template('brand_video');
}

