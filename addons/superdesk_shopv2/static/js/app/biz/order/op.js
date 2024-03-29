define(['core', 'tpl'],
    function(core, tpl) {
        var modal = {};
        modal.init = function(fromDetail) {
            if (typeof fromDetail === undefined) {
                fromDetail = true
            }
            modal.fromDetail = fromDetail;
            $('.order-cancel select').unbind('change').change(function() {
                var orderid = $(this).data('orderid');
                var val = $(this).val();
                $(this).val(-1);
                if (val == '') {
                    return
                }
                var _this = $(this)
                FoxUI.confirm('确认要取消该订单吗?', '提示',
                    function() {
                        modal.cancel(orderid, val, true)
                    },
                    function() {
                        _this.val(-1);
                    })
            });
            $('.order-delete').unbind('click').click(function() {
                var orderid = $(this).data('orderid');
                FoxUI.confirm('确认要删除该订单吗?', '提示',
                    function() {
                        modal.delete(orderid, 1)
                    })
            });
            $('.order-deleted').unbind('click').click(function() {
                var orderid = $(this).data('orderid');
                FoxUI.confirm('确认要彻底删除该订单吗?', '提示',
                    function() {
                        modal.delete(orderid, 2)
                    })
            });
            $('.order-recover').unbind('click').click(function() {
                var orderid = $(this).data('orderid');
                FoxUI.confirm('确认要恢复该订单吗?', '提示',
                    function() {
                        modal.delete(orderid, 0)
                    })
            });
            $('.order-finish').unbind('click').click(function() {
                var orderid = $(this).data('orderid');
                FoxUI.confirm('确认已收到货了吗?', '提示',
                    function() {
                        modal.finish(orderid)
                    })
            });
            $('.order-verify').unbind('click').click(function() {
                var orderid = $(this).data('orderid');
                modal.verify(orderid)
            });
            $('.order-cancel-cancel').unbind('click').click(function() {
                var orderid = $(this).data('orderid');
                FoxUI.confirm('确认要撤回取消申请吗?', '提示',
                    function() {
                        modal.cancelCancel(orderid, 1)
                    })
            });
        };
        modal.cancel = function(id, remark) {
            core.json('order/op/cancel', {
                    id: id,
                    remark: remark
                },
                function(pay_json) {
                    if (pay_json.status == 1) {
                        if (modal.fromDetail) {
                            location.href = core.getUrl('order')
                        } else {
                            $(".order-item[data-orderid='" + id + "']").remove()
                        }
                        return
                    }
                    FoxUI.toast.show(pay_json.result)
                },
                true, true)
        };
        modal.delete = function(id, userdeleted) {
            core.json('order/op/delete', {
                    id: id,
                    userdeleted: userdeleted
                },
                function(pay_json) {
                    if (pay_json.status == 1) {
                        if (modal.fromDetail) {
                            location.href = core.getUrl('order')
                        } else {
                            $(".order-item[data-orderid='" + id + "']").remove()
                        }
                        return
                    }
                    FoxUI.toast.show(pay_json.result)
                },
                true, true)
        };
        modal.finish = function(id) {
            core.json('order/op/finish', {
                    id: id
                },
                function(pay_json) {
                    if (pay_json.status == 1) {
                        location.reload();
                        return
                    }
                    FoxUI.toast.show(pay_json.result)
                },
                true, true)
        };
        modal.verify = function(orderid) {
            container = new FoxUIModal({
                content: $(".order-verify-hidden").html(),
                extraClass: "popup-modal",
                maskClick: function() {
                    container.close()
                }
            });
            container.show();
            $('.verify-pop').find('.close').unbind('click').click(function() {
                container.close()
            });
            core.json('verify/qrcode', {
                    id: orderid
                },
                function(ret) {
                    if (ret.status == 0) {
                        FoxUI.alert('生成出错，请刷新重试!');
                        return
                    }
                    var time = +new Date();
                    $('.verify-pop').find('.qrimg').attr('src', ret.result.url + "?timestamp=" + time).show()
                },
                false, true)
        };
        modal.cancelCancel = function(id) {
            core.json('order/cancel/cancel', {
                    id: id
                },
                function(pay_json) {
                    if (pay_json.status == 1) {
                        location.reload();
                        return
                    }
                    FoxUI.toast.show(pay_json.result)
                },
                true, true)
        };
        return modal
    });