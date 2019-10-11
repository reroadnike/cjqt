define(['core', 'tpl'],
    function (core, tpl) {
        var modal = {
            params: {}
        };
        modal.init = function (params) {

            modal.params.orderid = params.orderid;

            $('.btn-submit').click(function () {
                if ($(this).attr('stop')) {
                    return
                }

                $(this).attr('stop', 1).html('正在处理...');
                core.json('order/cancel/submit', {
                        'id'     : modal.params.orderid,
                        'reason' : $('#reason').val(),
                        'content': $('#content').val()
                    },
                    function (ret) {
                        if (ret.status == 1) {
                            location.href = core.getUrl('order/detail', {
                                id: modal.params.orderid
                            });
                            return
                        }
                        $('.btn-submit').removeAttr('stop').html('确定');
                        FoxUI.toast.show(ret.result.message)
                    },
                    true, true)
            });
        };
        return modal
    });