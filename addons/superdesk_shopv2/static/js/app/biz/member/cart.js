/**
 * 购物车
 */

define(['core', 'tpl', 'biz/goods/picker', 'biz/plugin/diyform'],
    function(core, tpl, picker, diyform) {

        var modal = {
            status: 'cart'
        };

        modal.init = function() {

            $('.fui-number').numbers({
                callback: function(num, container) {
                    modal.caculate();// 计算合计
                }
            });

            modal.caculate();

            $('.check-item').unbind('click').click(function() {
                var cartid = $(this).closest('.goods-item').data('cartid');
                modal.select(cartid, $(this).prop('checked'))
            });

            $('.checkall').unbind('click').click(function() {
                var checked = $(this).find(':checkbox').prop('checked');
                $(".check-item").prop('checked', checked);
                modal.caculate();
                modal.select('all', checked)
            });

            $('.btn-submit').unbind('click').click(function() {
                if ($(this).attr('stop')) {
                    return;
                }
                $.router.load(core.getUrl('order/create'), true);
            });

            $('.btn-edit').unbind('click').click(function() {
                modal.changeMode();
            });

            $('.btn-delete').unbind('click').click(function() {
                if ($('.edit-item:checked').length <= 0) {
                    return;
                }
                modal.remove();
            });

            $('.btn-favorite').unbind('click').click(function() {
                if ($('.edit-item:checked').length <= 0) {
                    return
                }
                modal.toFavorite();
            });

            $('.editcheckall').unbind('click').click(function() {
                var checked = $(this).find(':checkbox').prop('checked');
                $(".edit-item").prop('checked', checked);
                modal.caculateEdit();
            });

            $('.edit-item').unbind('click').click(function() {
                modal.caculateEdit();
            });

            $('.choose-option').unbind('click').click(function(e) {
                if (modal.status == 'edit') {
                    e.preventDefault();
                    modal.changeOption(this);
                }
            })
        };

        // 编辑 与 完成
        modal.changeMode = function() {
            if ($('.goods-item').length <= 0) {
                $('.btn-edit').remove();
                $('.cartmode').remove();
                $('.editmode').remove();
                $('#container').remove();
                $('.content-empty').show();
                return;
            }
            $('.fui-list-group').each(function(index, item) {
                if ($(item).find('.goods-item').length <= 0) {
                    $(item).prev('.fui-title').remove();
                    $(item).remove()
                }
            });
            if (modal.status == 'cart') {
                $('.edit-item').prop('checked', false);
                $('.editcheckall').prop('checked', false);
                $('.cartmode').hide();
                $('.editmode').show();
                modal.status = 'edit';
                $('.btn-edit').html('完成');
            } else {
                $('.cartmode').show();
                $('.editmode').hide();
                modal.status = 'cart';
                $('.btn-edit').html('编辑');
            }
        };


        modal.select = function(cartid, select) {
            core.json('member/cart/select', {
                    id: cartid,
                    select: select ? "1": '0'
                },
                function(ret) {
                    if (ret.status == 0) {
                        FoxUI.toast.show(ret.result.message)
                    }
                    modal.caculate();
                },
                true, true)
        };

        // 计算合计 与 更新 结算 ( 数量 )
        modal.caculate = function() {

            var total = 0;
            var totalprice = 0;

            $('.goods-item').each(function() {

                // TODO 在这做功夫 就是那些已下架的，没库存的
                if ($(this).find('.check-item').prop('checked')) {

                    total += parseInt($(this).find('.shownum').val());
                    totalprice += parseInt($(this).find('.shownum').val()) * core.getNumber($(this).find('.marketprice').html());

                    var count = parseInt($(this).find('.shownum').val());
                    var cartid = $(this).data('cartid');
                    var optionid = $(this).data('optionid');

                    modal.update(cartid, count, optionid);
                }
            });

            $(".total").html(total);

            window.cartcount = total;
            if (total != 0) {
                $("#menucart span.badge").text(total).show();
            } else {
                $("#menucart span.badge").hide();
            }

            $(".totalprice").html(core.number_format(totalprice, 0));

            if (total <= 0) {
                $(".btn-submit").attr('stop', 1).removeClass('btn-danger').addClass('btn-default disabled')
            } else {
                $(".btn-submit").removeAttr('stop').removeClass('btn-default disabled').addClass('btn-danger')
            }

            $('.checkall .fui-radio').prop('checked', $('.check-item').length == $('.check-item:checked').length);

        };


        // 计算编辑
        // 编辑 :: 显示 | 合计与结算
        // 完成 :: 显示 | 移动到关注与删除
        modal.caculateEdit = function() {
            $('.editcheckall .fui-radio').prop('checked', $('.edit-item').length == $('.edit-item:checked').length);
            var selects = $('.edit-item:checked').length;
            if (selects > 0) {
                $('.btn-delete').removeClass('disabled');
                $('.btn-favorite').removeClass('disabled');
            } else {
                $('.btn-delete').addClass('disabled');
                $('.btn-favorite').addClass('disabled');
            }
        };


        // 在购物车页更新商品数量时调用
        modal.update = function(cartid, num, optionid) {
            core.json('member/cart/update', {
                    id: cartid,
                    total: num,
                    optionid: optionid
                },
                function(ret) {
                    if (ret.status == 0) {
                        FoxUI.toast.show(ret.result.message);
                    }
                },
                true, true);
        };

        // 在购物车页没用到 商品加入购物车后会转到购物车页
        modal.add = function(goodsid, optionid, total, diyformdata, callback) {
            core.json('member/cart/add', {
                    id: goodsid,
                    optionid: optionid,
                    total: total,
                    diyformdata: diyformdata
                },
                function(ret) {
                    if (ret.status == 0) {
                        FoxUI.toast.show(ret.result.message);
                        if (ret.result.url) {
                            setTimeout(function() {
                                    location.href = ret.result.url
                                }, 800);
                        }
                        return;
                    }
                    if (callback) {
                        callback(ret.result);
                    }
                },
                true, true);
        };

        // 编辑 -> 删除
        modal.remove = function() {
            var ids = [];
            $('.edit-item:checked').each(function() {
                var cartid = $(this).closest('.goods-item').data('cartid');
                ids.push(cartid);
            });

            // 如果选中删除的没有，不做操作
            if (ids.length <= 0) {

                // TODO 要做提示吗? 还有没选定删除商品
                return;
            }

            FoxUI.confirm('确认要从购物车删除吗?',
                function() {
                    core.json('member/cart/remove', {
                            ids: ids
                        },
                        function(ret) {
                            if (ret.status == 0) {
                                FoxUI.toast.show(ret.result.message);
                                return
                            }
                            $.each(ids,
                                function() {
                                    $(".goods-item[data-cartid='" + this + "']").remove()
                                });
                            modal.caculate();
                            modal.changeMode();
                        },
                        true, true);
                });
        };

        // 编辑 -> 移动到关注
        modal.toFavorite = function() {

            var ids = [];

            $('.edit-item:checked').each(function() {
                var cartid = $(this).closest('.goods-item').data('cartid');
                ids.push(cartid);
            });

            if (ids.length <= 0) {
                return;
            }

            FoxUI.confirm('确认要从购物车移动到关注吗?',
                function() {
                    core.json('member/cart/tofavorite', {
                            ids: ids
                        },
                        function(ret) {
                            if (ret.status == 0) {
                                FoxUI.toast.show(ret.result.message);
                                return
                            }
                            $.each(ids,
                                function() {
                                    $(".goods-item[data-cartid='" + this + "']").remove()
                                });
                            modal.caculate();
                            modal.changeMode();
                        },
                        true, true)
                })
        };


        modal.changeOption = function(btn) {
            var goodsitem = $(btn).closest('.goods-item');
            var goodsid = goodsitem.data('goodsid'),
                total = parseInt(goodsitem.find('.shownum').val()),
                optionid = goodsitem.data('optionid');
            var cartid = goodsitem.data('cartid');
            picker.open({
                goodsid: goodsid,
                total: total,
                split: '+',
                optionid: optionid,
                showConfirm: true,
                autoClose: false,
                onConfirm: function(total, optionid, optiontitle, optionthumb) {
                    if ($('.diyform-container').length > 0) {
                        var diyformdata = diyform.getData('.diyform-container');
                        if (!diyformdata) {
                            return
                        } else {
                            core.json('order/create/diyform', {
                                    id: goodsid,
                                    diyformdata: diyformdata
                                },
                                function(ret) {
                                    $("#gimg_" + cartid).attr('src', optionthumb);
                                    $(btn).html(optiontitle);
                                    goodsitem.data('optionid', optionid);
                                    goodsitem.find('.fui-number').numbers('refresh', total);
                                    $(".goods-item[data-cartid='" + cartid + "']").find('.cartmode .choose-option').html(optiontitle);
                                    modal.caculate()
                                },
                                true, true);
                            picker.close()
                        }
                    } else {
                        $("#gimg_" + cartid).attr('src', optionthumb);
                        $(btn).html(optiontitle);
                        goodsitem.data('optionid', optionid);
                        goodsitem.find('.fui-number').numbers('refresh', total);
                        $(".goods-item[data-cartid='" + cartid + "']").find('.cartmode .choose-option').html(optiontitle);
                        modal.caculate();
                        picker.close()
                    }
                }
            })
        };
        return modal;
    });