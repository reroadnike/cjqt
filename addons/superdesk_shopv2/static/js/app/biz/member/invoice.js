define(['core', 'tpl'],
    function (core, tpl) {

        var modal = {};

        modal.initList = function () {

            if (typeof(window.editInvoiceData) !== 'undefined') {
                var item = $(".invoice-item[data-invoiceid='" + window.editInvoiceData.id + "']");
                if (item.length <= '0') {
                    var first = $(".invoice-item");
                    if (first.length > '0') {
                        var html = tpl('tpl_invoice_item', {
                            invoice: window.editInvoiceData
                        });
                        $(first).first().before(html)
                    } else {
                        window.editInvoiceData.isdefault = 1;
                        var html = tpl('tpl_invoice_item', {
                            invoice: window.editInvoiceData
                        });
                        $('.content-empty').hide();
                        $('.fui-content').html(html)
                    }
                } else {
                    var invoice = window.editInvoiceData;
                    item.find('.realname').html(invoice.realname);
                    item.find('.mobile').html(invoice.mobile);
                    item.find('.invoice').html(invoice.areas.replace(/ /ig, '') + ' ' + invoice.invoice)
                }
                delete window.editInvoiceData
            }

            $('*[data-toggle=delete]').unbind('click').click(function () {

                var item = $(this).closest('.invoice-item');
                var id = item.data('invoiceid');

                FoxUI.confirm('删除后无法恢复, 确认要删除吗 ?',

                    function () {
                        core.json('member/invoice/delete', {
                                id: id
                            },
                            function (ret) {
                                if (ret.status == 1) {
                                    if (ret.result.defaultid) {
                                        $("[data-invoiceid='" + ret.result.defaultid + "']").find(':radio').prop('checked', true);
                                    }
                                    item.remove();
                                    setTimeout(function () {
                                            if ($(".invoice-item").length <= 0) {
                                                $('.content-empty').show();
                                            }
                                        },
                                        100);
                                    return;
                                }
                                FoxUI.toast.show(ret.result.message);
                            },
                            true, true);
                    })
            });


            $(document).on('click', '[data-toggle=setdefault]',
                function () {
                    var item = $(this).closest('.invoice-item');
                    var id = item.data('invoiceid');
                    core.json('member/invoice/setdefault', {
                            id: id
                        },
                        function (ret) {
                            if (ret.status == 1) {
                                $('.fui-content').prepend(item);
                                FoxUI.toast.show("设置默认发票成功");
                                return
                            }
                            FoxUI.toast.show(ret.result.message)
                        },
                        true, true)
                })
        };

        modal.invoiceRule = function () {

            // 发票类型 增值普票 1 增值专票 2
            var _invoiceType = $('input:radio[name="invoiceType"]:checked').val();

            // 发票抬头 个人 4 单位 5
            var _selectedInvoiceTitle = $('input:radio[name="selectedInvoiceTitle"]:checked').val();

            console.log('发票类型 cc 增值专票 2');
            console.log(_invoiceType);
            console.log('发票抬头 个人 4 单位 5');
            console.log(_selectedInvoiceTitle);

            if (_invoiceType == 1) { // 增值普票 1

                $('input:radio[name=selectedInvoiceTitle]:nth(0)').attr('disabled', false);

                if (_selectedInvoiceTitle == 4) {

                    modal.initPostRule_1_5(false);
                    modal.initPostRule_2_5(false);

                } else if (_selectedInvoiceTitle == 5) {

                    modal.initPostRule_1_5(true);
                    modal.initPostRule_2_5(false);
                }
            } else if (_invoiceType == 2) {


                $('input:radio[name=selectedInvoiceTitle]:nth(0)').attr('disabled', true);


                if (_selectedInvoiceTitle == 4) {

                    $('input:radio[name=selectedInvoiceTitle]')[0].checked = false;
                    $('input:radio[name=selectedInvoiceTitle]')[1].checked = true;
                    modal.initPostRule_1_5(true);
                    modal.initPostRule_2_5(true);

                } else if (_selectedInvoiceTitle == 5) {

                    modal.initPostRule_1_5(true);
                    modal.initPostRule_2_5(true);
                }

            }

        };

        // 增值普票 1 个人 4
        modal.initPostRule_1_4 = function () {

        };
        // 增值普票 1 单位 5
        modal.initPostRule_1_5 = function (isShow) {
            $('.Rule_1_5').each(function (index, element) {
                var that = this;
                if (isShow) {
                    $(that).show();
                } else {
                    $(that).hide();
                }
            });
        };
        // 增值专票 2 单位 5
        modal.initPostRule_2_5 = function (isShow) {
            $('.Rule_2_5').each(function (index, element) {
                var that = this;
                if (isShow) {
                    $(that).show();
                } else {
                    $(that).hide();
                }
            });
        };

        modal.submitPostRule = function () {


        };

        modal.initPost = function () {

            modal.invoiceRule();

            $(document).on('change', 'input:radio[name="invoiceType"]',
                function () {
                    modal.invoiceRule();
                });
            $(document).on('change', 'input:radio[name="selectedInvoiceTitle"]',
                function () {
                    modal.invoiceRule();
                });

            $(document).on('click', '#btn-submit',

                function () {

                    // check data start

                    if ($(this).attr('submit')) {
                        return;
                    }

                    // 发票类型 增值普票 1 增值专票 2
                    var _invoiceType = $('input:radio[name="invoiceType"]:checked').val();

                    // 发票抬头 个人 4 单位 5
                    var _selectedInvoiceTitle = $('input:radio[name="selectedInvoiceTitle"]:checked').val();

                    console.log('发票类型 cc 增值专票 2');
                    console.log(_invoiceType);
                    console.log('发票抬头 个人 4 单位 5');
                    console.log(_selectedInvoiceTitle);

                    if ($("input[name='companyName']").isEmpty()) {
                        FoxUI.toast.show("请填写发票抬头");
                        return;
                    }

                    if (_invoiceType == 1) { // 增值普票 1

                        if (_selectedInvoiceTitle == 4) {// 个人 4

                        } else if (_selectedInvoiceTitle == 5) { // 单位 5
                            if ($("input[name='taxpayersIDcode']").isEmpty()) {
                                FoxUI.toast.show("请填写纳税人识别号");
                                return;
                            }
                        }

                    } else if (_invoiceType == 2) {

                        if ($("input[name='taxpayersIDcode']").isEmpty()) {
                            FoxUI.toast.show("请填写纳税人识别号");
                            return;
                        }

                        if (_selectedInvoiceTitle == 4) {// 个人 4
                            FoxUI.toast.show("增值专票不为个人开放");
                            return;

                        } else if (_selectedInvoiceTitle == 5) { // 单位 5

                            if ($("input[name='invoiceAddress']").isEmpty()) {
                                FoxUI.toast.show("请填写注册地址");
                                return;
                            }
                            if ($("input[name='invoicePhone']").isEmpty()) {
                                FoxUI.toast.show("请填写注册电话");
                                return;
                            }
                            if ($("input[name='invoiceBank']").isEmpty()) {
                                FoxUI.toast.show("请填写开户银行");
                                return;
                            }
                            if ($("input[name='invoiceAccount']").isEmpty()) {
                                FoxUI.toast.show("请填写银行账号");
                                return;
                            }
                        }
                    }

                    // check data end


                    // 改为改下each方式
                    // window.editInvoiceData = {
                    //     invoiceType         : $("input[name='invoiceType',checked='checked']").val(),
                    //     selectedInvoiceTitle: $("input[name='selectedInvoiceTitle']").val(),
                    //     companyName         : $("input[name='companyName']").val(),
                    //     taxpayersIDcode     : $("input[name='taxpayersIDcode']").val(),
                    //     invoiceContent      : $("input[name='invoiceContent']").val()
                    // };

                    window.editInvoiceData = {};

                    var fields = $('.form-ajax').serializeArray();

                    jQuery.each(fields, function (i, field) {
                        window.editInvoiceData[field.name] = field.value;
                    });

                    console.log(window.editInvoiceData);

                    $('#btn-submit').html('正在处理...').attr('submit', 1);

                    core.json('member/invoice/submit', {
                            id         : $('#invoiceid').val(),
                            invoicedata: window.editInvoiceData
                        },

                        function (json) {

                            $('#btn-submit').html('保存发票').removeAttr('submit');

                            window.editInvoiceData.id = json.result.invoiceid;

                            if (json.status == 1) {

                                FoxUI.toast.show('保存成功!');
                                history.back();

                            } else {

                                FoxUI.toast.show(json.result.message);

                            }
                        },
                        true, true);
                });
        };

        modal.initSelector = function () {

            if (typeof(window.editInvoiceData) !== 'undefined') {

                var invoice = window.editInvoiceData;
                var item = $(".invoice-item[data-invoiceid='" + invoice.id + "']", $('#page-invoice-selector'));

                if (item.length > 0) {

                    item.find('.realname').html(invoice.realname);
                    item.find('.mobile').html(invoice.mobile);
                    item.find('.invoice').html(invoice.areas.replace(/ /ig, '') + ' ' + invoice.invoice);
                } else {

                    var html = tpl('tpl_invoice_item', {
                        invoice: window.editInvoiceData
                    });
                    $('.fui-list-group').prepend(html);
                }
                delete window.editInvoiceData;
            }

            var selectedAddressID = false;

            if (typeof(window.selectedInvoiceData) !== 'undefined') {

                selectedAddressID = window.selectedInvoiceData.id;
                delete window.selectedInvoiceData

            } else if (typeof(window.orderSelectedAddressID) !== 'undefined') {

                selectedAddressID = window.orderSelectedAddressID

            }

            if (selectedAddressID) {

                $(".invoice-item[data-invoiceid='" + selectedAddressID + "'] .fui-radio", $('#page-invoice-selector')).prop('checked', true);

            }

            $('.invoice-item .fui-list-media,.invoice-item .fui-list-inner', $('#page-invoice-selector')).click(function () {

                var $this = $(this).closest('.invoice-item');

                window.selectedInvoiceData = {
                    'companyName'    : $this.find('.companyName').html(),
                    'taxpayersIDcode': $this.find('.taxpayersIDcode').html(),
                    'invoice'        : $this.find('.invoice').html(),
                    'id'             : $this.data('invoiceid')
                };

                console.log(window.selectedInvoiceData);

                history.back();
            });
        };

        modal.loadSelectorData = function () {
            core.json('member/invoice/selector/get_list', {},
                function () {

                });
        };
        return modal;
    });