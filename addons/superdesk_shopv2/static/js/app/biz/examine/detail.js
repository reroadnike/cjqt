define(['core', 'tpl'],
    function (core, tpl) {

        var modal = {};

        modal.init = function (params) {

            modal.params = params;

            $('.examine_cancel,.examine_submit').click(function () {

                var btn = $(this);
                var status = $(this).data('status');

                console.log(status);

                if ($(this).attr('stop')) {
                    return;
                }

                var status_msg = status == 1 ? '通过' : '不通过';

                $(this).html('正在处理...').attr('stop', 1);

                FoxUI.confirm('确认要审核为' + status_msg + '?', '提醒',

                    function () {
                        core.json('examine/detail/examineChange', {
                                'orderid': modal.params.orderid,
                                'status' : status
                            },
                            function (ret) {

                                btn.removeAttr('stop').html(status_msg);

                                FoxUI.toast.show(ret.result.message);

                                if (ret.status == 1) {
                                    setTimeout(function () {
                                        location.reload();
                                    }, 5000);
                                }
                            },
                            true, true);
                    },
                    function () {
                        btn.html(status_msg).removeAttr('stop');
                    })
            })
        };
        return modal;
    });