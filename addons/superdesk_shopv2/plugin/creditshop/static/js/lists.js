define(['core', 'tpl'],
    function(core, tpl) {
        var modal = {
            page: 1,
            cate: 0,
            keyword: ''
        };
        modal.init = function(params) {
            modal.cate = params.cate;
            modal.keyword = params.keyword;
            $('.fui-content').infinite({
                onLoading: function() {
                    modal.getList()
                }
            });
            if (modal.page == 1) {
                modal.getList()
            }
            $("#search").click(function() {
                var kw = $.trim($("#keyword").val());
                var url = core.getUrl('creditshop/lists');
                location.href = url + "&keyword=" + kw + "&cate=" + modal.cate
            });
            $("#keyword").keypress(function(event) {
                if (event.keyCode == 13) {
                    var kw = $.trim($("#keyword").val());
                    var url = core.getUrl('creditshop/lists');
                    location.href = url + "&keyword=" + kw + "&cate=" + modal.cate
                }
            })
        };
        modal.getList = function() {
            core.json('creditshop/lists/getlist', {
                    page: modal.page,
                    cate: modal.cate,
                    keyword: modal.keyword
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
                    core.tpl('.container', 'tpl_list', result, modal.page > 1)
                })
        };
        return modal
    });