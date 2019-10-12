define(['core', 'tpl'],
    function(core, tpl) {
        var modal = {};
        modal.init = function(params) {
            modal.params = params;
            modal.params.levels = 0;
            modal.params.logis = 0;
            modal.params.service = 0;
            modal.params.describes = 0;
            $('.levels').stars({
                'clearIcon': 'icon icon-round_close',
                'icon': 'icon icon-favor',
                'selectedIcon': 'icon icon-favorfill',
                'onSelected': function(value) {
                    modal.params.levels = value
                }
            });
            $('.logis').stars({
                'clearIcon': 'icon icon-round_close',
                'icon': 'icon icon-favor',
                'selectedIcon': 'icon icon-favorfill',
                'onSelected': function(value) {
                    modal.params.logis = value
                }
            });
            $('.service').stars({
                'clearIcon': 'icon icon-round_close',
                'icon': 'icon icon-favor',
                'selectedIcon': 'icon icon-favorfill',
                'onSelected': function(value) {
                    modal.params.service = value
                }
            });
            $('.describes').stars({
                'clearIcon': 'icon icon-round_close',
                'icon': 'icon icon-favor',
                'selectedIcon': 'icon icon-favorfill',
                'onSelected': function(value) {
                    modal.params.describes = value
                }
            });
            $('.fui-uploader').uploader({
                uploadUrl: core.getUrl('util/uploader'),
                removeUrl: core.getUrl('util/uploader/remove')
            });
            $('.goods-comment-btn').click(function() {
                var $this = $(this),
                    selected = $(this).attr('sel') == '1';
                if (selected) {
                    $this.removeAttr('sel').closest('.goods-list').next().slideUp();
                    $this.find('i')[0].className = "icon icon-fold";
                    $('.goods-list').slideDown();
                    $('.goods-level').slideDown();
                } else {
                    $('.goods-list').slideUp();
                    $('.goods-level').slideUp();
                    $(this).find('.goods-comment-btn').removeAttr('sel');
                    $(this).find('i')[0].className = "icon icon-fold"
                    $this.attr('sel', 1).closest('.goods-list').next().slideDown();
                    $this.find('i')[0].className = "icon icon-unfold"
                }
            });
            $('.btn-submit').click(function() {
                if ($(this).attr('stop')) {
                    return;
                }
                if(modal.params.status==0) {
                    if (modal.params.logis < 1 || modal.params.service < 1 || modal.params.describes < 1) {
                        FoxUI.toast.show('还没有评分');
                        return;
                    }
                    if ($('#comment').isEmpty()) {
                        FoxUI.toast.show('说点什么吧!');
                        return;
                    }
                    var default_images = [];//图片
                    $('#images').find('li').each(function () {
                        default_images.push($(this).data('filename'));
                    });
                    var content = $('.must').next().find('textarea').val();
                    if ($.trim(content) == '') {
                        content = '此用户没有填写评价';
                    }
                    var default_comment = {//默认评价内容
                        'logis': modal.params.logis,
                        'service': modal.params.service,
                        'describes': modal.params.describes,
                        'content': content,
                        'images': default_images
                    };
                    var goods = [];
                    var good_result = false;
                    $('.goods-list').each(function () {
                        var levels = $(this).find('.levels').data('star');
                        if(!levels) {
                            FoxUI.toast.show('商品还未评分');
                            good_result = true;
                            return false;
                        }
                        goods.push({
                            'goodsid': $(this).data('goodsid'),
                            'level': levels
                        });

                    });
                    if(good_result) {
                        return false;
                    }
                    $(this).html('正在处理...').attr('stop', 1);
                    core.json('order/comment/submit', {
                        'orderid': modal.params.orderid,
                        'logis':default_comment.logis,
                        'service':default_comment.service,
                        'describes':default_comment.describes,
                        'content':default_comment.content,
                        'images':default_comment.images,
                        'comments': goods
                    },
                    function(ret) {
                        if (ret.status == 1) {
                            location.href = core.getUrl('order');
                            return;
                        }
                        $('.btn-submit').removeAttr('stop').html('提交评价');
                        FoxUI.toast.show(ret.result.message)
                    },
                    true, true);
                } else {
                    if ($('#append_content').isEmpty()) {
                        FoxUI.toast.show('说点什么吧!');
                        return;
                    }
                    var default_images = [];//图片
                    $('#append_images').find('li').each(function () {
                        default_images.push($(this).data('filename'));
                    });
                    var append_content = $('#append_content').val();
                    if ($.trim(append_content) == '') {
                        content = '此用户没有填写评价';
                    }
                    var default_comment = {
                        'append_content': append_content,
                        'append_images': default_images
                    };
                    $(this).html('正在处理...').attr('stop', 1);
                    core.json('order/comment/submit', {
                            'orderid': modal.params.orderid,
                            'append_content':default_comment.append_content,
                            'append_images':default_comment.append_images
                        },
                        function(ret) {
                            if (ret.status == 1) {
                                location.href = core.getUrl('order');
                                return;
                            }
                            $('.btn-submit').removeAttr('stop').html('提交评价');
                            FoxUI.toast.show(ret.result.message)
                        },
                        true, true);
                }

            });
        };
        return modal;
    });