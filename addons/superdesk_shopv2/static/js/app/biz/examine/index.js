define(['core', 'tpl'],
    function (core, tpl) {
        var modal = {
            page   : 1,
            status : '',
            merchid: 0
        };
        modal.init = function (params) {

            modal.status = params.status;
            modal.merchid = params.merchid;

            $('.fui-content').infinite({
                onLoading: function () {
                    console.log('onLoading');
                    modal.getList();
                }
            });

            if (modal.page == 1) {
                console.log("init modal getList");
                modal.getList();
            }

            $('.icon-delete').click(function () {
                $('.fui-tab-danger a').removeClass('active');
                modal.changeTab(5);
            });

            FoxUI.tab({
                container: $('#tab'),
                handlers : {
                    tab : function () {
                        modal.changeTab('');
                    },
                    tab0: function () {
                        modal.changeTab(0);
                    },
                    tab1: function () {
                        modal.changeTab(1);
                    },
                    tab2: function () {
                        modal.changeTab(2);
                    }
                }
            })
        };

        modal.changeTab = function (status) {

            modal.page = 1;
            modal.status = status;
            modal.getList();

            console.log('modal.changeTab::' + status);

            $('.fui-content').infinite('init');
            $('.content-empty').hide(),
                $('.container').html(''),
                $('.infinite-loading').show();



        };

        modal.loading = function () {
            modal.page++;
            console.log('modal.page='+modal.page);
        };

        modal.getList = function () {

            core.json('examine/get_list', {
                    page   : modal.page,
                    status : modal.status,
                    merchid: modal.merchid
                },
                function (ret) {

                    var result = ret.result;

                    if (result.total <= 0) {

                        $('.content-empty').show();
                        $('.fui-content').infinite('stop');

                    } else {

                        $('.content-empty').hide();
                        $('.fui-content').infinite('init');

                        if (result.list.length <= 0 || result.list.length < result.pagesize) {
                            $('.fui-content').infinite('stop');
                        }
                    }
                    console.log(modal.page > 1);
                    core.tpl('.container', 'tpl_order_index_list', result, modal.page > 1);
                    modal.page++;
                },true);
        };
        return modal;
    });