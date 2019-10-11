define(['core', 'tpl', 'biz/goods/picker'],
    function(core, tpl, picker) {
        var defaults = {
            keywords: '',
            isrecommand: '',
            ishot: '',
            isnew: '',
            isdiscount: '',
            issendfree: '',
            istime: '',
            cate: '',
            order: '',
            by: 'desc',
            merchid: 0
        };
        var modal = {
            page: 1,
            params: {},
            lastcate: false,
            loading : false
        };
        modal.init = function(params) {
            modal.params = $.extend(defaults, params || {});

            if (!modal.toGoods) {
                modal.page = 1;
            } else {
                modal.toGoods = false;
            }

            modal.initScroll()

            // modal.loading = true;
			// const isInit = sessionStorage.getItem('isInit') || 0
			// alert(isInit)
			// if (!isInit){modal.getList();}

			modal.getList()
            $('form').submit(function() {

                console.log('form.submit.loading => '+ modal.loading);

                $('.container').empty();

                modal.params = defaults;
                modal.page = 1;
                modal.params.keywords = $('#search').val();
                modal.getList();

                return false;
            });

            $('#search').on('input propertychange',
                function() {
                    // console.log('search.input propertychange');
                    if ($.trim($(this).val()) == '') {

                        $('.container').empty();
                        $('.sort .item-price').removeClass('desc').removeClass('asc');

                        modal.params = defaults;
                        modal.page = 1;
                        modal.params.keywords = '';
                        modal.getList();
                    }
                });

            $('.sort .item').on('click',
                function () {
                    $('.sort .item.on').removeClass('on'), $(this).addClass('on');
                    var keywords = modal.params.keywords;
                    var order = $(this).data('order') || '';
                    if (order == '') {
                        if (modal.params.order == order) {
                            return;
                        }
                        modal.params = defaults
                    } else if (order == 'price_filter') {
                        /* 20180320修改了价格的逻辑 */
                        modal.showPriceFilter();
                        return;
                    } else if (order == 'sales') {
                        if (modal.params.order == order) {
                            return;
                        }
                        modal.params = defaults,
                            modal.params.order = 'sales',
                            modal.params.by = 'desc'
                    }
                    if (order != 'minprice') {
                        $('.sort .item-price').removeClass('desc').removeClass('asc')
                    }
                    if (order == 'filter') {
                        modal.showFilter();
                        return;
                    }
                    modal.params.keywords = keywords;
                    modal.page = 1;

                    $('.container').html(''),
                        $('.infinite-loading').show(),
                        $(".content-empty").hide();

                    modal.getList();
                });

            $('#listblock').click(function() {
                if ($(this).hasClass('icon-app')) {
                    $(this).removeClass('icon-app').addClass('icon-sort')
                } else {
                    $(this).removeClass('icon-sort').addClass('icon-app')
                }
                $('.container').toggleClass('block');
            });

            //控制滚动 2019627
            // $('.fui-content').infinite({
            // onLoading: function() {
            //modal.getList();
            // }
            // });

            modal.bindEvents();
        };
        modal.showFilter = function() {

            $('.fui-mask-m').show().addClass('visible');
            /* 判断有没有弹出另外一个,有就关掉 */
            if($('.price_screen').hasClass('in')){
                $('.price_screen').removeClass('in');
            }

            $('.screen').addClass('in');

            $('.screen .btn').unbind('click').click(function() {

                var type = $(this).data('type');

                if ($(this).hasClass('btn-danger-o')) {
                    $(this).removeClass('btn-danger-o').addClass('btn-default-o');

                    $(this).find('.zhijiao').hide();
                    $(this).find('.icon').hide();
                } else {
                    $(this).removeClass('btn-default-o').addClass('btn-danger-o');
                    $(this).find('.zhijiao').show();
                    $(this).find('.icon').show();
                }
            });


            $('.screen .cancel').unbind('click').click(function() {
                modal.cancelFilter();
            });


            $('.screen .confirm').unbind('click').click(function() {
                $('.screen .btn').each(function() {
                    var type = $(this).data('type');
                    if ($(this).hasClass('btn-danger-o')) {
                        modal.params[type] = "1";
                    } else {
                        modal.params[type] = "";
                    }
                });

                if (modal.lastcateid) {
                    modal.params.cate = modal.lastcateid;
                }

                modal.closeFilter();

                $('.container').html(''),
                    $('.infinite-loading').show(),
                    $(".content-empty").hide();
                modal.page = 1;
                modal.getList();
            });
            modal.bindCategoryEvents();
            $('.fui-mask-m').unbind('click').click(function() {
                modal.closeFilter();
            });
        };

        modal.cancelFilter = function() {
            modal.closeFilter();
            $('.screen .btn').each(function() {
                if ($(this).hasClass('btn-danger-o')) {
                    $(this).removeClass('btn-danger-o').addClass('btn-default-o');
                    $(this).find('.zhijiao').hide();
                    $(this).find('.icon').hide();
                    modal.params[$(this).data('type')] = "";
                }
            });
            $('.screen .cate .item nav').removeClass('on');
            $('.screen .cate .item:first-child ~ .item').html('');
            defaults.cate = '';
            modal.params = defaults;
            modal.getList();
        };

        modal.closeFilter = function() {
            $('.fui-mask-m').removeClass('visible').transitionEnd(function() {
                $('.fui-mask-m').hide();
            });
            $('.screen').removeClass('in');
        };

        /*
         *  价格筛选功能
         * */
        modal.showPriceFilter = function() {
            $('.fui-mask-m').show().addClass('visible');    //背景黑幕
            if($('.screen').hasClass('in')){
                $('.screen').removeClass('in');
            }
            $('.price_screen').addClass('in');    //筛选模块层展示
            $('.price_screen .btn').unbind('click').click(function() {
                $(this).parent().parent().find('.btn').removeClass('btn-danger-o');
                $(this).addClass('btn-danger-o');
            });


            $('.price_screen .cancel').unbind('click').click(function() {
                modal.cancelPriceFilter();
            });


            $('.price_screen .confirm').unbind('click').click(function() {
                $('.price_screen .btn').each(function() {
                    var type = $(this).data('type');
                    if ($(this).hasClass('btn-danger-o')) {
                        modal.params.order = 'minprice';
                        modal.params.by = type;
                    }
                });

                var priceMin = $('.price_screen #priceMin').val();
                var priceMax = $('.price_screen #priceMax').val();
                if (priceMin) {
                    modal.params.priceMin = priceMin;
                }
                if(priceMax){
                    modal.params.priceMax = priceMax;
                }

                modal.closePriceFilter();

                $('.container').html(''),
                    $('.infinite-loading').show(),
                    $(".content-empty").hide();
                modal.page = 1;
				modal.getList();
            });

            $('.fui-mask-m').unbind('click').click(function() {
                modal.closePriceFilter();
            });
        };

        /**
         * 取消价格筛选功能
         */
        modal.cancelPriceFilter = function() {
            modal.closePriceFilter();
            $('.price_screen .btn').removeClass('btn-danger-o');
            if(modal.params.order == 'price'){
                modal.params.order = '';
                modal.params.by = 'desc';
            }
            $('#minprice,#maxprice').val('');
            modal.params = defaults;
            modal.getList();
        };

        /**
         * 关闭价格筛选功能
         */
        modal.closePriceFilter = function() {
            $('.fui-mask-m').removeClass('visible').transitionEnd(function() {
                $('.fui-mask-m').hide();
            });
            $('.price_screen').removeClass('in');
        };

        modal.bindCategoryEvents = function() {
            $('.screen .cate .item nav').unbind('click').click(function() {
                var catlevel = $(this).closest('.cate').data('catlevel');
                var item = $(this).parent();
                item.find('nav.on').removeClass('on');
                $(this).addClass('on');
                var level = item.data('level');
                modal.lastcateid = $(this).data('id');
                if (level >= catlevel) {
                    return;
                }
                var items = $(".screen .cate .item[data-level='" + level + "'] ~ .item");
                items.html('');
                var next = $(this).closest('.item').next('.item');
                var children = window.category['children'][modal.lastcateid];
                var HTML = "";
                $.each(children,
                    function() {
                        HTML += "<nav data-id='" + this.id + "'>" + this.name + "</nav> "
                    });
                next.html(HTML);
                modal.bindCategoryEvents();
            })
        };

        modal.bindEvents = function() {
            $("#goods-list-container .fui-goods-item").click(function() {
                modal.toGoods = true;
            });
            $('.buy').unbind('click').click(function() {
                var goodsid = $(this).closest('.fui-goods-item').data('goodsid');
                picker.open({
                    goodsid: goodsid,
                    total: 1
                });
            });
        };

        modal.getList = function() {

            //console.log('shareData1',window.shareData)
            //if(window.shareData != undefined) console.log('shareData2',window.shareData.link)
            //console.log('shareData3',core.toQueryString(modal.params));
			//alert('我进来了')
            if(window.shareData != undefined){
                var urlData = modal.params
                urlData.page = 1
                window.shareData.link = core.getUrl('goods',urlData,true)
            }
            // console.log('shareData1',window.shareData)

            if(modal.loading == true){

                FoxUI.toast.show("努力加载中");
                return;
            }

            modal.loading = true;

            modal.params.page = modal.page;

            // FoxUI.loader.show('loading');
            $('.infinite-loading').show();

            core.json('goods/get_list', modal.params,
                function(ret) {
                    $('.infinite-loading').hide();
                    // FoxUI.loader.hide();

                    var result = ret.result;
                    if (result.total <= 0) {
                        $('.content-empty').show();
                        $('.fui-content').infinite('stop');
                    } else {
                        $('.content-empty').hide();
                        $('.fui-content').infinite('init');
                        if (result.list.length <= 0 || result.list.length < result.pagesize) {
                            modal.stopLoading = true;
                            $('.fui-content').infinite('stop');
                        }
                    }

                    // FoxUI.toast.show("processed in "+ret.processing);

                    modal.page++;
                    core.tpl('.container', 'tpl_goods_list', result, modal.page > 1);
                    modal.bindEvents();
                    modal.loading = false;

                });
        };
        modal.refreshalert = function(){   //刷新成功提示
            document.querySelector('.alert-hook').style = 'block';
            setTimeout(()=>{
                document.querySelector('.alert-hook').style = 'none'
            },1000)
        }

        /////
        modal.initScroll = function(){
			console.log('maxScrollY1', sessionStorage.getItem('maxScrollY'))
			let maxScrollY = sessionStorage.getItem('maxScrollY') || 0;
            var scroll = new BScroll(document.querySelector('.wrapper'),{
                probeType:1,   //1 滚动的时候会派发scroll事件，会截流。2滚动的时候实时派发scroll事件，不会截流。 3除了实时派发scroll事件，在swipe的情况下仍然能实时派发scroll事件
                click: true   //是否派发click事件
            })
			// scroll.scrollTo(0, maxScrollY)
			if (Math.abs(maxScrollY)){
				scroll.scrollTo(0, maxScrollY)
			}
            // 滑动过程中事件
            scroll.on('scroll',(pos)=>{
                if(pos.y > 30){
                    document.querySelector('.refresh-hook').innerHTML = '释放立即刷新'
                }
            });
            //滑动结束松开事件
            scroll.on('touchEnd',(pos) =>{  //上拉刷新
				sessionStorage.setItem('maxScrollY', pos.y)
				console.log('maxScrollY2', pos.y)
                if(pos.y > 30){
                    setTimeout(()=>{
                        document.querySelector('.refresh-hook').innerHTML = '下拉刷新'
                        //刷新成功后提示
                        modal.refreshalert();
                        //刷新列表后，重新计算滚动区域高度
                        scroll.refresh();
                        modal.getList();

                    },2000)
                } else if(pos.y<(scroll.maxScrollY - 30)){   //下拉加载
                    document.querySelector('.loading-hook').innerHTML= '加载中。。。';
                    setTimeout(()=>{
                        //恢复文本值
                        document.querySelector('.loading-hook').innerHTML = '加载更多';
                        modal.getList()
                        scroll.refresh();
                    },200)
                }
            })
        }
        return modal;
    });