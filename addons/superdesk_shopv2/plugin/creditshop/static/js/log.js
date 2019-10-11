define(['core', 'tpl'],
    function(core, tpl) {
        var modal = {
            page: 1,
            shine: 0
        };
        modal.init = function(params) {
            modal.shine = params.shine;
            $('.fui-content').infinite({
                onLoading: function() {
                    modal.getList()
                }
            });
            if (modal.page == 1) {
                modal.getList()
            }
        };
        modal.getList = function() {
            core.json('creditshop/log/getlist', {
                    page: modal.page
                },
                function(ret) {
                    var result = ret.result;
                    if (result.total <= 0) {
                        $('.content-empty').show();
                        $('.fui-content').infinite('stop')
                    } else {
                        $('.content-empty').hide();
                        $('.fui-content').infinite('init');
                        if (result.list.length <= 0 || result.list.length < result.pagesize) {
                            $('.fui-content').infinite('stop')
                        }
                    }
                    modal.page++;
                    core.tpl('.container', 'tpl_log_list', result, modal.page > 1)
                })
        };
        return modal
    });