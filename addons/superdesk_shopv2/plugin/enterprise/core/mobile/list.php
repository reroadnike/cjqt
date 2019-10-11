<?php

class List_SuperdeskShopV2Page extends PluginMobilePage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        $category       = $this->model->getCategory(array(
            'isrecommand' => 1,
            'status'      => 1,
            'orderby'     => array('displayorder' => 'desc', 'id' => 'asc')
        ));

        $enterprise_user      = $this->model->getEnterprise(array(
            'isrecommand' => 1,
            'status'      => 1,
            'field'       => 'id,uniacid,enterprise_name,desc,logo,groupid,cateid',
            'orderby'     => array('id' => 'asc')
        ));

        $category_swipe = $this->model->getCategorySwipe(array(
            'status'  => 1,
            'orderby' => array('displayorder' => 'desc', 'id' => 'asc')
        ));

        include $this->template();
    }

    public function category()
    {
        global $_W;
        global $_GPC;
        $data = array();

        if (!empty($_GPC['keyword'])) {
            $data['likecatename'] = $_GPC['keyword'];
        }


        $data     = array_merge($data, array(
            'status'  => 1,
            'orderby' => array('displayorder' => 'desc', 'id' => 'asc')
        ));
        $category = $this->model->getCategory($data);
        include $this->template();
    }

    public function enterprise_user()
    {
        global $_W;
        global $_GPC;
        $data     = array();
        $data     = array_merge($data, array(
            'status'  => 1,
            'orderby' => array('displayorder' => 'desc', 'id' => 'asc')
        ));
        $category = $this->model->getCategory($data);

        foreach ($category as &$value) {
            $value['thumb'] = tomedia($value['thumb']);
        }

        unset($value);
        include $this->template();
    }

    public function ajaxenterprise_user()
    {
        global $_W;
        global $_GPC;
        $data     = array();
        $pindex   = max(1, intval($_GPC['page']));
        $psize    = 30;
        $lat      = floatval($_GPC['lat']);
        $lng      = floatval($_GPC['lng']);
        $sorttype = $_GPC['sorttype'];
        $range    = $_GPC['range'];

        if (empty($range)) {
            $range = 10;
        }


        if (!empty($_GPC['keyword'])) {
            $data['like'] = array('enterprise_name' => $_GPC['keyword']);
        }


        if (!empty($_GPC['cateid'])) {
            $data['cateid'] = $_GPC['cateid'];
        }


        $data = array_merge($data, array('status' => 1, 'field' => 'id,uniacid,enterprise_name,desc,logo,groupid,cateid,address,tel,lng,lat'));

        if (!empty($sorttype)) {
            $data['orderby'] = array('id' => 'desc');
        }


        $enterprise_user = $this->model->getEnterprise($data);

        if (!empty($enterprise_user)) {
            $data      = array();
            $data      = array_merge($data, array(
                'status'  => 1,
                'orderby' => array('displayorder' => 'desc', 'id' => 'asc')
            ));
            $category  = $this->model->getCategory($data);
            $cate_list = array();

            if (!empty($category)) {
                foreach ($category as $k => $v) {
                    $cate_list[$v['id']] = $v;
                }
            }


            foreach ($enterprise_user as $k => $v) {
                if (($lat != 0) && ($lng != 0) && !empty($v['lat']) && !empty($v['lng'])) {
                    $distance = m('util')->GetDistance($lat, $lng, $v['lat'], $v['lng'], 2);

                    if ((0 < $range) && ($range < $distance)) {
                        unset($enterprise_user[$k]);
                        continue;
                    }


                    $enterprise_user[$k]['distance'] = $distance;
                } else {
                    $enterprise_user[$k]['distance'] = 100000;
                }

                $enterprise_user[$k]['catename']  = $cate_list[$v['cateid']]['catename'];
                $enterprise_user[$k]['url']       = mobileUrl('enterprise/map', array('enterprise_id' => $v['id']));
                $enterprise_user[$k]['enterprise_url'] = mobileUrl('enterprise', array('enterprise_id' => $v['id']));
                $enterprise_user[$k]['logo']      = tomedia($v['logo']);
            }
        }


        $total = count($enterprise_user);

        if ($sorttype == 0) {
            $enterprise_user = m('util')->multi_array_sort($enterprise_user, 'distance');
        }


        $start = ($pindex - 1) * $psize;

        if (!empty($enterprise_user)) {
            $enterprise_user = array_slice($enterprise_user, $start, $psize);
        }


        show_json(1, array('list' => $enterprise_user, 'total' => $total, 'pagesize' => $psize));
    }
}