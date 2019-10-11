define(['core', 'tpl', 'biz/plugin/diyform'],
    function (core, tpl, diyform) {
        var modal = {
            params: {
                orderid     : 0,
                goods       : [],
                merchs      : [],
                iscarry     : 0,
                isverify    : 0,
                isvirtual   : 0,
                addressid   : 0,
                invoiceid   : 0,
                couponid    : 0,
                isnodispatch: 0,
                nodispatch  : '',
                packageid   : 0,
                hasinvoice  : 0
            }
        };
        modal.init = function (params) {


            modal.params = $.extend(modal.params, params || {});
            modal.params.couponid = 0;

            $('#coupondiv').find('.fui-cell-label').html('优惠券');
            $('#coupondiv').find('.fui-cell-info').html('');

            var discountprice = core.getNumber($(".discountprice").val());
            var isdiscountprice = core.getNumber($(".isdiscountprice").val());

            if (discountprice > 0) {
                $('.discount').show()
            }
            if (isdiscountprice > 0) {
                $('.isdiscount').show()
            }


            // 收货地址 -> 地址新页面 -> 返回 start
            var loadAddress = false;
            if (typeof(window.selectedAddressData) !== 'undefined') {
                loadAddress = window.selectedAddressData
            } else if (typeof(window.editAddressData) !== 'undefined') {
                loadAddress = window.editAddressData;
                loadAddress.address = loadAddress.areas.replace(/ /ig, '') + ' ' + loadAddress.address
            }
            if (loadAddress) {
                modal.params.addressid = loadAddress.id;
                $('#addressInfo .has-address').show();
                $('#addressInfo .no-address').hide();
                $('#addressInfo .realname').html(loadAddress.realname);
                $('#addressInfo .mobile').html(loadAddress.mobile);
                $('#addressInfo .address').html(loadAddress.address);
                $('#addressInfo a').attr('href', core.getUrl('member/address/selector'));
                $('#addressInfo a').click(function () {
                    window.orderSelectedAddressID = loadAddress.id
                })
            }
            // 收货地址 -> 地址新页面 -> 返回 end


            // 发票 -> 发票新页面 -> 返回 start
            var loadInvoice = false;

            if (typeof(window.selectedInvoiceData) !== 'undefined') {
                loadInvoice = window.selectedInvoiceData
            } else if (typeof(window.editInvoiceData) !== 'undefined') {
                loadInvoice = window.editInvoiceData;
            }

            if (loadInvoice) {

                modal.params.invoiceid = loadInvoice.id;

                $('#invoiceInfo').data('invoiceid', loadInvoice.id);

                $('#invoiceInfo .has-invoice').show();
                $('#invoiceInfo .no-invoice').hide();
                $('#invoiceInfo .companyName').html(loadInvoice.companyName);
                $('#invoiceInfo .taxpayersIDcode').html(loadInvoice.taxpayersIDcode);
                $('#invoiceInfo .invoice').html(loadInvoice.invoice);
                $('#invoiceInfo a').attr('href', core.getUrl('member/invoice/selector'));
                $('#invoiceInfo a').click(function () {
                    window.orderSelectedInvoiceID = loadInvoice.id
                })
            }
            // 发票 -> 发票新页面 -> 返回 end


            // 上门自提门店 -> 门店新页面-> 返回 start
            var loadStore = false;
            if (typeof(window.selectedStoreData) !== 'undefined') {
                loadStore = window.selectedStoreData;
                modal.params.storeid = loadStore.id;
                $('#carrierInfo .storename').html(loadStore.storename);
                $('#carrierInfo .realname').html(loadStore.realname);
                $('#carrierInfo_mobile').html(loadStore.mobile);
                $('#carrierInfo .address').html(loadStore.address)
            }
            // 上门自提门店 -> 门店新页面-> 返回 end


            FoxUI.tab({
                container: $('#carrierTab'),
                handlers : {
                    tab1: function () {
                        modal.params.iscarry = 0;
                        $('#addressInfo').show(),
                            $('#carrierInfo').hide(),
                            $('#memberInfo').hide();
                        modal.caculate()
                    },
                    tab2: function () {
                        modal.params.iscarry = 1;
                        $('#addressInfo').hide(),
                            $('#carrierInfo').show(),
                            $('#memberInfo').show();
                        modal.caculate()
                    }
                }
            });
            var number = $('.fui-number');
            if (number.length > 0) {
                var maxbuy = number.data('maxbuy') || 0,
                    goodsid = number.data('goodsid'),
                    minbuy = number.data('minbuy') || 0,
                    unit = number.data('unit') || '件';
                number.numbers({
                    max     : maxbuy,
                    min     : minbuy,
                    minToast: "{min}" + unit + "起售",
                    maxToast: "最多购买{max}" + unit,
                    callback: function (num) {
                        $.each(modal.params.goods,
                            function () {
                                if (this.goodsid == goodsid) {
                                    this.total = num;
                                    return false
                                }
                            });
                        $('#goodscount').html(num);
                        var marketprice = core.getNumber(number.closest('.goods-item').find('.marketprice').html()) * num;
                        $('.goodsprice').html(core.number_format(marketprice, 2));
                        modal.caculate()
                    }
                })
            }
            $('#deductcredit').click(function () {
                modal.calcCouponPrice()
            });
            $('#deductcredit2').click(function () {
                modal.calcCouponPrice()
            });
            modal.bindCoupon();
            $(document).click(function () {
                $('input,select,textarea').each(function () {
                    $(this).attr('data-value', $(this).val())
                });
                $(':checkbox,:radio').each(function () {
                    $(this).attr('data-checked', $(this).prop('checked'))
                })
            });
            $('input,select,textarea').each(function () {
                var value = $(this).attr('data-value') || '';
                if (value != '') {
                    $(this).val(value)
                }
            });
            $(':checkbox,:radio').each(function () {
                var value = $(this).attr('data-checked') === 'true' ? true : false;
                $(this).prop('checked', value)
            });
            $('.buybtn').click(function () {
                modal.submit(this, params.token)
            });
            modal.caculate();
            $(".fui-cell-giftclick").click(function () {
                modal.giftPicker = new FoxUIModal({
                    content   : $('#gift-picker-modal').html(),
                    extraClass: 'picker-modal',
                    maskClick : function () {
                        modal.giftPicker.close()
                    }
                });
                modal.giftPicker.container.find('.btn-danger').click(function () {
                    modal.giftPicker.close()
                });
                modal.giftPicker.show();
                var giftid = $("#giftid").val();
                $(".gift-item").each(function () {
                    if ($(this).val() == giftid) {
                        $(this).prop("checked", "true")
                    }
                });
                $(".gift-item").on("click",
                    function () {
                        $.ajax({
                            url    : core.getUrl('goods/detail/querygift', {
                                id: $(this).val()
                            }),
                            cache  : true,
                            success: function (data) {
                                data = window.JSON.parse(data);
                                if (data.status > 0) {
                                    $("#giftid").val(data.result.id);
                                    $("#gifttitle").text(data.result.title)
                                }
                            }
                        })
                    })
            })
        };
        modal.giftPicker = function () {
            modal.giftPicker = new FoxUIModal({
                content   : $('#option-picker-modal').html(),
                extraClass: 'picker-modal',
                maskClick : function () {
                    modal.packagePicker.close()
                }
            })
        };
        modal.bindCoupon = function () {
            $('#coupondiv').unbind('click').click(function () {
                core.json('sale/coupon/util/query', {
                        money : 0,
                        type  : 0,
                        merchs: modal.params.merchs,
                        goods : modal.params.goods
                    },
                    function (rjson) {
                        if (rjson.result.coupons.length > 0) {
                            $('#coupondiv').show().find('.badge').html(rjson.result.coupons.length).show();
                            $('#coupondiv').find('.text').hide();
                            require(['biz/sale/coupon/picker'],
                                function (picker) {
                                    picker.show({
                                        couponid  : modal.params.couponid,
                                        coupons   : rjson.result.coupons,
                                        onCancel  : function () {
                                            modal.params.couponid = 0;
                                            modal.params.couponmerchid = 0;
                                            $('#coupondiv').find('.fui-cell-label').html('优惠券');
                                            $('#coupondiv').find('.fui-cell-info').html('');
                                            modal.calcCouponPrice()
                                        },
                                        onSelected: function (data) {
                                            modal.params.couponid = data.id;
                                            modal.params.couponmerchid = data.merchid;
                                            $('#coupondiv').find('.fui-cell-label').html('已选择');
                                            $('#coupondiv').find('.fui-cell-info').html(data.couponname);
                                            $('#coupondiv').data(data);
                                            modal.calcCouponPrice()
                                        }
                                    })
                                })
                        } else {
                            FoxUI.toast.show('未找到优惠券!');
                            modal.hideCoupon();
                        }
                    },
                    false, true);
            })
        };
        modal.hideCoupon = function () {

            $('#coupondiv').hide();
            $('#coupondiv').find('.badge').html('0').hide();
            $('#coupondiv').find('.text').show();

        };
        modal.caculate = function () {
            var goodsprice = core.getNumber($('.goodsprice').html());
            var taskdiscountprice = core.getNumber($(".taskdiscountprice").val());
            var discountprice = core.getNumber($(".discountprice").val());
            var isdiscountprice = core.getNumber($(".isdiscountprice").val());
            var totalprice = goodsprice - taskdiscountprice - discountprice - isdiscountprice;
            if ($('.shownum').length > 0) {
                totalprice = core.getNumber($('.marketprice').html()) * parseInt($('.shownum').val())
            }
            if (modal.params.fromcart == 0) {
                if (modal.params.goods.length == 1) {
                    modal.params.goods[0].total = parseInt($('.shownum').val())
                }
            }
            core.json('order/create/caculate', {
                    totalprice: totalprice,
                    addressid : modal.params.addressid,
                    dispatchid: modal.params.dispatchid,
                    dflag     : modal.params.iscarry,
                    goods     : modal.params.goods
                },
                function (getjson) {
                    if (getjson.status == 1) {
                        if (modal.params.iscarry) {
                            $('.dispatchprice').html('0.00')
                        } else {
                            $('.dispatchprice').html(core.number_format(getjson.result.price, 2))
                        }
                        if (getjson.result.taskdiscountprice) {
                            $('#taskdiscountprice').val(core.number_format(getjson.result.taskdiscountprice, 2))
                        }
                        if (getjson.result.discountprice) {
                            $('#discountprice').val(core.number_format(getjson.result.discountprice, 2))
                        }
                        if (getjson.result.buyagain) {
                            $('#buyagain').val(core.number_format(getjson.result.buyagain, 2));
                            $('#showbuyagainprice').html(core.number_format(getjson.result.buyagain, 2)).parents(".fui-cell").show()
                        }
                        if (getjson.result.isdiscountprice) {
                            $('#isdiscountprice').val(core.number_format(getjson.result.isdiscountprice, 2))
                        }
                        if (getjson.result.deductcredit) {
                            $('#deductcredit_money').html(core.number_format(getjson.result.deductmoney, 2));
                            $('#deductcredit_info').html(getjson.result.deductcredit);
                            $("#deductcredit").data('credit', getjson.result.deductcredit);
                            $("#deductcredit").data('money', core.number_format(getjson.result.deductmoney, 2))
                        }
                        if (getjson.result.deductcredit2) {
                            $('#deductcredit2_money').html(getjson.result.deductcredit2);
                            $("#deductcredit2").data('credit2', getjson.result.deductcredit2)
                        }
                        if (getjson.result.couponcount > 0) {
                            $('#coupondiv').show().find('.badge').html(getjson.result.couponcount).show();
                            $('#coupondiv').find('.text').hide()
                        } else {
                            modal.params.couponid = 0;
                            $('#coupondiv').hide().find('.badge').html(0).hide()
                        }
                        if (getjson.result.merch_deductenough_money > 0) {
                            $('#merch_deductenough').show();
                            $('#merch_deductenough_money').html(core.number_format(getjson.result.merch_deductenough_money, 2));
                            $('#merch_deductenough_enough').html(core.number_format(getjson.result.merch_deductenough_enough, 2))
                        } else {
                            $('#merch_deductenough').hide();
                            $('#merch_deductenough_money').html('0.00');
                            $('#merch_deductenough_enough').html('0.00')
                        }
                        if (getjson.result.deductenough_money > 0) {
                            $('#deductenough').show();
                            $('#deductenough_money').html(core.number_format(getjson.result.deductenough_money, 2));
                            $('#deductenough_enough').html(core.number_format(getjson.result.deductenough_enough, 2))
                        } else {
                            $('#deductenough').hide();
                            $('#deductenough_money').html('0.00');
                            $('#deductenough_enough').html('0.00')
                        }
                        if (getjson.result.merchs) {
                            modal.params.merchs = getjson.result.merchs
                        }
                        if (getjson.result.isnodispatch == 1) {
                            modal.isnodispatch = 1;
                            modal.nodispatch = getjson.result.nodispatch;
                            FoxUI.toast.show(modal.nodispatch)
                        } else {
                            modal.isnodispatch = 0;
                            modal.nodispatch = ''
                        }
                        modal.calcCouponPrice()
                    }else{


                        /** 京东错误处理 start **/

                        /* 下架 处理 */
                        if (getjson.status == 900) {
                            FoxUI.loader.hide();
                            $.each(getjson.result,
                                function () {
                                    if (this.state_msg != '' && this.state_msg != undefined) {
                                        $('.total_' + this.goodsid).html("<font style='color:red'>" + this.state_msg + "</font>");
                                    }
                                }
                            );
                            FoxUI.toast.show("您所在地区存在商品已下架");
                            return;
                        }

                        /* 库存 处理 */
                        if (getjson.status == 901) {
                            FoxUI.loader.hide();
                            $.each(getjson.result,
                                function () {
                                    if (this.stock_msg != '' && this.stock_msg != undefined) {
                                        $('.total_' + this.goodsid).html("<font style='color:red'>" + this.stock_msg + "</font>");
                                    }
                                }
                            );
                            FoxUI.toast.show("您所在地区存在商品库存不足或无货");
                            return;
                        }

                        /* 价格 处理 */
                        if (getjson.status == 902) {
                            FoxUI.loader.hide();
                            $.each(getjson.result,
                                function () {
                                    if (this.price_msg != '' && this.price_msg != undefined) {
                                        $('.price_' + this.goodsid).html("&yen; <span class='marketprice' style='color:red;font-size: .6rem;'>" + this.price_msg + "</span>");
                                    }
                                }
                            );
                            FoxUI.toast.show("您所在地区存在商品价格变动");
                            return;
                        }

                        /** 京东错误处理 end   **/
                    }
                },
                true, true)
        };
        modal.totalPrice = function (couponprice) {
            var goodsprice = core.getNumber($('.goodsprice').html());   //商品金额
            var couponprice = couponprice;  //优惠券金额
            var taskdiscountprice = core.getNumber($(".showtaskdiscountprice").html()); //任务活动优惠
            var discountprice = core.getNumber($(".showdiscountprice").html()); //会员优惠
            var isdiscountprice = core.getNumber($(".showisdiscountprice").html()); //促销优惠
            var buyagainprice = core.getNumber($("#buyagain").val());   //再次购买优惠

            //总金额 = 商品金额 - 任务活动优惠 - 会员优惠 - 促销优惠 - 优惠券金额 - 再次购买优惠
            var totalprice = goodsprice - taskdiscountprice - discountprice - isdiscountprice - couponprice - buyagainprice;

            var dispatchprice = core.getNumber($(".dispatchprice").html()); //运费
            var merch_enoughprice = 0;

            //商户满额立减
            if ($("#merch_deductenough_money").length > 0 && $("#merch_deductenough_money").html() != '') {
                merch_enoughprice = core.getNumber($("#merch_deductenough_money").html())
            }

            //平台满额立减
            var enoughprice = 0;
            if ($("#deductenough_money").length > 0 && $("#deductenough_money").html() != '') {
                enoughprice = core.getNumber($("#deductenough_money").html())
            }

            //总金额 = 总金额 - 商户满额立减 - 平台满额立减 + 运费
            totalprice = totalprice - merch_enoughprice - enoughprice + dispatchprice;

            //积分抵扣 余额抵扣
            var deductprice = 0;
            if ($("#deductcredit").length > 0) {
                if ($("#deductcredit").prop('checked')) {
                    deductprice = core.getNumber($("#deductcredit").data('money'));
                    if ($("#deductcredit2").length > 0) {
                        var td1 = core.getNumber($("#deductcredit2").data('credit2'));
                        if (totalprice - deductprice >= 0) {
                            var td = totalprice - deductprice;
                            if (td > td1) {
                                td = td1
                            }
                            $("#deductcredit2_money").html(core.number_format(td, 2))
                        }
                    }
                } else {
                    if ($("#deductcredit2").length > 0) {
                        var td = core.getNumber($("#deductcredit2").data('credit2'));
                        $("#deductcredit2_money").html(core.number_format(td, 2))
                    }
                }
            }
            var deductprice2 = 0;
            if ($("#deductcredit2").length > 0) {
                if ($("#deductcredit2").prop('checked')) {
                    deductprice2 = core.getNumber($("#deductcredit2_money").html())
                }
            }

            //总金额 = 总金额 - 积分抵扣 - 余额抵扣
            totalprice = totalprice - deductprice - deductprice2;
            if (totalprice <= 0) {
                totalprice = 0
            }
            $('.totalprice').html(core.number_format(totalprice));
            modal.bindCoupon();
            return totalprice
        };
        modal.calcCouponPrice = function () {
            var goodsprice = core.getNumber($('.goodsprice').html());

            $('#coupondeduct_div').hide();
            $('#coupondeduct_text').html('');
            $('#coupondeduct_money').html('0');

            var deductprice = 0;
            //所有的优惠都有一个初始值以及一个可变化的值 如 taskdiscountprice是存储初始值的.不可变化的  showtaskdiscountprice是显示的.可变化的
            var taskdiscountprice = core.getNumber($(".taskdiscountprice").val());
            var discountprice = core.getNumber($(".discountprice").val());
            var isdiscountprice = core.getNumber($(".isdiscountprice").val());

            if (modal.params.couponid == 0) {
                if (taskdiscountprice > 0) {
                    $(".showtaskdiscountprice").html($(".taskdiscountprice").val());
                    $('.istaskdiscount').show()
                } else {
                    $('.istaskdiscount').hide()
                }
                if (discountprice > 0) {
                    $(".showdiscountprice").html($(".discountprice").val());
                    $('.discount').show()
                } else {
                    $('.discount').hide()
                }
                if (isdiscountprice > 0) {
                    $(".showisdiscountprice").html($(".isdiscountprice").val());
                    $('.isdiscount').show()
                } else {
                    $('.isdiscount').hide()
                }
                return modal.totalPrice(0)
            }
            core.json('order/create/getcouponprice', {
                    goods          : modal.params.goods,
                    goodsprice     : goodsprice,
                    couponid       : modal.params.couponid,
                    discountprice  : discountprice,
                    isdiscountprice: isdiscountprice
                },
                function (getjson) {
                    if (getjson.status == 1) {

                        $('#coupondeduct_text').html(getjson.result.coupondeduct_text);

                        deductprice = getjson.result.deductprice;

                        var discountpricenew = getjson.result.discountprice;
                        var isdiscountpricenew = getjson.result.isdiscountprice;

                        if (discountpricenew > 0) {
                            $(".showdiscountprice").html(discountpricenew);
                            $('.discount').show()
                        } else {
                            $(".showdiscountprice").html(0);
                            $('.discount').hide()
                        }
                        if (isdiscountpricenew > 0) {
                            $(".showisdiscountprice").html(isdiscountpricenew);
                            $('.isdiscount').show()
                        } else {
                            $(".showisdiscountprice").html(0);
                            $('.isdiscount').hide()
                        }
                        if (deductprice > 0) {
                            $('#coupondeduct_div').show();
                            $('#coupondeduct_money').html(deductprice)
                            //$('#coupondeduct_money').html(core.number_format(deductprice, 2)) //不知为何这里无法使用.导致出现显示问题 2018年9月10日 12:29:41 zjh
                        }
                    } else {
                        if (discountprice > 0) {
                            $(".showdiscountprice").html($(".discountprice").val());
                            $('.discount').show()
                        } else {
                            $('.discount').hide()
                        }
                        if (isdiscountprice > 0) {
                            $(".showisdiscountprice").html($(".isdiscountprice").val());
                            $('.isdiscount').show()
                        } else {
                            $('.isdiscount').hide()
                        }
                        deductprice = 0
                    }
                    return modal.totalPrice(deductprice)
                },
                true, true)
        };

        // TODO 立即支付
        modal.submit = function (obj, token) {

            var $this = $(obj);


            var giftid = $("#giftid").val();

            if (giftid == '') {
                FoxUI.toast.show('请选择赠品');
                return;
            }

            if (modal.params.mustbind) {
                FoxUI.alert("请先绑定手机", "提示",
                    function () {
                        location.href = core.getUrl('member/bind', {
                            backurl: btoa(location.href)
                        })
                    });
                return
            }

            if ($this.attr('stop')) {
                return;
            }

            if (modal.params.iscarry || modal.params.isverify || modal.params.isvirtual) {
                if (modal.params.iscarry && modal.storeid == 0) {
                    FoxUI.toast.show('请选择自提点');
                    return
                }
                if ($(':input[name=carrier_realname]').isEmpty()) {
                    FoxUI.toast.show('请填写联系人');
                    return
                }
                if ($(':input[name=carrier_mobile]').isEmpty()) {
                    FoxUI.toast.show('请填写联系电话');
                    return
                }
                if (!$(':input[name=carrier_mobile]').isMobile()) {
                    FoxUI.toast.show('联系电话格式有误');
                    return
                }
            } else {
                if (modal.params.addressid == 0) {
                    FoxUI.toast.show('请选择收货地址');
                    return;
                } else {
                    if (modal.isnodispatch == 1) {
                        FoxUI.toast.show(modal.nodispatch);
                        return;
                    }
                }
            }


            if (modal.params.hasinvoice == 1) { // 只有当支持开票时才去验证 1支持 0 不支持
                if (modal.params.invoiceid == 0) {
                    FoxUI.toast.show('请选择发票信息');
                    return;
                }
            }


            var diyformdata = '';

            if (modal.params.orderdiyformid != '0') {
                var diyformdata = diyform.getData('.diyform-container');
                if (!diyformdata) {
                    return;
                }
            }

            if (modal.params.fromcart == 0) {
                if (modal.params.goods.length == 1) {
                    modal.params.goods[0].total = parseInt($('.shownum').val())
                }
            }
            $this.attr('stop', 1);

            var data = {
                'orderid'     : modal.params.orderid,
                'id'          : modal.params.id,
                'goods'       : modal.params.goods,
                'giftid'      : giftid,
                'gdid'        : modal.params.gdid,
                'diydata'     : diyformdata,
                'dispatchtype': modal.params.iscarry ? 1 : 0,
                'fromcart'    : modal.params.fromcart,
                'carrierid'   : modal.params.iscarry ? modal.params.storeid : 0,
                'addressid'   : !modal.params.iscarry ? modal.params.addressid : 0,
                'invoiceid'   : modal.params.invoiceid,
                'hasinvoice'  : modal.params.hasinvoice,// new add
                'carriers'    : (modal.params.iscarry || modal.params.isvirtual || modal.params.isverify) ? {
                    'carrier_realname': $(':input[name=carrier_realname]').val(),
                    'carrier_mobile'  : $(':input[name=carrier_mobile]').val(),
                    'realname'        : $('#carrierInfo .realname').html(),
                    'mobile'          : $('#carrierInfo_mobile').html(),
                    'storename'       : $('#carrierInfo .storename').html(),
                    'address'         : $('#carrierInfo .address').html()
                } : '',
                'remark'      : $("#remark").val(),
                'deduct'      : ($("#deductcredit").length > 0 && $("#deductcredit").prop('checked')) ? 1 : 0,
                'deduct2'     : ($("#deductcredit2").length > 0 && $("#deductcredit2").prop('checked')) ? 1 : 0,
                'couponid'    : modal.params.couponid,
                'submit'      : true,
                'token'       : token,
                'packageid'   : modal.params.packageid
            };


            FoxUI.loader.show('正在创建订单...', 'icon icon-add');


            core.json('order/create/submit', data,

                function (ret) {

                    $this.removeAttr('stop', 1);

                    // error 处理
                    if (ret.status == -1) {
                        FoxUI.loader.hide();
                        FoxUI.toast.show(ret.result.message);
                        location.replace(location.href);
                        return;
                    }


                    if (ret.status == 0) {
                        FoxUI.loader.hide();
                        FoxUI.toast.show(ret.result.message);
                        if (ret.result.url) {
                            setTimeout(function () {
                                    location.href = ret.result.url
                                },
                                800)
                        }
                        return;
                    }

                    /** 京东错误处理 start **/

                    /* 下架 处理 */
                    if (ret.status == 900) {
                        FoxUI.loader.hide();
                        $.each(ret.result,
                            function () {
                                if (this.state_msg != '' && this.state_msg != undefined) {
                                    $('.total_' + this.goodsid).html("<font style='color:red'>" + this.state_msg + "</font>");
                                }
                            }
                        );
                        FoxUI.toast.show("您所在地区存在商品已下架");
                        return;
                    }

                    /* 库存 处理 */
                    if (ret.status == 901) {
                        FoxUI.loader.hide();
                        $.each(ret.result,
                            function () {
                                if (this.stock_msg != '' && this.stock_msg != undefined) {
                                    $('.total_' + this.goodsid).html("<font style='color:red'>" + this.stock_msg + "</font>");
                                }
                            }
                        );
                        FoxUI.toast.show("您所在地区存在商品库存不足或无货");
                        return;
                    }

                    /* 价格 处理 */
                    if (ret.status == 902) {
                        FoxUI.loader.hide();
                        $.each(ret.result,
                            function () {
                                if (this.price_msg != '' && this.price_msg != undefined) {
                                    $('.price_' + this.goodsid).html("&yen; <span class='marketprice' style='color:red;font-size: .6rem;'>" + this.price_msg + "</span>");
                                }
                            }
                        );
                        FoxUI.toast.show("您所在地区存在商品价格变动");
                        return;
                    }

                    /** 京东错误处理 end   **/

                    location.href = core.getUrl('order/pay', {
                        id: ret.result.orderid
                    })
                },
                false, true);
        };
        return modal;
    });