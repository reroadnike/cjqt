define(['core', 'tpl'], function (core, tpl) {

    var modal = {
        provinceName: '',
        cityName    : '',
        areaName    : '',
        townName    : ''
    };

    modal.initList = function () {
        if (typeof(window.editAddressData) !== 'undefined') {
            var item = $(".address-item[data-addressid='" + window.editAddressData.id + "']");
            if (item.length <= '0') {
                var first = $(".address-item");
                if (first.length > '0') {
                    var html = tpl('tpl_address_item', {
                        address: window.editAddressData
                    });
                    $(first).first().before(html)
                } else {
                    window.editAddressData.isdefault = 1;
                    var html = tpl('tpl_address_item', {
                        address: window.editAddressData
                    });
                    $('.content-empty').hide();
                    $('.fui-content').html(html)
                }
            } else {
                var address = window.editAddressData;
                item.find('.realname').html(address.realname);
                item.find('.mobile').html(address.mobile);
                item.find('.address').html(address.areas.replace(/ /ig, '') + ' ' + address.address)
            }
            delete window.editAddressData;
        }

        $('*[data-toggle=delete]').unbind('click').click(function () {
            var item = $(this).closest('.address-item');
            var id = item.data('addressid');
            FoxUI.confirm('删除后无法恢复, 确认要删除吗 ?',
                function () {
                    core.json('member/address/delete', {
                            id: id
                        },
                        function (ret) {
                            if (ret.status == 1) {
                                if (ret.result.defaultid) {
                                    $("[data-addressid='" + ret.result.defaultid + "']").find(':radio').prop('checked', true)
                                }
                                item.remove();
                                setTimeout(function () {
                                        if ($(".address-item").length <= 0) {
                                            $('.content-empty').show()
                                        }
                                    },
                                    100);
                                return
                            }
                            FoxUI.toast.show(ret.result.message)
                        },
                        true, true)
                })
        });
        $(document).on('click', '[data-toggle=setdefault]',
            function () {
                var item = $(this).closest('.address-item');
                var id = item.data('addressid');
                core.json('member/address/setdefault', {
                        id: id
                    },
                    function (ret) {
                        if (ret.status == 1) {
                            $('.fui-content').prepend(item);
                            FoxUI.toast.show("设置默认地址成功");
                            return
                        }
                        FoxUI.toast.show(ret.result.message)
                    },
                    true, true)
            })
    };
    modal.initPost = function () {

        window.jd_vop_area = {};

        var $province = $('#jd_vop_area').find('select[name="province"]'),
            $city = $('#jd_vop_area').find('select[name="city"]'),
            $area = $('#jd_vop_area').find('select[name="area"]'),
            $town = $('#jd_vop_area').find('select[name="town"]');

        // init 选择器
        // require(['city-picker'], function () {
        // 方法1
        // $('[data-toggle="city-picker"]').citypicker();
        // 方法2
        // $('#areas').citypicker({
        //     url_county : "{php echo murl('entry', array('m'=>'superdesk_jd_vop','do'=>'js_create_city_picker_county'), true);}"
        // });
        // 方法3
        // });

        modal.jd_vop_area_option.onChange = function (info) {

            console.log(JSON.stringify(info));

            window.jd_vop_area = info;

            modal.provinceName = $province.find('option:selected').html() || '';
            modal.cityName = $city.find('option:selected').html() || '';
            modal.areaName = $area.find('option:selected').html() || '';
            modal.townName = $town.find('option:selected').html() || '';
        };

        // init 选择器
        require(['jquery.jd_vop_area_cascade'], function () {
            // 方法3
            $('#jd_vop_area').citys(modal.jd_vop_area_option, function (api) {
                console.log(JSON.stringify(api.getInfo()));

                window.jd_vop_area = api.getInfo();
            });
        });


        $(document).on('click', '#btn-address',
            function () {
                wx.openAddress({
                    success: function (res) {
                        $("#realname").val(res.userName);
                        $('#mobile').val(res.telNumber);
                        $('#address').val(res.detailInfo);
                        $('#areas').val(res.provinceName + " " + res.cityName + " " + res.countryName)
                    }
                });
            });

        $(document).on('click', '#btn-submit',
            function () {


                if ($(this).attr('submit')) {
                    return;
                }

                if ($('#realname').isEmpty()) {
                    FoxUI.toast.show("请填写收件人");
                    return;
                }

                var jingwai = /(境外地区)+/.test($('#areas').val());
                if (jingwai) {
                    if ($('#mobile').isEmpty()) {
                        FoxUI.toast.show("请填写手机号码");
                        return;
                    }
                } else {
                    if (!$('#mobile').isMobile()) {
                        FoxUI.toast.show("请填写正确手机号码");
                        return;
                    }
                }



                if ($province.isEmpty()) {
                    FoxUI.toast.show("请选择所在省份");
                    return;
                }
                if ($city.isEmpty()) {
                    FoxUI.toast.show("请选择所在城市");
                    return;
                }
                if ($area.isEmpty()) {
                    FoxUI.toast.show("请选择所在区县");
                    return;
                }

                if ($('#address').isEmpty()) {
                    FoxUI.toast.show("请填写详细地址");
                    return;
                }

                $('#btn-submit').html('正在处理...').attr('submit', 1);


                window.editAddressData = {
                    realname: $('#realname').val(),
                    mobile  : $('#mobile').val(),
                    areas   : modal.provinceName + ' ' + modal.cityName + ' ' + modal.areaName + ' ' + modal.townName, // 所在 省份 城市 区县 乡镇
                    address : $('#address').val()

                };

                // province 19
                // city 1601
                // district 36953
                // county 50400



                // $('.select-item').each(function (i) {
                //     var that = $(this);
                //     var code = that.data('code');
                //     var count = that.data('count');
                //     window.jd_vop_area[count] = code;
                // });

                console.log(window.editAddressData);
                // return;


                core.json('member/address/submit', {
                        id         : $('#addressid').val(),
                        addressdata: window.editAddressData,
                        jd_vop_area: window.jd_vop_area
                    },
                    function (json) {
                        $('#btn-submit').html('保存地址').removeAttr('submit');
                        window.editAddressData.id = json.result.addressid;
                        if (json.status == 1) {
                            FoxUI.toast.show('保存成功!');
                            history.back()
                        } else {
                            FoxUI.toast.show(json.result.message)
                        }
                    },
                    true, true);
            });
    };
    modal.initSelector = function () {
        if (typeof(window.editAddressData) !== 'undefined') {
            var address = window.editAddressData;
            var item = $(".address-item[data-addressid='" + address.id + "']", $('#page-address-selector'));
            if (item.length > 0) {
                item.find('.realname').html(address.realname);
                item.find('.mobile').html(address.mobile);
                item.find('.address').html(address.areas.replace(/ /ig, '') + ' ' + address.address)
            } else {
                var html = tpl('tpl_address_item', {
                    address: window.editAddressData
                });
                $('.fui-list-group').prepend(html)
            }
            delete window.editAddressData;
        }
        var selectedAddressID = false;

        if (typeof(window.selectedAddressData) !== 'undefined') {
            selectedAddressID = window.selectedAddressData.id;
            delete window.selectedAddressData
        } else if (typeof(window.orderSelectedAddressID) !== 'undefined') {
            selectedAddressID = window.orderSelectedAddressID
        }

        if (selectedAddressID) {
            $(".address-item[data-addressid='" + selectedAddressID + "'] .fui-radio", $('#page-address-selector')).prop('checked', true)
        }

        $('.address-item .fui-list-media,.address-item .fui-list-inner', $('#page-address-selector')).click(function () {
            var $this = $(this).closest('.address-item');
            window.selectedAddressData = {
                'realname': $this.find('.realname').html(),
                'address' : $this.find('.address').html(),
                'mobile'  : $this.find('.mobile').html(),
                'id'      : $this.data('addressid')
            };

            if($('#request_type').val() == 2){
                core.json('member/address/setdefault', {
                        id: $this.data('addressid')
                    },
                    function (res) {
                        window.goodsdetail_need_reload = true;
                    })
            }

            history.back();
        })
    };
    modal.loadSelectorData = function () {
        core.json('member/address/selector/get_list', {},
            function () {

            });
    };
    return modal;
});