define(['core', 'tpl'], function (core, tpl) {
    var modal = {
        page   : 1,
        success: ''
    };
    modal.init = function () {
        $('.fui-content').infinite({
            onLoading: function () {
                modal.getList()
            }
        });
        if (modal.page == 1) {
            modal.getList()
        }
        FoxUI.tab({
            container: $('#tab'),
            handlers : {
                success : function () {
                    modal.changeTab(0)
                },
                success0: function () {
                    modal.changeTab(1)
                },
                success1: function () {
                    modal.changeTab('-1')
                }
            }
        })
    };
    modal.changeTab = function (success) {
        $('.fui-content').infinite('init');
        $('.content-empty').hide(),
            $('.content-loading').show(),
            $('#container').html('');
        modal.page = 1,
            modal.success = success,
            modal.getList()
    };
    modal.loading = function () {
        modal.page++
    };
    modal.getList = function () {
        core.json('groups/team/get_list', {
                page   : modal.page,
                success: modal.success
            },
            function (ret) {
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
                $('.content-loading').hide();
                modal.page++;
                core.tpl('#container', 'tpl_groups_team_list', result, modal.page > 1)
            })
    };
    return modal
});