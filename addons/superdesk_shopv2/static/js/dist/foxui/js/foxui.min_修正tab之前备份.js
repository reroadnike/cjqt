/*
 * FoxUI Mobile App Framework v0.1
 *
 * http://foxui.org/
 *
 * Copyright (c) 2016 FOXTEAM
 * Released under the Apache license 2.0
 *
 */

$.supports = (function () {
    return {
        touch: !!(('ontouchstart' in window) || window.DocumentTouch && document instanceof window.DocumentTouch)
    };
})();

$.touchEvents = {
    start: $.supports.touch ? 'touchstart' : 'mousedown',
    move: $.supports.touch ? 'touchmove' : 'mousemove',
    end: $.supports.touch ? 'touchend' : 'mouseup'
};

function _bindCssEvent(events, callback) {
    var dom = this;
    function fireCallBack(e) {

        if (e.target !== this) {
            return;
        }
        callback.call(this, e);
        for (var i = 0; i < events.length; i++) {
            dom.off(events[i], fireCallBack);
        }
    }
    if (callback) {
        for (var i = 0; i < events.length; i++) {
            dom.on(events[i], fireCallBack);
        }
    }
}
$.fn.animationEnd = function (callback) {

    _bindCssEvent.call(this, ['webkitAnimationEnd', 'animationend'], callback);
    return this;
};
$.fn.transitionEnd = function (callback) {
    _bindCssEvent.call(this, ['webkitTransitionEnd', 'transitionend'], callback);
    return this;
};
$.fn.transition = function (duration) {
    if (typeof duration !== 'string') {
        duration = duration + 'ms';
    }
    for (var i = 0; i < this.length; i++) {
        var elStyle = this[i].style;
        elStyle.webkitTransitionDuration = elStyle.MozTransitionDuration = elStyle.transitionDuration = duration;
    }
    return this;
};
$.fn.transform = function (transform) {
    for (var i = 0; i < this.length; i++) {
        var elStyle = this[i].style;
        elStyle.webkitTransform = elStyle.MozTransform = elStyle.transform = transform;
    }
    return this;
};
$.getTranslate = function (el, axis) {
    var matrix, curTransform, curStyle, transformMatrix;
    if (typeof axis === 'undefined') {
        axis = 'x';
    }
    curStyle = window.getComputedStyle(el, null);
    if (window.WebKitCSSMatrix) {
        transformMatrix = new WebKitCSSMatrix(curStyle.webkitTransform === 'none' ? '' : curStyle.webkitTransform);
    } else {
        transformMatrix = curStyle.MozTransform || curStyle.transform || curStyle.getPropertyValue('transform').replace('translate(', 'matrix(1, 0, 0, 1,');
        matrix = transformMatrix.toString().split(',');
    }

    if (axis === 'x') {
        if (window.WebKitCSSMatrix)
            curTransform = transformMatrix.m41;
        else if (matrix.length === 16)
            curTransform = parseFloat(matrix[12]);
        else
            curTransform = parseFloat(matrix[4]);
    }
    if (axis === 'y') {
        if (window.WebKitCSSMatrix)
            curTransform = transformMatrix.m42;
        else if (matrix.length === 16)
            curTransform = parseFloat(matrix[13]);
        else
            curTransform = parseFloat(matrix[5]);
    }

    return curTransform || 0;
};
$.requestAnimationFrame = function (callback) {
    if (requestAnimationFrame) {
        return requestAnimationFrame(callback);
    } else if (webkitRequestAnimationFrame) {
        return webkitRequestAnimationFrame(callback);
    } else if (mozRequestAnimationFrame) {
        return mozRequestAnimationFrame(callback);
    } else {
        return setTimeout(callback, 1000 / 60);
    }
};
$.cancelAnimationFrame = function (id) {
    if (cancelAnimationFrame) {
        return cancelAnimationFrame(id);
    } else if (webkitCancelAnimationFrame) {
        return webkitCancelAnimationFrame(id);
    } else if (mozCancelAnimationFrame) {
        return mozCancelAnimationFrame(id);
    } else {
        return clearTimeout(id);
    }
};
$.compareVersion = function (a, b) {
    var as = a.split('.');
    var bs = b.split('.');
    if (a === b)
        return 0;

    for (var i = 0; i < as.length; i++) {
        var x = parseInt(as[i]);
        if (!bs[i])
            return 1;
        var y = parseInt(bs[i]);
        if (x < y)
            return -1;
        if (x > y)
            return 1;
    }
    return -1;
};
$.device = (function () {
    var device = {};
    var ua = navigator.userAgent;
    var android = ua.match(/(Android);?[\s\/]+([\d.]+)?/);
    var ipad = ua.match(/(iPad).*OS\s([\d_]+)/);
    var ipod = ua.match(/(iPod)(.*OS\s([\d_]+))?/);
    var iphone = !ipad && ua.match(/(iPhone\sOS)\s([\d_]+)/);
    device.ios = device.android = device.iphone = device.ipad = device.androidChrome = false;
    if (android) {
        device.os = 'android';
        device.osVersion = android[2];
        device.android = true;
        device.androidChrome = ua.toLowerCase().indexOf('chrome') >= 0;
    }
    if (ipad || iphone || ipod) {
        device.os = 'ios';
        device.ios = true;
    }
    if (iphone && !ipod) {
        device.osVersion = iphone[2].replace(/_/g, '.');
        device.iphone = true;
    }
    if (ipad) {
        device.osVersion = ipad[2].replace(/_/g, '.');
        device.ipad = true;
    }
    if (ipod) {
        device.osVersion = ipod[3] ? ipod[3].replace(/_/g, '.') : null;
        device.iphone = true;
    }
    if (device.ios && device.osVersion && ua.indexOf('Version/') >= 0) {
        if (device.osVersion.split('.')[0] === '10') {
            device.osVersion = ua.toLowerCase().split('version/')[1].split(' ')[0];
        }
    }
    device.webView = (iphone || ipad || ipod) && ua.match(/.*AppleWebKit(?!.*Safari)/i);

    if (device.os && device.os === 'ios') {
        var osVersionArr = device.osVersion.split('.');
        device.minimalUi = !device.webView &&
            (ipod || iphone) &&
            (osVersionArr[0] * 1 === 7 ? osVersionArr[1] * 1 >= 1 : osVersionArr[0] * 1 > 7) &&
            $('meta[name="viewport"]').length > 0 && $('meta[name="viewport"]').attr('content').indexOf('minimal-ui') >= 0;
    }

    var windowWidth = $(window).width();
    var windowHeight = $(window).height();
    device.statusBar = false;
    if (device.webView && (windowWidth * windowHeight === screen.width * screen.height)) {
        device.statusBar = true;
    } else {
        device.statusBar = false;
    }

    var classNames = [];

    device.pixelRatio = window.devicePixelRatio || 1;
    classNames.push('pixel-ratio-' + Math.floor(device.pixelRatio));
    if (device.pixelRatio >= 2) {
        classNames.push('retina');
    }

    if (device.os) {
        classNames.push(device.os, device.os + '-' + device.osVersion.split('.')[0], device.os + '-' + device.osVersion.replace(/\./g, '-'));
        if (device.os === 'ios') {
            var major = parseInt(device.osVersion.split('.')[0], 10);
            for (var i = major - 1; i >= 6; i--) {
                classNames.push('ios-gt-' + i);
            }
        }

    }
    device.isWeixin = /MicroMessenger/i.test(ua);
    return device;
})();


(function () {
    var FoxUI = {
        defaults: {
            confirmText: "确定",
            cancelText: "取消",
            loaderText: "数据加载中",
            router: true, //默认使用路由
            routerShowLoading: true //路由加载页面 显示loading
        }
    };


    FoxUI.mask = {
        transparent: false,
        show: function (transparent, clickCallback) {
            this.transparent = transparent;
            var mask = $('.fui-mask');
            if (mask.length <= 0) {
                mask = $("<div class='fui-mask " + (transparent ? 'fui-mask-transparent' : '') + "'></div>");
                $(document.body).append(mask);

            }
            mask.show().addClass('visible').click(function () {
                if (clickCallback) {
                    clickCallback();
                }
            });

        },
        hide: function () {
            if (this.transparent) {
                $('.fui-mask').remove();
                return;
            }
            $('.fui-mask').removeClass('visible').transitionEnd(function (e) {
                $('.fui-mask').remove();
            });
        }
    };
    FoxUI.toast = {
        show: function (text, center, duration) {
            if ($('.fui-toast').length > 0) {
                $('.fui-toast').remove();
            }
            var toast = $("<div class='fui-toast'><span class='fui-toast-text'>" + text + "</span></div>");
            $(document.body).append(toast);
            toast.show();
            if (center) {
                toast.css({
                    'top': '50%',
                    'margin-top': -toast.outerHeight() + 'px'
                });
            } else {
                toast.css({
                    'bottom': '3rem'
                });
            }
            toast.addClass('in');
            setTimeout(function () {
                FoxUI.toast.hide();
            }, duration || 2500);
        },
        hide: function () {
            $('.fui-toast').addClass('out').transitionEnd(function (e) {
                $('.fui-toast').remove();
            });
        }
    };
    FoxUI.alert = function (text, title, callback) {

        FoxUI.dialog({
            title: title,
            text: text,
            buttons: [{
                text: FoxUI.defaults.confirmText,
                onclick: callback
            }]
        });
    };

    FoxUI.confirm = function (text, title, confirmCallback, cancelCallback) {

        if (typeof title == 'function') {

            confirmCallback = title;
            title = '提示';
        }
        FoxUI.dialog({
            title: title,
            text: text,
            extraClass: 'fui-dialog-confirm',
            buttons: [{
                text: FoxUI.defaults.cancelText,
                onclick: cancelCallback
            }, {
                text: FoxUI.defaults.confirmText,
                onclick: confirmCallback
            }]
        });
    };

    FoxUI.prompt = function (text, title, placeholder, callback) {

        FoxUI.dialog({
            title: title,
            text: text,
            extraClass: 'fui-dialog-confirm',
            prompt: {
                placeholder: placeholder
            },
            buttons: [{
                text: FoxUI.defaults.confirmText,
                onclick: callback
            }]
        });
    };

    var FoxUIModal = function (params) {

        var self = this;
        var content = params.content || '',
            mask = typeof (params.mask) == 'undefined' ? true : params.mask,
            maskTransparent = typeof (params.maskTransparent) == 'undefined' ? false : params.maskTransparent,
            maskClick = params.maskClick || false,
            extraClass = params.extraClass || "",
            openCallback = params.openCallback || false;


        if (mask) {
            FoxUI.mask.show(maskTransparent, maskClick);
        }

        self.container = $("<div class='fui-modal " + extraClass + "'>" + content + "</div>");
        $(document.body).append(self.container);
        self.container.bind('close', function () {
            self.close();
        });

        self.show = function () {

            if (self.container.hasClass('dialog-modal')) {

                var dialog = self.container.find('.fui-dialog');
                dialog.css({
                    marginTop: -Math.round(dialog.outerHeight() / 2) + 'px'
                });


            }
            setTimeout(function () {
                self.container.addClass("in").transitionEnd(function () {
                    if (openCallback) {
                        openCallback(self);
                    }
                });


            }, 0);
        };
        self.close = function () {
            if (mask) {
                FoxUI.mask.hide();
            }
            self.container.addClass('out').transitionEnd(function () {
                self.container.remove();
            });
        };

        return self;
    };
    $.closeModal = function (modal) {
        modal = $(modal || ".fui-modal.in");
        if (typeof modal !== 'undefined' && modal.length === 0) {
            return;
        }

        modal.each(function () {

            modal.trigger('close');
        });
        return true;
    };
    window.FoxUIModal = FoxUIModal;

    FoxUI.dialog = function (params) {
        params = params || {};

        var titleHTML = params.title ? '<div class="fui-dialog-title">' + params.title + '</div>' : '';
        var textHTML = params.text ? '<div class="fui-dialog-text">' + params.text + '</div>' : '';

        var buttonsHTML = '';
        if (params.buttons && params.buttons.length > 0) {
            buttonsHTML += '<div class="fui-dialog-btns">';
            for (var i = 0; i < params.buttons.length; i++) {
                buttonsHTML += '<a class="' + (params.buttons[i].extraClass ? params.buttons[i].extraClass : '') + '" href="javascript:;">' + params.buttons[i].text + '</a>';
            }
            buttonsHTML += '</div>';
        }

        var promptHTML = '';
        if (params.prompt) {
            promptHTML += '<div class="fui-dialog-prompt"><input type="text" class="fui-dialog-prompt-input" placeholder="' + (params.prompt.placeholder ? params.prompt.placeholder : '') + '" value="' + (params.prompt.value ? params.prompt.value : '') + '"/></div>';
        }

        var dialogHTML = '<div class="fui-dialog  ' + (params.extraClass ? params.extraClass : '') + '">';
        dialogHTML += titleHTML + textHTML + promptHTML + buttonsHTML;
        dialogHTML += '</div>';
        FoxUI.dialog.modal = new FoxUIModal({
            content: dialogHTML,
            extraClass: "dialog-modal"
        });

        FoxUI.dialog.modal.show();

        $('.fui-dialog-btns a', FoxUI.dialog.modal.container).each(function (index, el) {
            $(el).click(function (e) {

                FoxUI.dialog.modal.close();

                if (params.buttons[index].onclick) {
                    if (params.prompt) {
                        params.buttons[index].onclick(FoxUI.dialog.modal.container, FoxUI.dialog.modal.container.find(':input').val(), e);
                        return;
                    }
                    params.buttons[index].onclick(FoxUI.dialog.modal, e);
                }
            });
        });
        return FoxUI.dialog.modal;
    };

    FoxUI.loader = {
        show: function (text, iconClass, duration) {
            FoxUI.mask.show(true);

            var loader = "<div class='fui-loader " + (text != 'mini' ? 'fui-loader-tip' : '') + "'>";
            if (text == 'mini' || text == 'loading') {
                loader += "<span class='fui-preloader fui-preloader-white'></span>";
                if (text == 'loading') {
                    loader += "<span class='fui-loader-text'>" + FoxUI.defaults.loaderText + "</span>";
                }
            } else {

                if (iconClass) {
                    loader += "<span class='fui-loader-icon'><i class='" + iconClass + "'></i></span>";
                }
                loader += "<span class='fui-loader-text'>" + text + "</span>";
            }
            loader += "</div>";
            $(document.body).append(loader);

        },
        hide: function () {
            FoxUI.mask.hide();
            $('.fui-loader').remove();
        }

    };

    FoxUI.notify = {
        show: function (text, title, css, autoClose) {

            if ($('.fui-notify').length > 0) {
                $('.fui-notify').remove();
            }

            if (!css) {
                css = 'default';
            }
            var notifyHTML = "<div class='fui-notify fui-notify-" + css + "'>";
            if (title) {
                notifyHTML += "<span class='fui-notify-title'>" + title + "</span>";
            }
            if (text) {
                notifyHTML += "<span class='fui-notify-text'>" + text + "</span>";
            }
            notifyHTML += "<span class='fui-notify-close'>X</span>";
            notifyHTML += "</div>";

            FoxUI.notify.modal = new FoxUIModal({
                content: notifyHTML,
                extraClass: 'notify-modal',
                mask: false
            });
            FoxUI.notify.modal.show();
            FoxUI.notify.modal.container.find('.fui-notify-close').click(function () {
                FoxUI.notify.modal.close();
            });

            autoClose = autoClose || true;
            if (autoClose) {

                setTimeout(function () {

                    FoxUI.notify.modal.close();

                }, 2000);
            }

        },
        hide: function () {
            FoxUI.notify.modal.close();
        }

    };

    FoxUI.list = {
        init: function () {
            $(".fui-list").each(function () {

                $(this).find('.fui-checkbox,.fui-radio').each(function () {
                    var box = $(this);
                    var list = box.closest('.fui-list');
                    list.unbind('click').click(function () {

                        if (box.hasClass('fui-radio')) {
                            if (!box[0].checked) {
                                box[0].checked = true;
                            }
                            return;
                        }
                        box[0].checked = !box[0].checked;

                    });
                });
            });
        }
    };

    FoxUI.searchbar = {
        init: function () {
            $(document).on("focus", ".searchbar input", function (e) {
                var $input = $(e.target);
                $input.parents(".searchbar").addClass("searchbar-active");
            });
            $(document).on("click", ".searchbar-cancel", function (e) {
                var $btn = $(e.target);
                $btn.parents(".searchbar").removeClass("searchbar-active");
            });
            $(document).on("blur", ".searchbar input", function (e) {
                var $input = $(e.target);
                $input.parents(".searchbar").removeClass("searchbar-active");
            });
        }
    };
    FoxUI.tab = function (params) {

        var self = this;
        self.params = $.extend({}, params || {});

        var container = self.params.container || false;
        if (!container) {
            return;
        }

        self.container = $(container);
        self.tabs = self.container.find("a");
        self.tabs.click(function () {

            var tab = $.trim($(this).data('tab'));
            self.container.find('a.active').removeClass('active');

            $(this).addClass('active');

            if (self.params.handlers) {

                if (self.params.handlers[ tab ] && typeof (self.params.handlers[ tab ]) == 'function') {
                    self.params.handlers[ tab ]();
                }
            }

        });


    };
    FoxUI.message = {
        show: function (params) {

            var titleHTML = '';
            if (params.title) {
                titleHTML = '<div class="title">' + params.title + '</div>';
            }

            var contentHTML = '';
            if (params.content) {
                contentHTML = '<div class="content">' + params.content + '</div>';
            }

            var iconHTML = '';
            if (params.icon) {
                iconHTML = '<div class="icon ' + (params.iconClass ? params.iconClass : '') + '"><i class="' + params.icon + '"></i></div>';
            }

            var buttonsHTML = '';
            if (params.buttons) {
                $.each(params.buttons, function (i, btn) {

                    buttonsHTML += '<a href="javascript:;" class="btn btn-default ' + (btn.extraClass ? btn.extraClass : '') + ' block">' + btn.text + '</a>';
                });
            }
            var messageHTML = "<div class='fui-message fui-message-popup'>" + iconHTML + titleHTML + contentHTML + buttonsHTML + "</div>";

            FoxUI.message.modal = new FoxUIModal({
                content: messageHTML,
                extraClass: 'popup-modal',
                mask: false,
                openCallback: function () {
                    if (params.url) {
                        setTimeout(function () {
                            location.href = params.url;
                            return;
                        }, params.duration || 2000);
                    }
                },
                maskClick: function () {
                    if(!btn.holdModal){
                        FoxUI.message.modal.close();
                    }
                }
            });

            $('.fui-message-popup a', FoxUI.message.modal.container).each(function (index, el) {
                $(el).click(function (e) {
                    if(!params.buttons[index].holdModal) {
                        FoxUI.message.modal.close();
                    }
                    if (params.buttons) {
                        if (params.buttons[index].onclick) {
                            params.buttons[index].onclick(FoxUI.message.modal, e);
                        }
                    }
                });
            });

            FoxUI.message.modal.show();
        },
        confirm: function (title, content, click) {
            FoxUI.message.show({
                title: title,
                content: content,
                buttons: [{
                    text: FoxUI.defaults.confirmText,
                    extraClass: 'btn-success',
                    onclick: click
                }, {
                    text: FoxUI.defaults.cancelText
                }]
            });
        },
        success: function (title, content, urlOrClick) {
            var params = {
                title: title,
                content: content,
                buttons: [{
                    text: FoxUI.defaults.confirmText,
                    extraClass: 'btn-success'
                }]
            };
            if (urlOrClick) {

                if (typeof (urlOrClick) == 'function') {
                    params.buttons[0].onclick = urlOrClick;
                } else {
                    params.url = urlOrClick;
                }
            }
            FoxUI.message.show(params);
        },
        info: function (title, content, urlOrClick) {
            var params = {
                title: title,
                content: content,
                buttons: [{
                    text: FoxUI.defaults.confirmText,
                    extraClass: 'btn-warning'
                }]
            };
            if (urlOrClick) {
                if (typeof (urlOrClick) == 'function') {
                    params.buttons[0].onclick = urlOrClick;
                } else {
                    params.url = urlOrClick;
                }
            }

            FoxUI.message.show(params);
        },
        error: function (title, content, urlOrClick) {
            var params = {
                title: title,
                content: content,
                buttons: [{
                    text: FoxUI.defaults.confirmText,
                    extraClass: 'btn-danger'
                }]
            };
            if (urlOrClick) {
                if (typeof (urlOrClick) == 'function') {
                    params.buttons[0].onclick = urlOrClick;
                } else {
                    params.url = urlOrClick;
                }
            }


            FoxUI.message.show(params);

        },
        hide: function () {

            $('.fui-message-popup').addClass('out').transitionEnd(function (e) {
                $('.fui-message-popup').remove();
            });
            FoxUI.mask.hide();
        }
    };

    jQuery.extend({
        createUploadIframe: function (id, uri)
        {
            //create frame
            var frameId = 'jUploadFrame' + id;
            var iframeHtml = '<iframe id="' + frameId + '" name="' + frameId + '" style="position:absolute; top:-9999px; left:-9999px"';
            if (window.ActiveXObject)
            {
                if (typeof uri == 'boolean') {
                    iframeHtml += ' src="' + 'javascript:false' + '"';

                } else if (typeof uri == 'string') {
                    iframeHtml += ' src="' + uri + '"';

                }
            }
            iframeHtml += ' />';
            jQuery(iframeHtml).appendTo(document.body);

            return jQuery('#' + frameId).get(0);
        },
        createUploadForm: function (id, fileElementId, data)
        {
            //create form
            var formId = 'jUploadForm' + id;
            var fileId = 'jUploadFile' + id;
            var form = jQuery('<form  action="" method="POST" name="' + formId + '" id="' + formId + '" enctype="multipart/form-data"></form>');
            if (data){
                for (var i in data){
                    jQuery('<input type="hidden" name="' + i + '" value="' + data[i] + '" />').appendTo(form);
                }
            }
            var oldElement = jQuery('#' + fileElementId);
            var newElement = jQuery(oldElement).clone(true);
            jQuery(oldElement).attr('id', fileId);
            jQuery(oldElement).before(newElement);
            jQuery(oldElement).appendTo(form);



            //set attributes
            jQuery(form).css('position', 'absolute');
            jQuery(form).css('top', '-1200px');
            jQuery(form).css('left', '-1200px');
            jQuery(form).appendTo('body');
            return form;
        },
        ajaxFileUpload: function (s) {
            // TODO introduce global settings, allowing the client to modify them for all requests, not only timeout
            s = jQuery.extend({}, jQuery.ajaxSettings, s);
            var id = new Date().getTime();
            var form = jQuery.createUploadForm(id, s.fileElementId, (typeof (s.data) == 'undefined' ? false : s.data));
            var io = jQuery.createUploadIframe(id, s.secureuri);
            var frameId = 'jUploadFrame' + id;
            var formId = 'jUploadForm' + id;
            // Watch for a new set of requests
            if (s.global && !jQuery.active++)
            {
                jQuery.event.trigger("ajaxStart");
            }
            var requestDone = false;
            // Create the request object
            var xml = {};
            if (s.global)
                jQuery.event.trigger("ajaxSend", [xml, s]);
            // Wait for a response to come back
            var uploadCallback = function (isTimeout)
            {
                var io = document.getElementById(frameId);
                try
                {
                    if (io.contentWindow)
                    {
                        xml.responseText = io.contentWindow.document.body ? io.contentWindow.document.body.innerHTML : null;
                        xml.responseXML = io.contentWindow.document.XMLDocument ? io.contentWindow.document.XMLDocument : io.contentWindow.document;

                    } else if (io.contentDocument)
                    {
                        xml.responseText = io.contentDocument.document.body ? io.contentDocument.document.body.innerHTML : null;
                        xml.responseXML = io.contentDocument.document.XMLDocument ? io.contentDocument.document.XMLDocument : io.contentDocument.document;
                    }
                } catch (e)
                {
                    jQuery.handleError(s, xml, null, e);
                }
                if (xml || isTimeout == "timeout")
                {
                    requestDone = true;
                    var status;
                    try {
                        status = isTimeout != "timeout" ? "success" : "error";
                        // Make sure that the request was successful or notmodified
                        if (status != "error")
                        {
                            // process the data (runs the xml through httpData regardless of callback)
                            var data = jQuery.uploadHttpData(xml, s.dataType);
                            // If a local callback was specified, fire it and pass it the data
                            if (s.success)
                                s.success(data, status);

                            // Fire the global callback
                            if (s.global)
                                jQuery.event.trigger("ajaxSuccess", [xml, s]);
                        } else
                            jQuery.handleError(s, xml, status);
                    } catch (e)
                    {
                        status = "error";
                        jQuery.handleError(s, xml, status, e);
                    }

                    // The request was completed
                    if (s.global)
                        jQuery.event.trigger("ajaxComplete", [xml, s]);

                    // Handle the global AJAX counter
                    if (s.global && !--jQuery.active)
                        jQuery.event.trigger("ajaxStop");

                    // Process result
                    if (s.complete)
                        s.complete(xml, status);

                    jQuery(io).unbind();

                    setTimeout(function ()
                    {
                        try
                        {
                            jQuery(io).remove();
                            jQuery(form).remove();

                        } catch (e)
                        {
                            jQuery.handleError(s, xml, null, e);
                        }

                    }, 100);

                    xml = null;

                }
            };
            // Timeout checker
            if (s.timeout > 0)
            {
                setTimeout(function () {
                    // Check to see if the request is still happening
                    if (!requestDone)
                        uploadCallback("timeout");
                }, s.timeout);
            }
            try
            {

                var form = jQuery('#' + formId);
                jQuery(form).attr('action', s.url);
                jQuery(form).attr('method', 'POST');
                jQuery(form).attr('target', frameId);
                if (form.encoding)
                {
                    jQuery(form).attr('encoding', 'multipart/form-data');
                } else
                {
                    jQuery(form).attr('enctype', 'multipart/form-data');
                }
                jQuery(form).submit();

            } catch (e)
            {
                jQuery.handleError(s, xml, null, e);
            }

            jQuery('#' + frameId).load(uploadCallback);
            return {abort: function () {
            }};

        },
        uploadHttpData: function (r, type) {
            var data = !type;
            data = type == "xml" || data ? r.responseXML : r.responseText;
            // If the type is "script", eval it in global context
            if (type == "script")
                jQuery.globalEval(data);
            // Get the JavaScript object, if JSON is used.
            if (type == "json")
                eval("data = " + data);
            // evaluate scripts within html
            if (type == "html")
                jQuery("<div>").html(data).evalScripts();

            return data;
        }
    });


    var FoxUIUploader = function (element, params) {
        var self = this;
        var defaults = {
            max: 1,
            uploadUrl: '',
            removeUrl: '',
            remove: true,
            long: 0,
            removeIcon: "",
            count: 0,
            name: '',
            imageCss: 'image-sm'
        };
        self.uploader = $(element);
        self.container = self.uploader.prev('.fui-images');
        self.params = $.extend(defaults, params || {});
        self.params.max = self.uploader.data('max') || self.params.max;
        self.params.count = self.uploader.data('count') || self.params.count;
        self.params.name = self.uploader.data('name') || self.params.name;
        self.params.long = self.uploader.data('long') || 0;

        if (!self.params.uploadUrl) {
            console.warn('FoxUI: UploadUrl not defined.');
            return;
        }
        self.file = self.uploader.find('input[type=file]');
        var fileid = self.file.attr('id');
        self.removeHandler = function () {
            $('.image-remove', self.container).unbind('click').click(function () {

                FoxUI.loader.show('mini');
                var item = $(this).closest('li');
                $.ajax({
                    url: self.params.removeUrl,
                    type: 'post',
                    dataType: 'json',
                    data: {filename: $(this).data('filename')},
                    cache: false,
                    success: function (ret) {
                        FoxUI.loader.hide();
                        if (ret.status == 'success') {

                            item.remove();
                            self.params.count--;
                            if (self.params.count < self.params.max) {
                                self.uploader.show();
                            }
                        } else {
                            FoxUI.toast.show(ret.message);
                        }

                    }
                });
            });
        };
        self.file.change(function () {

            if(self.params.max>0){
                var filecount = self.params.count + this.files.length;
                if(filecount>self.params.max){
                    FoxUI.toast.show('最多上传 ' + self.params.max  + ' 张图片!');
                    return;
                }
            }

            FoxUI.loader.show('mini');
            $.ajaxFileUpload({
                url: self.params.uploadUrl,
                data: {file: fileid},
                secureuri: false,
                fileElementId: fileid,
                dataType: 'json',
                success: function (res, status) {
                    FoxUI.loader.hide();
                    if (res.status != 'success') {
                        FoxUI.toast.show(res.message);
                        return;
                    }
                    if(res.files){

                        self.params.count+=res.files.length;
                        if (self.params.count >= self.params.max) {
                            self.uploader.hide();
                        }

                        $.each(res.files,function(i){
                            file = res.files[i];
                            if(file.status!='success'){
                                FoxUI.toast.show(file.message);
                            }
                            else{

                                if (self.container.length > 0) {
                                    var removeHTML = "";
                                    if (self.params.remove) {
                                        removeHTML += '<span class="image-remove">';
                                        if (self.params.removeIcon) {
                                            removeHTML += "<i class='" + self.params.removeIcon + "'></i>";
                                        } else {
                                            removeHTML += "X";
                                        }
                                        removeHTML += '</span>';
                                    }
                                    var liClass = "image " + self.params.imageCss + " " + (self.params.long == '1' ? 'long' : '');
                                    var imageHTML = '<li style="background-image:url(\'' + file.url + '\')" class="' + liClass + '" data-filename="' + file.filename + '">' + removeHTML + '<input type="hidden" name="' + self.params.name + '" value="' + file.filename + '" /></li>';
                                    self.container.html(self.container.html() + imageHTML);
                                    self.removeHandler();
                                }
                            }
                        });

                        var images = [];
                        $('.image', self.container).each(function () {
                            images.push($(this).data('filename'));
                        });
                        if (self.params.onUpload) {
                            self.params.onUpload(res, images);
                        }

                    } else{
                        self.params.count++;
                        if (self.params.count >= self.params.max) {
                            self.uploader.hide();
                        }

                        if (self.container.length > 0) {
                            var removeHTML = "";
                            if (self.params.remove) {
                                removeHTML += '<span class="image-remove">';
                                if (self.params.removeIcon) {
                                    removeHTML += "<i class='" + self.params.removeIcon + "'></i>";
                                } else {
                                    removeHTML += "X";
                                }
                                removeHTML += '</span>';
                            }
                            var liClass = "image " + self.params.imageCss + " " + (self.params.long == '1' ? 'long' : '');
                            var imageHTML = '<li style="background-image:url(\'' + res.url + '\')" class="' + liClass + '" data-filename="' + res.filename + '">' + removeHTML + '<input type="hidden" name="' + self.params.name + '" value="' + res.filename + '" /></li>';
                            self.container.html(self.container.html() + imageHTML);
                            self.removeHandler();

                        }

                        var images = [];
                        $('.image', self.container).each(function () {
                            images.push($(this).data('filename'));
                        });
                        if (self.params.onUpload) {
                            self.params.onUpload(res, images);
                        }
                    }

                }, error: function (data, status, e) {
                    FoxUI.loader.hide();
                    FoxUI.toast.show('上传失败');
                }
            });

        });

        if (self.container.length > 0) {

            self.removeHandler();
        }

    };
    $.fn.uploader = function (params) {
        var args = arguments;
        return this.each(function () {
            if (!this)
                return;
            var $this = $(this);
            var uploader = $this.data("uploader");
            if (!uploader) {
                uploader = new FoxUIUploader(this, params || {});
                $this.data("uploader", uploader);
            }
            if (typeof params === typeof "a") {
                uploader[params].apply(uploader, Array.prototype.slice.call(args, 1));
            }
        });
    };
    var FoxUINumbers = function (element, params) {

        var defaults = {
            value: 1,
            max: 0,
            min: 0,
            minToast: "",
            maxToast: "",
            callback: function () {
            }
        };

        var self = this;
        self.container = $(element);

        self.params = $.extend(defaults, params || {});
        self.params.value = self.container.data('value') || self.params.value;
        self.params.max = self.container.data('max') || self.params.max;
        self.params.min = self.container.data('min') || self.params.min;
        self.params.minToast = self.container.data('mintoast') || self.params.minToast;
        self.params.maxToast = self.container.data('maxtoast') || self.params.maxToast;

        if (self.params.value <= 0) {
            self.params.value = 1;
        }

        self.$minus = $('.minus', self.container);
        self.$plus = $('.plus', self.container);
        self.$num = $('.num', self.container);

        self.$minus.removeClass('disabled'), self.$plus.removeClass('disabled');
        self.$num.val(self.params.value);

        self.checkNumber = function (num) {
            self.$minus.removeClass('disabled'), self.$plus.removeClass('disabled');

            if (num < 1) {
                num = 1;
            }
            if (self.params.min > 0 && num <= self.params.min) {

                if (self.params.minToast && num <= self.params.min) {
                    FoxUI.toast.show(self.params.minToast.replace("{min}", self.params.min));
                }
                num = self.params.min;
                self.$minus.addClass('disabled');
            }
            if (num <= 1) {
                self.$minus.addClass('disabled');
            }
            if (self.params.max > 0 && num >= self.params.max) {
                if (self.params.maxToast && num >= self.params.max) {
                    FoxUI.toast.show(self.params.maxToast.replace("{max}", self.params.max));
                }
                num = self.params.max;
                self.$plus.addClass('disabled');
            }
            self.$num.val(num);
            return num;
        };

        if (self.params.min > 0) {

            if (self.params.value < self.params.min) {
                self.$num.val(self.params.min);
                self.$minus.addClass('disabled');
            }
        }

        if (self.params.max > 0) {

            if (self.params.value > self.params.max) {
                self.$num.val(self.params.max);
                self.$plus.addClass('disabled');
            }
        }

        self.$num.click(function (e) {
            e.preventDefault();
        });
        self.$minus.click(function (e) {
            e.preventDefault();
            if (self.$minus.hasClass('disabled')) {
                return;
            }
            var num = self.checkNumber(parseInt(self.$num.val()) - 1);
            if (self.params.callback) {
                self.params.callback(num, self.container);
            }
        });

        self.$plus.click(function (e) {
            e.preventDefault();
            if (self.$plus.hasClass('disabled')) {
                return;
            }
            var num = self.checkNumber(parseInt(self.$num.val()) + 1);
            if (self.params.callback) {
                self.params.callback(num, self.container);
            }

        });
        self.$num.blur(function () {
            var num = self.checkNumber(parseInt(self.$num.val()));
            if (self.params.callback) {
                self.params.callback(num, self.container);
            }
        });
        self.refresh = function (num) {
            self.checkNumber(num);
        };
        self.checkNumber(self.params.value);

    };
    $.fn.numbers = function (params) {
        var args = arguments;
        return this.each(function () {
            if (!this)
                return;
            var $this = $(this);
            var numbers = $this.data("numbers");
            if (!numbers) {
                numbers = new FoxUINumbers(this, params || {});
                $this.data("numbers", numbers);
            } else {
                if (typeof (params.value) !== undefined) {
                    numbers.checkNumber(params.value);
                }
            }
            if (typeof params === typeof "a") {
                numbers[params].apply(numbers, Array.prototype.slice.call(args, 1));
            }

        });
    };

    var FoxUIActionSheet = function (params) {
        var self = this;
        var cancelButton = {
            text: FoxUI.defaults.cancelText,
            extraClass: 'cancel'
        };
        var defaults = {
            title: '',
            buttons: [],
            round: false,
            clickClose: true
        };

        self.params = $.extend(defaults, params || {});
        self.params.buttons.push(cancelButton);
        self.modal = false;
        self.show = function () {

            var buttonsHTML = '';
            $.each(self.params.buttons, function (i, btn) {
                buttonsHTML += '<a href="' + (btn.url ? btn.url : 'javascript:;') + '" class="' + (btn.extraClass ? btn.extraClass : '') + '">' + btn.text + '</a>';
            });
            var titleHTML = "";
            if (self.params.title) {
                titleHTML = '<div class="fui-actionsheet-title">' + self.params.title + "</div>";
            }
            var actionSheetHTML = "<div class='fui-actionsheet " + (self.params.round ? 'fui-actionsheet-o' : '') + "'>" + titleHTML + buttonsHTML + "</div>";
            self.modal = new FoxUIModal({
                content: actionSheetHTML,
                extraClass: 'picker-modal',
                maskClick: function () {

                    self.close();
                }
            });
            self.modal.show();

            $('.fui-actionsheet a', self.modal.container).each(function (index, el) {
                $(el).click(function (e) {

                    if (self.params.clickClose) {
                        self.close();
                    }

                    if (self.params.buttons[index].onclick) {
                        self.params.buttons[index].onclick(self.modal, e);
                    }
                });
            });
        };
        self.close = function () {
            self.modal.close();
        };
    };


    FoxUI.actionsheet = {
        actionsheet: null,
        show: function (title, buttons, round, clickClose) {
            var actionsheet = new FoxUIActionSheet({
                title: title,
                buttons: buttons,
                round: round,
                clickClose: clickClose
            });
            this.actionsheet = actionsheet;
            actionsheet.show();
            return this.actionsheet;
        },
        hide: function () {
            this.actionsheet.close();
            this.actionsheet = null;
        }
    };
    FoxUI.according = {
        init: function () {
            $('.fui-according').each(function () {
                var self = $(this);
                var header = self.find('.fui-according-header');
                var group = self.closest('.fui-according-group');
                header.unbind("click").click(function () {
                    group.find('.fui-according').not(self).each(function () {
                        FoxUI.according.collapse($(this).closest('.fui-according'));
                    });
                    if (self.hasClass('expanded')) {
                        FoxUI.according.collapse(self);
                    } else {
                        FoxUI.according.expand(self);
                    }

                });
            });
        },
        expand: function (according) {
            var content = according.find('.fui-according-content');
            content.css('height', content[0].scrollHeight + 'px');
            according.addClass('expanded');
        },
        collapse: function (according) {
            according.find('.fui-according-content').css('height', '');
            according.removeClass('expanded');
        }

    };

    var FoxUISwipe = function (swipe, params) {
        var defaults = {
            transition: 300,
            speed: 3000,
            gap: 0,
            touch: true,
            placeholder: "data:image/gif;base64,R0lGODlhAQABAIAAAOXl5QAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw=="
        };
        var self = this;
        swipe = $(swipe);
        self.params = $.extend({}, defaults, params || {});
        self.wrapper = swipe.find('.fui-swipe-wrapper');
        self.items = self.wrapper.find('.fui-swipe-item');
        self.page = swipe.find('.fui-swipe-page');
        self.bullets = self.page.find('.fui-swipe-bullet');
        self.buttons = swipe.find('.fui-swipe-button');
        self.params.speed = swipe.data('speed') || self.params.speed;
        self.params.transition = swipe.data('transition') || self.params.transition;
        self.params.gap = swipe.data('gap') || self.params.gap;
        self.params.touch = swipe.data('touch') || self.params.touch;
        if (self.items.length < 2) {
            return;
        }
        var allowItemClick = true;


        self.interval = 0;
        self.width = swipe.width(); //anjey

        self.setBullet = function () {

            var bullet = $(self.bullets[self.activeIndex]);
            if (bullet.length > 0) {
                self.page.find('.active').removeClass('active');
                bullet.addClass('active');
            }

        };
        self.slide = function (activeIndex, transition) {

            self.items.each(function (index, item) {
                if (index == activeIndex)
                {
                    var data_lazy = $(this).children('img').get(0).getAttribute('data-lazy');
                    if (data_lazy !== null)
                    {
                        $(this).children('img').get(0).setAttribute('src', data_lazy);
                        $(this).children('img').get(0).setAttribute('data-lazyloaded', 'true');
                        $(this).children('img').get(0).removeAttribute('data-lazy');
                    }
                }
            });
            self.activeIndex = activeIndex;
            self.setBullet();
            if (self.params.onStart) {
                self.params.onStart(self);
            }
            transition = transition || self.params.transition;

            var num = -(activeIndex * self.width);

            if (self.params.gap) {

                num -= self.params.gap * (activeIndex - 1) + self.params.gap;

            }
            var transform = 'translate3d(' + num + 'px,0,0)';

            self.wrapper.transition(transition).transform(transform).transitionEnd(function (e) {

                if (self.params.speed) {
                    self.begin();
                }
                allowItemClick = true;
                if (self.params.onChange) {
                    self.params.onChange(self);
                }
            });

        };
        self.prev = function () {

            clearTimeout(self.interval);

            self.activeIndex--;
            if (self.activeIndex < 0) {
                self.activeIndex = 0;
            }
            self.slide(self.activeIndex);

        };
        self.next = function (delay) {

            clearTimeout(this.interval);
            self.activeIndex++;
            if (self.activeIndex > self.items.length - 1) {
                self.activeIndex = 0;
            }
            self.slide(self.activeIndex);

        };

        self.begin = function () {
            if (self.params.speed) {

                self.interval = setTimeout(function () {
                    self.next();
                }, self.params.speed);
            }
        };
        var isTouched, isMoved;
        var onTouchStart = function (e) {
            if (isTouched) {
                return;
            }


            self.start = {
                pageX: e.type === 'touchstart' ? e.originalEvent.targetTouches[0].pageX : e.pageX,
                pageY: e.type === 'touchstart' ? e.originalEvent.targetTouches[0].pageY : e.pageY,
                time: Number(new Date())
            };

            self.isScrolling = undefined;
            self.deltaX = 0;
            self.wrapper.transition(0);

            isTouched = true;
            allowItemClick = true;


        };
        var onTouchMove = function (e) {
            if (!isTouched) {
                return;
            }
            allowItemClick = false;
            var pageX = e.type === 'touchmove' ? e.originalEvent.targetTouches[0].pageX : e.pageX;
            var pageY = e.type === 'touchmove' ? e.originalEvent.targetTouches[0].pageY : e.pageY;
            self.deltaX = pageX - self.start.pageX;

            if (self.isScrolling === undefined) {
                self.isScrolling = !!(self.isScrolling || Math.abs(self.deltaX) < Math.abs(pageY - self.start.pageY));
            }

            if (self.isScrolling) {
                isTouched = false;
                return;
            }
            e.preventDefault();
            allowItemClick = false;
            clearTimeout(self.interval);
            self.deltaX =
                self.deltaX /
                ((!self.activeIndex && self.deltaX > 0 || self.activeIndex == self.items.length - 1 && self.deltaX < 0) ?
                    (Math.abs(self.deltaX) / self.width + 1) : 1);

            var transform = 'translate3d(' + (self.deltaX - self.activeIndex * self.width) + 'px,0,0)';
            self.wrapper.transform(transform);
        };
        var onTouchEnd = function (e) {
            if (!isTouched) {
                isTouched = false;
                return;
            }
            isTouched = false;
            var isValidSlide =
                    Number(new Date()) - self.start.time < 250 && Math.abs(self.deltaX) > 20 || Math.abs(self.deltaX) > self.width / 2,
                isPastBounds = !self.activeIndex && self.deltaX > 0 || self.activeIndex == self.items.length - 1 && self.deltaX < 0;
            if (!self.isScrolling) {
                self.slide(self.activeIndex + (isValidSlide && !isPastBounds ? (self.deltaX < 0 ? 1 : -1) : 0));
            }
        };

        var onItemClick = function (e) {
            if (!allowItemClick) {
                return;
            }
            var url = $(this).data('url') || '';
            if (url) {
                location.href = url;
            }
        };
        if (self.params.gap) {
            $.each(self.items, function () {
                $(this).css('margin-right', self.params.gap + 'px');
            });
        }
        var resizeSwipes = function () {
            self.width = $(document.body).width();
            $.each(self.items, function () {
                $(this).css('width', self.width + 'px');
            });
            self.slide(self.activeIndex, 0);
        };
        $(window).on('resize', resizeSwipes);

        self.init = function () {
            if (self.page.length > 0) {
                if (self.bullets.length <= 0) {
                    var bulletsHTML = '';
                    for (var i = 0; i < self.items.length; i++) {
                        bulletsHTML += '<div class="fui-swipe-bullet"></div>';
                    }
                    self.page.html(bulletsHTML);
                    self.bullets = self.page.find('.fui-swipe-bullet');
                }
                self.bullets.each(function (index) {
                    $(this).click(function () {
                        clearTimeout(self.interval);
                        self.activeIndex = index;
                        self.slide(self.activeIndex);
                    });

                });
            }
            self.buttons.each(function (i) {
                $(this).click(function () {

                    if ($(this).hasClass('left')) {
                        self.prev();
                    } else {
                        self.next();
                    }
                });
            });
        };
        self.init();
        self.slide(0, 0);
        self.begin();

        self.initEvents = function (detach) {
            var method = detach ? 'off' : 'on';
            self.wrapper[method]($.touchEvents.start, onTouchStart);
            self.wrapper[method]($.touchEvents.move, onTouchMove);
            self.wrapper[method]($.touchEvents.end, onTouchEnd);
            self.items[method]('click', onItemClick);
        };
        if (self.params.touch) {
            self.initEvents();
        }

    };
    $.fn.swipe = function (params) {
        var args = arguments;
        return this.each(function () {

            if (!this)
                return;
            var $this = $(this);
            var swipe = $this.data("swipe");
            if (!swipe) {
                params = params || {};
                swipe = new FoxUISwipe(this, params);
                $this.data("swipe", swipe);
            }
            if (typeof params === typeof "a") {
                swipe[params].apply(swipe, Array.prototype.slice.call(args, 1));
            }
        });
    };

    var FoxUILazyLoad = function (container, params) {
        var defaults = {
            offset: 0,
            delay: 250,
            placeholder: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAA9JREFUeNpi+PTpE0CAAQAFsALXeCy2FAAAAABJRU5ErkJggg=="
        };
        var self = this;
        self.params = $.extend({}, defaults, params || {});
        self.container = $(container);
        var offset = self.params.offset || 0;
        self.params.offsetVertical = self.params.offsetVertical || offset;
        self.params.offsetHorizontal = self.params.offsetHorizontal || offset;
        self.params.delay = self.container.data('lazydelay') || self.params.delay;
        self.timer = null;
        self.toInt = function (str, defaultValue) {
            return parseInt(str || defaultValue, 10);
        };
        self.offset = {
            top: self.toInt(self.params.offsetTop, self.params.offsetVertical),
            bottom: self.toInt(self.params.offsetBottom, self.params.offsetVertical),
            left: self.toInt(self.params.offsetLeft, self.params.offsetHorizontal),
            right: self.toInt(self.params.offsetRight, self.params.offsetHorizontal)
        };

        self.inView = function (element, view) {
            var box = element.getBoundingClientRect();
            return (box.right >= view.left && box.bottom >= view.top && box.left <= view.right && box.top <= view.bottom);
        };

        self.run = function () {

            clearTimeout(self.timer);
            self.timer = setTimeout(self.render, self.params.delay);
        };

        self.render = function (ratio) {

            self.images = self.container.find('img[data-lazy], [data-lazy-background]');

            var view = {
                left: 0 - self.offset.left,
                top: 0 - self.offset.top,
                bottom: (container.innerHeight || document.documentElement.clientHeight) + self.offset.bottom,
                right: (container.innerWidth || document.documentElement.clientWidth) + self.offset.right
            };

            $.each(self.images, function (i) {
                var $this = $(this);
                var inview = self.inView(this, view);
                if (inview) {
                    if ($this.attr('data-lazyloaded')) {
                        return;
                    }
                    if ($this.attr('data-lazy-background')) {
                        $this.css({
                            'background-image': "url('" + $this.data('lazy-background') + "')"
                        });
                        $this.removeAttr('data-lazy-background');
                    } else {
                        var lazy = $this.attr('data-lazy');
                        $this.removeAttr('data-lazy');
                        if (lazy) {
                            this.src = lazy;
                            this.onload = function () {
                                if (!$(this).height()) {
                                    this.style.height = "auto";
                                }
                                this.onload = null;
                            };
                        }
                    }
                    $this.attr('data-lazyloaded', true);

                } else {
                    var placeholder = $this.data('lazy-placeholder') || self.params.placeholder;
                    if (placeholder && !$this.attr('data-lazyloaded')) {
                        if ($this.data('lazy-background') !== undefined && $this.data('lazy-background') === '') {
                            this.style.backgroundImage = "url('" + placeholder + "')";
                            $this.removeAttr('data-lazy-background');
                        } else {
                            this.src = placeholder;
                        }
                        /*if (!$this.height()) {
                         var outerWidth = $(this).parent().length > 0 ? $(this).parent().outerWidth() : '';
                         outerWidth = outerWidth || self.container.outerWidth();
                         var height = outerWidth * ratio;
                         this.style.height = 'auto';
                         }*/
                    }
                    $this.removeData('lazy-placeholder');
                }
                if (self.params.onLoad) {
                    self.params.onLoad(self, this);
                }
            });
        };

        $('<img />').attr('src', self.params.placeholder).load(function () {
            self.render(this.height / this.width);
            if (self.images.length <= 0) {
                self.container.off('scroll', self.run);
            }
        });
        self.container.off('scroll', self.run);
        self.container.on('scroll', self.run).transitionEnd(self.run);
        self.run();
    };

    $.fn.lazyload = function (params) {
        var args = arguments;
        return this.each(function () {
            if (!this)
                return;
            var lazyload = new FoxUILazyLoad(this, params);
            if (typeof params === typeof "a") {
                lazyload[params].apply(lazyload, Array.prototype.slice.call(args, 1));
            }
        });
    };

    var FoxUIStars = function (element, params) {

        var defaults = {
            num: 5,
            value: 0,
            icon: '',
            selectedIcon: '',
            label: ['差评', '一般', '挺好', '满意', '非常满意'],
            labelClass: ['fui-label-default', 'fui-label-primary', 'fui-label-success', 'fui-label-warning', 'fui-label-danger'],
        };
        var self = this;
        self.params = $.extend(defaults, params || {});

        self.container = $(element);
        self.params.num = self.container.data('num') || self.params.num;
        self.params.value = self.container.data('value') || self.params.value;

        self.star_container = self.container.find('.stars');
        self.clear = self.container.find('.clear');

        var starsHTML = "";
        for (var i = 1; i <= self.params.num; i++) {

            if (self.params.value >= i) {
                starsHTML += "<i class='" + self.params.selectedIcon + " selected'></i>";
            } else {
                starsHTML += "<i class='" + self.params.icon + "'></i>";
            }
        }
        if (self.params.value > 0) {
            self.label.html(self.params.label[self.params.value - 1]);
            self.label[0].className = "fui-label " + self.params.labelClass[self.params.value - 1];
        }
        self.star_container.html(starsHTML);
        self.stars = self.star_container.find('i');
        self.label = self.container.find('.fui-label');
        self.stars.click(function (i) {
            var index = $(this).index();
            self.stars.each(function (i) {
                if (index >= i) {
                    $(this)[0].className = self.params.selectedIcon + ' selected';
                } else {
                    $(this)[0].className = self.params.icon;
                }
            });
            if (self.label.length > 0) {
                self.label.html(self.params.label[index]);
                self.label[0].className = "fui-label " + self.params.labelClass[index];
            }
            self.container.data('star', index + 1);
            if (self.params.onSelected) {
                self.params.onSelected(index + 1);
            }
        });
        self.clear.click(function () {
            self.stars.each(function () {
                $(this)[0].className = self.params.icon;
            });
            if (self.label.length > 0) {
                self.label.html('没有评分');
                self.label[0].className = "fui-label ";
            }

            self.container.data('star', 0);
            if (self.params.onSelected) {
                self.params.onSelected(0);
            }
        });
    };

    $.fn.stars = function (params) {
        var args = arguments;
        return this.each(function () {

            if (!this)
                return;
            var $this = $(this);
            var stars = $this.data("stars");
            if (!stars) {
                params = params || {};
                stars = new FoxUIStars(this, params);
                $this.data("stars", stars);
            }
            if (typeof params === typeof "a") {
                stars[params].apply(stars, Array.prototype.slice.call(args, 1));
            }
        });
    };

    var FoxUIPullToRefresh = function (container, params) {
        var self = this;
        self.container = $(container);

        self.params = $.extend({}, params || {});

        var isTouched, isMoved, touchStartX, touchStartY,
            isScrolling, touchesDiff, touchStartTime, refresh = false, loading = false,
            useTranslate = false,
            startTranslate = 0,
            translate, scrollTop, wasScrolled, triggerDistance;
        triggerDistance = 44;

        function handleTouchStart(e) {
            if (isTouched) {
                if ($.device.android) {
                    if ('targetTouches' in e && e.originalEvent.targetTouches.length > 1)
                        return;
                } else
                    return;
            }
            isMoved = false;
            isTouched = true;
            isScrolling = undefined;
            wasScrolled = undefined;
            useTranslate = false;
            touchStartX = e.type === 'touchstart' ? e.originalEvent.targetTouches[0].pageX : e.pageX;
            touchStartY = e.type === 'touchstart' ? e.originalEvent.targetTouches[0].pageY : e.pageY;
            touchStartTime = (new Date()).getTime();

        }

        function handleTouchMove(e) {
            if (!isTouched) {
                return;
            }

            var pageX = e.type === 'touchmove' ? e.originalEvent.targetTouches[0].pageX : e.pageX;
            var pageY = e.type === 'touchmove' ? e.originalEvent.targetTouches[0].pageY : e.pageY;
            if (typeof isScrolling === 'undefined') {
                isScrolling = !!(isScrolling || Math.abs(pageX - touchStartX) < Math.abs(pageY - touchStartY));
            }

            if (!isScrolling) {
                isTouched = false;
                return;
            }
            scrollTop = self.container.scrollTop();
            if (typeof wasScrolled === 'undefined' && scrollTop !== 0)
                wasScrolled = true;

            if (!isMoved) {
                self.container.removeClass('transitioning');
                startTranslate = self.container.hasClass('refreshing') ? triggerDistance : 0;
            }

            isMoved = true;
            touchesDiff = pageY - touchStartY;


            if (touchesDiff > 0 && scrollTop <= 0 || scrollTop < 0) {

                if (scrollTop === 0 && !wasScrolled)
                    useTranslate = true;

                if (useTranslate) {
                    e.preventDefault();
                    translate = (Math.pow(touchesDiff, 0.85) + startTranslate);
                    self.container.transform('translate3d(0,' + translate + 'px,0)');
                } else {
                }
                if ((useTranslate && Math.pow(touchesDiff, 0.85) > triggerDistance) || (!useTranslate && touchesDiff >= triggerDistance * 2)) {
                    refresh = true;
                    self.container.find('.pulldown-loading .arrow').addClass('reverse');
                    self.container.find('.pulldown-loading .text').html('释放刷新');
                } else {
                    refresh = false;
                    self.container.find('.pulldown-loading .arrow').removeClass('reverse');
                    self.container.find('.pulldown-loading .text').html('下拉刷新');

                    if (self.params.onRefreshReady) {
                        self.params.onRefreshReady(self);
                    }
                }
            } else {

                self.container.find('.pulldown-loading .arrow').removeClass('reverse');
                self.container.find('.pulldown-loading .text').html('下拉刷新');
                refresh = false;
                return;
            }

        }

        function handleTouchEnd() {
            if (!isTouched || !isMoved) {
                isTouched = false;
                isMoved = false;
                return;
            }
            if (translate) {
                self.container.addClass('transitioning');
                translate = 0;
            }
            self.container.transform('');

            if (refresh) {

                if (self.container.hasClass('refreshing')) {
                    return;
                }
                self.container.addClass('refreshing');
                self.container.find('.pulldown-loading .text').html('正在刷新');
                if (self.params.onRefresh) {
                    self.params.onRefresh(self);
                }
            } else {
                self.container.find('.pulldown-loading .arrow').removeClass('reverse');
                self.container.find('.pulldown-loading .text').html('下拉刷新');
            }
            isTouched = false;
            isMoved = false;

        }
        self.container.on($.touchEvents.start, handleTouchStart);
        self.container.on($.touchEvents.move, handleTouchMove);
        self.container.on($.touchEvents.end, handleTouchEnd);
        self.done = function () {

            $(window).scrollTop(0);//解决微信下拉刷新顶部消失的问题
            self.container.removeClass('refreshing').addClass('transitioning');
            self.container.transitionEnd(function () {
                self.container.removeClass('transitioning');
            });

        };
        self.detach = function () {
            self.container.off($.touchEvents.start, handleTouchStart);
            self.container.off($.touchEvents.move, handleTouchMove);
            self.container.off($.touchEvents.end, handleTouchEnd);
        };
    };

    $.fn.pullToRefresh = function (params) {
        var args = arguments;
        return this.each(function () {

            if (!this)
                return;
            var $this = $(this);
            var pulltorefresh = $this.data("pulltorefresh");
            if (!pulltorefresh) {
                params = params || {};
                pulltorefresh = new FoxUIPullToRefresh(this, params);
                $this.data("pulltorefresh", pulltorefresh);
            }
            if (typeof params === typeof "a") {
                pulltorefresh[params].apply(pulltorefresh, Array.prototype.slice.call(args, 1));
            }
        });
    };


    var FoxUIPullToLoading = function (container, params) {
        var self = this;
        self.container = $(container);

        self.params = $.extend({}, params || {});

        var isTouched, isMoved, touchStartX, touchStartY,
            isScrolling, touchesDiff, touchStartTime, loading = false,
            useTranslate = false,
            translate, scrollTop, wasScrolled, triggerDistance, containerHeight;
        var triggerDistance = 44;

        function handleTouchStart(e) {
            if (isTouched) {
                if ($.device.android) {
                    if ('targetTouches' in e && e.originalEvent.targetTouches.length > 1)
                        return;
                } else
                    return;
            }
            isMoved = false;
            isTouched = true;
            isScrolling = undefined;
            wasScrolled = undefined;
            useTranslate = false;
            touchStartX = e.type === 'touchstart' ? e.originalEvent.targetTouches[0].pageX : e.pageX;
            touchStartY = e.type === 'touchstart' ? e.originalEvent.targetTouches[0].pageY : e.pageY;
            touchStartTime = (new Date()).getTime();

        }

        function handleTouchMove(e) {
            if (!isTouched) {
                return;
            }

            var pageX = e.type === 'touchmove' ? e.originalEvent.targetTouches[0].pageX : e.pageX;
            var pageY = e.type === 'touchmove' ? e.originalEvent.targetTouches[0].pageY : e.pageY;
            if (typeof isScrolling === 'undefined') {
                isScrolling = !!(isScrolling || Math.abs(pageX - touchStartX) < Math.abs(pageY - touchStartY));
            }

            if (!isScrolling) {
                isTouched = false;
                return;
            }
            containerHeight = self.container.height();
            scrollTop = self.container.scrollTop();
            if (!isMoved) {
                self.container.removeClass('transitioning');
            }

            isMoved = true;
            touchesDiff = pageY - touchStartY;
            if (touchesDiff < 0) {

                var scrollHeight = self.container[0].scrollHeight;
                var bottom = scrollTop + containerHeight >= scrollHeight;

                if (bottom) {
                    useTranslate = true;
                }
                touchesDiff = -touchesDiff;
                if (useTranslate) {

                    e.preventDefault();
                    translate = Math.pow(Math.abs(touchesDiff), 0.85);
                    self.container.transform('translate3d(0,' + -translate + 'px,0)');
                }
                if (bottom) {
                    if (touchesDiff > 0 && touchesDiff <= triggerDistance) {
                        loading = false;
                        self.container.find('.pullup-loading .arrow').addClass('reverse');
                        self.container.find('.pullup-loading .text').html('释放加载');
                        if (self.params.onLoadingReady) {
                            self.params.onLoadingReady(self);
                        }

                    } else if (Math.abs(touchesDiff) > triggerDistance) {

                        loading = true;
                        self.container.find('.pulldown-loading .arrow').removeClass('reverse');
                        self.container.find('.pulldown-loading .text').html('上拉加载');
                    }
                }
            } else {
                loading = false;
                self.container.find('.pullup-loading .arrow').removeClass('reverse');
                self.container.find('.pullup-loading .text').html('上拉加载');
                return;
            }

        }

        function handleTouchEnd() {
            if (!isTouched || !isMoved) {
                isTouched = false;
                isMoved = false;
                return;
            }
            if (translate) {
                self.container.addClass('transitioning');
                translate = 0;
            }
            self.container.transform('');
            if (loading) {

                if (self.container.hasClass('loading')) {
                    return;
                }
                self.container.addClass('loading');
                self.container.find('.pullup-loading .text').html('正在加载');
                if (self.params.onLoading) {
                    self.params.onLoading(self);
                }
            } else {
                self.container.find('.pullup-loading .arrow').removeClass('reverse');
                self.container.find('.pullup-loading .text').html('上拉加载');
            }
            isTouched = false;
            isMoved = false;

        }
        self.container.on($.touchEvents.start, handleTouchStart);
        self.container.on($.touchEvents.move, handleTouchMove);
        self.container.on($.touchEvents.end, handleTouchEnd);
        self.done = function () {


            self.container.removeClass('loading').addClass('transitioning');
            self.container.transitionEnd(function () {
                self.container.removeClass('transitioning');
            });

        };
        self.detach = function () {
            self.container.off($.touchEvents.start, handleTouchStart);
            self.container.off($.touchEvents.move, handleTouchMove);
            self.container.off($.touchEvents.end, handleTouchEnd);
        };
    };

    $.fn.pullToLoading = function (params) {
        var args = arguments;
        return this.each(function () {

            if (!this)
                return;
            var $this = $(this);
            var pulltoloading = $this.data("pulltoloading");
            if (!pulltoloading) {
                params = params || {};
                pulltoloading = new FoxUIPullToLoading(this, params);
                $this.data("pulltoloading", pulltoloading);
            }
            if (typeof params === typeof "a") {
                pulltoloading[params].apply(pulltoloading, Array.prototype.slice.call(args, 1));
            }
        });
    };


    var FoxUIInfinite = function (container, params) {
        var defaults = {
            distance: 40
        };
        var self = this;
        self.params = $.extend(defaults, params || {});
        self.container = $(container);
        self.params.distance = self.container.data('distance') || self.params.distance;
        self.loading = false;
        self.stop = function () {

            $('.infinite-loading').hide();
            self.container.unbind('scroll');
        };
        self.init = function () {
            self.loading = false;
            self.container.scroll(function () {
                var height = self.container.height();
                var scrollHight = self.container[0].scrollHeight;
                var scrollTop = self.container[0].scrollTop;
                var paddingBottom = parseFloat(self.container.css('padding-bottom').replace("px", ""));
                if (scrollTop + height + paddingBottom + self.params.distance >= scrollHight) {
                    if (self.loading) {
                        return;
                    }
                    self.loading = true;
                    $('.infinite-loading').show();
                    if (self.params.onLoading) {
                        self.params.onLoading();
                    }

                }
            });
        };
        self.init();
    };
    $.fn.infinite = function (params) {
        var args = arguments;
        return this.each(function () {
            if (!this)
                return;
            var $this = $(this);
            var infinite = $this.data("infinite");
            if (infinite === undefined) {
                infinite = new FoxUIInfinite(this, params || {});
                $this.data("infinite", infinite);
            }
            if (typeof params === typeof "a") {
                infinite[params].apply(infinite, Array.prototype.slice.call(args, 1));
            }
        });
    };
    var FoxUITimer = function (container, params) {
        var defaults = {
            startLabel: "距离开始",
            endLabel: "距离结束",
            endText: "活动已结束",
            label: '.label'
        };
        var self = this;
        self.params = $.extend(defaults, params || {});
        self.container = $(container);
        self.params.now = self.container.data('now');
        if (self.params.now) {
            self.params.now = new Date(Date.parse(self.params.now.replace(/-/g, "/")));
        } else {
            self.params.now = new Date();
        }

        self.params.nowTime = self.params.now.getTime();
        self.params.start = self.container.data('start') || self.params.start || false;
        self.params.end = self.container.data('end') || self.params.end || false;
        self.params.startLabel = self.container.data('startLabel') || self.params.startLabel || '';		// 开始 文字
        self.params.endLabel = self.container.data('endLabel') || self.params.endLabel || '';		// 结束文字
        self.params.endText = self.container.data('endText') || self.params.endText || '';		// 结束描述文字
        self.params.label = self.container.data('label') || self.params.label;					// 文字投放元素
        self.params.labelChange = self.params.labelChange;	// 是否自动修改标签
        if (self.container.data('label-change') === false) {
            self.params.labelChange = false;
        }
        self.timeD = self.container.find('.day');
        self.timeH = self.container.find('.hour');
        self.timeM = self.container.find('.minute');
        self.timeS = self.container.find('.second');
        self.timer = 0;

        self.stop = function () {
            clearTimeout(self.timer);
            return;
        };
        self.update = function () {

            var startTime = false, endTime = false;
            if (self.params.start) {
                startTime = +new Date(Date.parse(self.params.start.replace(/-/g, "/")));
            }
            if (self.params.end) {
                endTime = +new Date(Date.parse(self.params.end.replace(/-/g, "/")));
            }
            var status = 0;
            if (startTime && endTime) {
                //两个时间都有
                if (startTime > self.params.nowTime) {
                    //未开始
                    status = -1;
                } else if (endTime < self.params.nowTime) {
                    //已结束
                    status = 1;
                } else if(startTime == self.params.nowTime){
                    if (self.params.onStart) {
                        self.params.onStart(self.container);
                    }
                }
            } else if (startTime) {
                //只有开始时间
                if (startTime > self.params.nowTime) {
                    //未开始
                    status = -1;
                }else if(startTime == self.params.nowTime){
                    if (self.params.onStart) {
                        self.params.onStart(self.container);
                    }
                }
            } else if (endTime) {
                //只有开始时间
                if (endTime < self.params.nowTime) {
                    //未开始
                    status = 1;
                }
            }
            var time = 0;
            if (status == -1) {
                //未开始
                time = startTime;
                if (self.params.startLabel !=='') {
                    $(self.params.label, self.container).html(self.params.startLabel);
                }
            } else if (status == 1) {
                //已结束
                if (self.params.endText !== '') {
                    $(self.timeD).parent().html(self.params.endText);
                }
                return;
            } else {
                time = endTime;
                if (self.params.endLabel !== '') {

                    $(self.params.label, self.container).html(self.params.endLabel);
                }
            }

            //正在进行
            if (time > 0) {
                var lag = (time - self.params.nowTime) / 1000; //当前时间和结束时间之间的秒数

                if (lag > 0) {
                    var second = Math.floor(lag % 60) + "";
                    var minute = Math.floor((lag / 60) % 60) + "";
                    var hour = Math.floor((lag / 3600) % 24) + "";
                    var day = Math.floor((lag / 3600) / 24) + "";
                    $(self.timeD).text(day);
                    $(self.timeH).text(hour.length == 1 ? '0' + hour : hour);
                    $(self.timeM).text(minute.length == 1 ? '0' + minute : minute);
                    $(self.timeS).text(second.length == 1 ? '0' + second : second);

                } else {
                    if (self.params.onEnd) {
                        self.stop();
                        self.params.onEnd(self.container);
                    }
                    return;
                }
                self.timer = setTimeout(function () {
                    self.params.nowTime += 1000;
                    self.update();
                }, 1000);
            }
        };
        self.update();
    };
    $.fn.timer = function (params) {
        var args = arguments;
        return this.each(function () {
            if (!this)
                return;
            var $this = $(this);
            var timer = $this.data("timer");
            if (!timer) {
                timer = new FoxUITimer(this, params || {});
                $this.data("timer", timer);
            }
            if (typeof params === typeof "a") {
                timer[params].apply(timer, Array.prototype.slice.call(args, 1));
            }
        });
    };




    function FastClick(layer, options) {
        var oldOnClick;

        options = options || {};

        /**
         * Whether a click is currently being tracked.
         *
         * @type boolean
         */
        this.trackingClick = false;


        /**
         * Timestamp for when click tracking started.
         *
         * @type number
         */
        this.trackingClickStart = 0;


        /**
         * The element being tracked for a click.
         *
         * @type EventTarget
         */
        this.targetElement = null;


        /**
         * X-coordinate of touch start event.
         *
         * @type number
         */
        this.touchStartX = 0;


        /**
         * Y-coordinate of touch start event.
         *
         * @type number
         */
        this.touchStartY = 0;


        /**
         * ID of the last touch, retrieved from Touch.identifier.
         *
         * @type number
         */
        this.lastTouchIdentifier = 0;


        /**
         * Touchmove boundary, beyond which a click will be cancelled.
         *
         * @type number
         */
        this.touchBoundary = options.touchBoundary || 10;


        /**
         * The FastClick layer.
         *
         * @type Element
         */
        this.layer = layer;

        /**
         * The minimum time between tap(touchstart and touchend) events
         *
         * @type number
         */
        this.tapDelay = options.tapDelay || 200;

        /**
         * The maximum time for a tap
         *
         * @type number
         */
        this.tapTimeout = options.tapTimeout || 700;

        if (FastClick.notNeeded(layer)) {
            return;
        }

        // Some old versions of Android don't have Function.prototype.bind
        function bind(method, context) {
            return function () {
                return method.apply(context, arguments);
            };
        }


        var methods = ['onMouse', 'onClick', 'onTouchStart', 'onTouchMove', 'onTouchEnd', 'onTouchCancel'];
        var context = this;
        for (var i = 0, l = methods.length; i < l; i++) {
            context[methods[i]] = bind(context[methods[i]], context);
        }

        // Set up event handlers as required
        if (deviceIsAndroid) {
            layer.addEventListener('mouseover', this.onMouse, true);
            layer.addEventListener('mousedown', this.onMouse, true);
            layer.addEventListener('mouseup', this.onMouse, true);
        }

        layer.addEventListener('click', this.onClick, true);
        layer.addEventListener('touchstart', this.onTouchStart, false);
        layer.addEventListener('touchmove', this.onTouchMove, false);
        layer.addEventListener('touchend', this.onTouchEnd, false);
        layer.addEventListener('touchcancel', this.onTouchCancel, false);

        // Hack is required for browsers that don't support Event#stopImmediatePropagation (e.g. Android 2)
        // which is how FastClick normally stops click events bubbling to callbacks registered on the FastClick
        // layer when they are cancelled.
        if (!Event.prototype.stopImmediatePropagation) {
            layer.removeEventListener = function (type, callback, capture) {
                var rmv = Node.prototype.removeEventListener;
                if (type === 'click') {
                    rmv.call(layer, type, callback.hijacked || callback, capture);
                } else {
                    rmv.call(layer, type, callback, capture);
                }
            };

            layer.addEventListener = function (type, callback, capture) {
                var adv = Node.prototype.addEventListener;
                if (type === 'click') {
                    adv.call(layer, type, callback.hijacked || (callback.hijacked = function (event) {
                            if (!event.propagationStopped) {
                                callback(event);
                            }
                        }), capture);
                } else {
                    adv.call(layer, type, callback, capture);
                }
            };
        }

        // If a handler is already declared in the element's onclick attribute, it will be fired before
        // FastClick's onClick handler. Fix this by pulling out the user-defined handler function and
        // adding it as listener.
        if (typeof layer.onclick === 'function') {

            // Android browser on at least 3.2 requires a new reference to the function in layer.onclick
            // - the old one won't work if passed to addEventListener directly.
            oldOnClick = layer.onclick;
            layer.addEventListener('click', function (event) {
                oldOnClick(event);
            }, false);
            layer.onclick = null;
        }
    }

    /**
     * Windows Phone 8.1 fakes user agent string to look like Android and iPhone.
     *
     * @type boolean
     */
    var deviceIsWindowsPhone = navigator.userAgent.indexOf("Windows Phone") >= 0;

    /**
     * Android requires exceptions.
     *
     * @type boolean
     */
    var deviceIsAndroid = navigator.userAgent.indexOf('Android') > 0 && !deviceIsWindowsPhone;


    /**
     * iOS requires exceptions.
     *
     * @type boolean
     */
    var deviceIsIOS = /iP(ad|hone|od)/.test(navigator.userAgent) && !deviceIsWindowsPhone;


    /**
     * iOS 4 requires an exception for select elements.
     *
     * @type boolean
     */
    var deviceIsIOS4 = deviceIsIOS && (/OS 4_\d(_\d)?/).test(navigator.userAgent);


    /**
     * iOS 6.0-7.* requires the target element to be manually derived
     *
     * @type boolean
     */
    var deviceIsIOSWithBadTarget = deviceIsIOS && (/OS [6-7]_\d/).test(navigator.userAgent);

    /**
     * BlackBerry requires exceptions.
     *
     * @type boolean
     */
    var deviceIsBlackBerry10 = navigator.userAgent.indexOf('BB10') > 0;

    /**
     * Determine whether a given element requires a native click.
     *
     * @param {EventTarget|Element} target Target DOM element
     * @returns {boolean} Returns true if the element needs a native click
     */
    FastClick.prototype.needsClick = function (target) {
        switch (target.nodeName.toLowerCase()) {

            // Don't send a synthetic click to disabled inputs (issue #62)
            case 'button':
            case 'select':
            case 'textarea':
                if (target.disabled) {
                    return true;
                }

                break;
            case 'input':

                // File inputs need real clicks on iOS 6 due to a browser bug (issue #68)
                if ((deviceIsIOS && target.type === 'file') || target.disabled) {
                    return true;
                }

                break;
            case 'label':
            case 'iframe': // iOS8 homescreen apps can prevent events bubbling into frames
            case 'video':
                return true;
        }

        return (/\bneedsclick\b/).test(target.className);
    };


    /**
     * Determine whether a given element requires a call to focus to simulate click into element.
     *
     * @param {EventTarget|Element} target Target DOM element
     * @returns {boolean} Returns true if the element requires a call to focus to simulate native click.
     */
    FastClick.prototype.needsFocus = function (target) {
        switch (target.nodeName.toLowerCase()) {
            case 'textarea':
                return true;
            case 'select':
                return !deviceIsAndroid;
            case 'input':
                switch (target.type) {
                    case 'button':
                    case 'checkbox':
                    case 'file':
                    case 'image':
                    case 'radio':
                    case 'submit':
                        return false;
                }

                // No point in attempting to focus disabled inputs
                return !target.disabled && !target.readOnly;
            default:
                return (/\bneedsfocus\b/).test(target.className);
        }
    };


    /**
     * Send a click event to the specified element.
     *
     * @param {EventTarget|Element} targetElement
     * @param {Event} event
     */
    FastClick.prototype.sendClick = function (targetElement, event) {
        var clickEvent, touch;

        // On some Android devices activeElement needs to be blurred otherwise the synthetic click will have no effect (#24)
        if (document.activeElement && document.activeElement !== targetElement) {
            document.activeElement.blur();
        }

        touch = event.changedTouches[0];

        // Synthesise a click event, with an extra attribute so it can be tracked
        clickEvent = document.createEvent('MouseEvents');
        clickEvent.initMouseEvent(this.determineEventType(targetElement), true, true, window, 1, touch.screenX, touch.screenY, touch.clientX, touch.clientY, false, false, false, false, 0, null);
        clickEvent.forwardedTouchEvent = true;
        targetElement.dispatchEvent(clickEvent);
    };

    FastClick.prototype.determineEventType = function (targetElement) {

        //Issue #159: Android Chrome Select Box does not open with a synthetic click event
        if (deviceIsAndroid && targetElement.tagName.toLowerCase() === 'select') {
            return 'mousedown';
        }

        return 'click';
    };


    /**
     * @param {EventTarget|Element} targetElement
     */
    FastClick.prototype.focus = function (targetElement) {
        var length;

        // Issue #160: on iOS 7, some input elements (e.g. date datetime month) throw a vague TypeError on setSelectionRange. These elements don't have an integer value for the selectionStart and selectionEnd properties, but unfortunately that can't be used for detection because accessing the properties also throws a TypeError. Just check the type instead. Filed as Apple bug #15122724.
        if (deviceIsIOS && targetElement.setSelectionRange && targetElement.type.indexOf('date') !== 0 && targetElement.type !== 'time' && targetElement.type !== 'month') {
            length = targetElement.value.length;
            targetElement.setSelectionRange(length, length);
        } else {
            targetElement.focus();
        }
    };


    /**
     * Check whether the given target element is a child of a scrollable layer and if so, set a flag on it.
     *
     * @param {EventTarget|Element} targetElement
     */
    FastClick.prototype.updateScrollParent = function (targetElement) {
        var scrollParent, parentElement;

        scrollParent = targetElement.fastClickScrollParent;

        // Attempt to discover whether the target element is contained within a scrollable layer. Re-check if the
        // target element was moved to another parent.
        if (!scrollParent || !scrollParent.contains(targetElement)) {
            parentElement = targetElement;
            do {
                if (parentElement.scrollHeight > parentElement.offsetHeight) {
                    scrollParent = parentElement;
                    targetElement.fastClickScrollParent = parentElement;
                    break;
                }

                parentElement = parentElement.parentElement;
            } while (parentElement);
        }

        // Always update the scroll top tracker if possible.
        if (scrollParent) {
            scrollParent.fastClickLastScrollTop = scrollParent.scrollTop;
        }
    };


    /**
     * @param {EventTarget} targetElement
     * @returns {Element|EventTarget}
     */
    FastClick.prototype.getTargetElementFromEventTarget = function (eventTarget) {

        // On some older browsers (notably Safari on iOS 4.1 - see issue #56) the event target may be a text node.
        if (eventTarget.nodeType === Node.TEXT_NODE) {
            return eventTarget.parentNode;
        }

        return eventTarget;
    };


    /**
     * On touch start, record the position and scroll offset.
     *
     * @param {Event} event
     * @returns {boolean}
     */
    FastClick.prototype.onTouchStart = function (event) {
        var targetElement, touch, selection;

        // Ignore multiple touches, otherwise pinch-to-zoom is prevented if both fingers are on the FastClick element (issue #111).
        if (event.targetTouches.length > 1) {
            return true;
        }

        targetElement = this.getTargetElementFromEventTarget(event.target);
        touch = event.targetTouches[0];

        if (deviceIsIOS) {

            // Only trusted events will deselect text on iOS (issue #49)
            selection = window.getSelection();
            if (selection.rangeCount && !selection.isCollapsed) {
                return true;
            }

            if (!deviceIsIOS4) {

                // Weird things happen on iOS when an alert or confirm dialog is opened from a click event callback (issue #23):
                // when the user next taps anywhere else on the page, new touchstart and touchend events are dispatched
                // with the same identifier as the touch event that previously triggered the click that triggered the alert.
                // Sadly, there is an issue on iOS 4 that causes some normal touch events to have the same identifier as an
                // immediately preceeding touch event (issue #52), so this fix is unavailable on that platform.
                // Issue 120: touch.identifier is 0 when Chrome dev tools 'Emulate touch events' is set with an iOS device UA string,
                // which causes all touch events to be ignored. As this block only applies to iOS, and iOS identifiers are always long,
                // random integers, it's safe to to continue if the identifier is 0 here.
                if (touch.identifier && touch.identifier === this.lastTouchIdentifier) {
                    event.preventDefault();
                    return false;
                }

                this.lastTouchIdentifier = touch.identifier;

                // If the target element is a child of a scrollable layer (using -webkit-overflow-scrolling: touch) and:
                // 1) the user does a fling scroll on the scrollable layer
                // 2) the user stops the fling scroll with another tap
                // then the event.target of the last 'touchend' event will be the element that was under the user's finger
                // when the fling scroll was started, causing FastClick to send a click event to that layer - unless a check
                // is made to ensure that a parent layer was not scrolled before sending a synthetic click (issue #42).
                this.updateScrollParent(targetElement);
            }
        }

        this.trackingClick = true;
        this.trackingClickStart = event.timeStamp;
        this.targetElement = targetElement;

        this.touchStartX = touch.pageX;
        this.touchStartY = touch.pageY;

        // Prevent phantom clicks on fast double-tap (issue #36)
        if ((event.timeStamp - this.lastClickTime) < this.tapDelay) {
            event.preventDefault();
        }

        return true;
    };


    /**
     * Based on a touchmove event object, check whether the touch has moved past a boundary since it started.
     *
     * @param {Event} event
     * @returns {boolean}
     */
    FastClick.prototype.touchHasMoved = function (event) {
        var touch = event.changedTouches[0], boundary = this.touchBoundary;

        if (Math.abs(touch.pageX - this.touchStartX) > boundary || Math.abs(touch.pageY - this.touchStartY) > boundary) {
            return true;
        }

        return false;
    };


    /**
     * Update the last position.
     *
     * @param {Event} event
     * @returns {boolean}
     */
    FastClick.prototype.onTouchMove = function (event) {
        if (!this.trackingClick) {
            return true;
        }

        // If the touch has moved, cancel the click tracking
        if (this.targetElement !== this.getTargetElementFromEventTarget(event.target) || this.touchHasMoved(event)) {
            this.trackingClick = false;
            this.targetElement = null;
        }

        return true;
    };


    /**
     * Attempt to find the labelled control for the given label element.
     *
     * @param {EventTarget|HTMLLabelElement} labelElement
     * @returns {Element|null}
     */
    FastClick.prototype.findControl = function (labelElement) {

        // Fast path for newer browsers supporting the HTML5 control attribute
        if (labelElement.control !== undefined) {
            return labelElement.control;
        }

        // All browsers under test that support touch events also support the HTML5 htmlFor attribute
        if (labelElement.htmlFor) {
            return document.getElementById(labelElement.htmlFor);
        }

        // If no for attribute exists, attempt to retrieve the first labellable descendant element
        // the list of which is defined here: http://www.w3.org/TR/html5/forms.html#category-label
        return labelElement.querySelector('button, input:not([type=hidden]), keygen, meter, output, progress, select, textarea');
    };


    /**
     * On touch end, determine whether to send a click event at once.
     *
     * @param {Event} event
     * @returns {boolean}
     */
    FastClick.prototype.onTouchEnd = function (event) {
        var forElement, trackingClickStart, targetTagName, scrollParent, touch, targetElement = this.targetElement;

        if (!this.trackingClick) {
            return true;
        }

        // Prevent phantom clicks on fast double-tap (issue #36)
        if ((event.timeStamp - this.lastClickTime) < this.tapDelay) {
            this.cancelNextClick = true;
            return true;
        }

        if ((event.timeStamp - this.trackingClickStart) > this.tapTimeout) {
            return true;
        }

        // Reset to prevent wrong click cancel on input (issue #156).
        this.cancelNextClick = false;

        this.lastClickTime = event.timeStamp;

        trackingClickStart = this.trackingClickStart;
        this.trackingClick = false;
        this.trackingClickStart = 0;

        // On some iOS devices, the targetElement supplied with the event is invalid if the layer
        // is performing a transition or scroll, and has to be re-detected manually. Note that
        // for this to function correctly, it must be called *after* the event target is checked!
        // See issue #57; also filed as rdar://13048589 .
        if (deviceIsIOSWithBadTarget) {
            touch = event.changedTouches[0];

            // In certain cases arguments of elementFromPoint can be negative, so prevent setting targetElement to null
            targetElement = document.elementFromPoint(touch.pageX - window.pageXOffset, touch.pageY - window.pageYOffset) || targetElement;
            targetElement.fastClickScrollParent = this.targetElement.fastClickScrollParent;
        }

        targetTagName = targetElement.tagName.toLowerCase();
        if (targetTagName === 'label') {
            forElement = this.findControl(targetElement);
            if (forElement) {
                this.focus(targetElement);
                if (deviceIsAndroid) {
                    return false;
                }

                targetElement = forElement;
            }
        } else if (this.needsFocus(targetElement)) {

            // Case 1: If the touch started a while ago (best guess is 100ms based on tests for issue #36) then focus will be triggered anyway. Return early and unset the target element reference so that the subsequent click will be allowed through.
            // Case 2: Without this exception for input elements tapped when the document is contained in an iframe, then any inputted text won't be visible even though the value attribute is updated as the user types (issue #37).
            if ((event.timeStamp - trackingClickStart) > 100 || (deviceIsIOS && window.top !== window && targetTagName === 'input')) {
                this.targetElement = null;
                return false;
            }

            this.focus(targetElement);
            this.sendClick(targetElement, event);

            // Select elements need the event to go through on iOS 4, otherwise the selector menu won't open.
            // Also this breaks opening selects when VoiceOver is active on iOS6, iOS7 (and possibly others)
            if (!deviceIsIOS || targetTagName !== 'select') {
                this.targetElement = null;
                event.preventDefault();
            }

            return false;
        }

        if (deviceIsIOS && !deviceIsIOS4) {

            // Don't send a synthetic click event if the target element is contained within a parent layer that was scrolled
            // and this tap is being used to stop the scrolling (usually initiated by a fling - issue #42).
            scrollParent = targetElement.fastClickScrollParent;
            if (scrollParent && scrollParent.fastClickLastScrollTop !== scrollParent.scrollTop) {
                return true;
            }
        }

        // Prevent the actual click from going though - unless the target node is marked as requiring
        // real clicks or if it is in the whitelist in which case only non-programmatic clicks are permitted.
        if (!this.needsClick(targetElement)) {
            event.preventDefault();
            this.sendClick(targetElement, event);
        }

        return false;
    };


    /**
     * On touch cancel, stop tracking the click.
     *
     * @returns {void}
     */
    FastClick.prototype.onTouchCancel = function () {
        this.trackingClick = false;
        this.targetElement = null;
    };


    /**
     * Determine mouse events which should be permitted.
     *
     * @param {Event} event
     * @returns {boolean}
     */
    FastClick.prototype.onMouse = function (event) {

        // If a target element was never set (because a touch event was never fired) allow the event
        if (!this.targetElement) {
            return true;
        }

        if (event.forwardedTouchEvent) {
            return true;
        }

        // Programmatically generated events targeting a specific element should be permitted
        if (!event.cancelable) {
            return true;
        }

        // Derive and check the target element to see whether the mouse event needs to be permitted;
        // unless explicitly enabled, prevent non-touch click events from triggering actions,
        // to prevent ghost/doubleclicks.
        if (!this.needsClick(this.targetElement) || this.cancelNextClick) {

            // Prevent any user-added listeners declared on FastClick element from being fired.
            if (event.stopImmediatePropagation) {
                event.stopImmediatePropagation();
            } else {

                // Part of the hack for browsers that don't support Event#stopImmediatePropagation (e.g. Android 2)
                event.propagationStopped = true;
            }

            // Cancel the event
            event.stopPropagation();
            event.preventDefault();

            return false;
        }

        // If the mouse event is permitted, return true for the action to go through.
        return true;
    };


    /**
     * On actual clicks, determine whether this is a touch-generated click, a click action occurring
     * naturally after a delay after a touch (which needs to be cancelled to avoid duplication), or
     * an actual click which should be permitted.
     *
     * @param {Event} event
     * @returns {boolean}
     */
    FastClick.prototype.onClick = function (event) {
        var permitted;

        // It's possible for another FastClick-like library delivered with third-party code to fire a click event before FastClick does (issue #44). In that case, set the click-tracking flag back to false and return early. This will cause onTouchEnd to return early.
        if (this.trackingClick) {
            this.targetElement = null;
            this.trackingClick = false;
            return true;
        }

        // Very odd behaviour on iOS (issue #18): if a submit element is present inside a form and the user hits enter in the iOS simulator or clicks the Go button on the pop-up OS keyboard the a kind of 'fake' click event will be triggered with the submit-type input element as the target.
        if (event.target.type === 'submit' && event.detail === 0) {
            return true;
        }

        permitted = this.onMouse(event);

        // Only unset targetElement if the click is not permitted. This will ensure that the check for !targetElement in onMouse fails and the browser's click doesn't go through.
        if (!permitted) {
            this.targetElement = null;
        }

        // If clicks are permitted, return true for the action to go through.
        return permitted;
    };


    /**
     * Remove all FastClick's event listeners.
     *
     * @returns {void}
     */
    FastClick.prototype.destroy = function () {
        var layer = this.layer;

        if (deviceIsAndroid) {
            layer.removeEventListener('mouseover', this.onMouse, true);
            layer.removeEventListener('mousedown', this.onMouse, true);
            layer.removeEventListener('mouseup', this.onMouse, true);
        }

        layer.removeEventListener('click', this.onClick, true);
        layer.removeEventListener('touchstart', this.onTouchStart, false);
        layer.removeEventListener('touchmove', this.onTouchMove, false);
        layer.removeEventListener('touchend', this.onTouchEnd, false);
        layer.removeEventListener('touchcancel', this.onTouchCancel, false);
    };


    /**
     * Check whether FastClick is needed.
     *
     * @param {Element} layer The layer to listen on
     */
    FastClick.notNeeded = function (layer) {
        var metaViewport;
        var chromeVersion;
        var blackberryVersion;
        var firefoxVersion;

        // Devices that don't support touch don't need FastClick
        if (typeof window.ontouchstart === 'undefined') {
            return true;
        }

        // Chrome version - zero for other browsers
        chromeVersion = +(/Chrome\/([0-9]+)/.exec(navigator.userAgent) || [, 0])[1];

        if (chromeVersion) {

            if (deviceIsAndroid) {
                metaViewport = document.querySelector('meta[name=viewport]');

                if (metaViewport) {
                    // Chrome on Android with user-scalable="no" doesn't need FastClick (issue #89)
                    if (metaViewport.content.indexOf('user-scalable=no') !== -1) {
                        return true;
                    }
                    // Chrome 32 and above with width=device-width or less don't need FastClick
                    if (chromeVersion > 31 && document.documentElement.scrollWidth <= window.outerWidth) {
                        return true;
                    }
                }

                // Chrome desktop doesn't need FastClick (issue #15)
            } else {
                return true;
            }
        }

        if (deviceIsBlackBerry10) {
            blackberryVersion = navigator.userAgent.match(/Version\/([0-9]*)\.([0-9]*)/);

            // BlackBerry 10.3+ does not require Fastclick library.
            // https://github.com/ftlabs/fastclick/issues/251
            if (blackberryVersion[1] >= 10 && blackberryVersion[2] >= 3) {
                metaViewport = document.querySelector('meta[name=viewport]');

                if (metaViewport) {
                    // user-scalable=no eliminates click delay.
                    if (metaViewport.content.indexOf('user-scalable=no') !== -1) {
                        return true;
                    }
                    // width=device-width (or less than device-width) eliminates click delay.
                    if (document.documentElement.scrollWidth <= window.outerWidth) {
                        return true;
                    }
                }
            }
        }

        // IE10 with -ms-touch-action: none or manipulation, which disables double-tap-to-zoom (issue #97)
        if (layer.style.msTouchAction === 'none' || layer.style.touchAction === 'manipulation') {
            return true;
        }

        // Firefox version - zero for other browsers
        firefoxVersion = +(/Firefox\/([0-9]+)/.exec(navigator.userAgent) || [, 0])[1];

        if (firefoxVersion >= 27) {
            // Firefox 27+ does not have tap delay if the content is not zoomable - https://bugzilla.mozilla.org/show_bug.cgi?id=922896

            metaViewport = document.querySelector('meta[name=viewport]');
            if (metaViewport && (metaViewport.content.indexOf('user-scalable=no') !== -1 || document.documentElement.scrollWidth <= window.outerWidth)) {
                return true;
            }
        }

        // IE11: prefixed -ms-touch-action is no longer supported and it's recomended to use non-prefixed version
        // http://msdn.microsoft.com/en-us/library/windows/apps/Hh767313.aspx
        if (layer.style.touchAction === 'none' || layer.style.touchAction === 'manipulation') {
            return true;
        }

        return false;
    };


    /**
     * Factory method for creating a FastClick object
     *
     * @param {Element} layer The layer to listen on
     * @param {Object} [options={}] The options to override the defaults
     */
    FastClick.attach = function (layer, options) {
        return new FastClick(layer, options);
    };
    window.FastClick = FastClick;

    if (!window.CustomEvent) {
        window.CustomEvent = function (type, config) {
            config = config || {bubbles: false, cancelable: false, detail: undefined};
            var e = document.createEvent('CustomEvent');
            e.initCustomEvent(type, config.bubbles, config.cancelable, config.detail);
            return e;
        };

        window.CustomEvent.prototype = window.Event.prototype;
    }

    var EVENTS = {
        pageLoadStart: 'pageLoadStart', // ajax 开始加载新页面前
        pageLoadCancel: 'pageLoadCancel', // 取消前一个 ajax 加载动作后
        pageLoadError: 'pageLoadError', // ajax 加载页面失败后
        pageLoadComplete: 'pageLoadComplete', // ajax 加载页面完成后（不论成功与否）
        pageAnimationStart: 'pageAnimationStart', // 动画切换 page 前
        pageAnimationEnd: 'pageAnimationEnd', // 动画切换 page 结束后
        beforePageRemove: 'beforePageRemove', // 移除旧 document 前（适用于非内联 page 切换）
        pageRemoved: 'pageRemoved', // 移除旧 document 后（适用于非内联 page 切换）
        beforePageSwitch: 'beforePageSwitch', // page 切换前，在 pageAnimationStart 前，beforePageSwitch 之后会做一些额外的处理才触发 pageAnimationStart
        pageInit: 'pageInitInternal' // 目前是定义为一个 page 加载完毕后（实际和 pageAnimationEnd 等同）
    };

    var Util = {
        /**
         * 获取 url 的 fragment（即 hash 中去掉 # 的剩余部分）
         *
         * 如果没有则返回空字符串
         * 如: http://example.com/path/?query=d#123 => 123
         *
         * @param {String} url url
         * @returns {String}
         */
        getUrlFragment: function (url) {
            var hashIndex = url.indexOf('#');
            return hashIndex === -1 ? '' : url.slice(hashIndex + 1);
        },
        /**
         * 获取一个链接相对于当前页面的绝对地址形式
         *
         * 假设当前页面是 http://a.com/b/c
         * 那么有以下情况:
         * d => http://a.com/b/d
         * /e => http://a.com/e
         * #1 => http://a.com/b/c#1
         * http://b.com/f => http://b.com/f
         *
         * @param {String} url url
         * @returns {String}
         */
        getAbsoluteUrl: function (url) {
            var link = document.createElement('a');
            link.setAttribute('href', url);
            var absoluteUrl = link.href;
            link = null;
            return absoluteUrl;
        },
        /**
         * 获取一个 url 的基本部分，即不包括 hash
         *
         * @param {String} url url
         * @returns {String}
         */
        getBaseUrl: function (url) {
            var hashIndex = url.indexOf('#');
            return hashIndex === -1 ? url.slice(0) : url.slice(0, hashIndex);
        },
        /**
         * 把一个字符串的 url 转为一个可获取其 base 和 fragment 等的对象
         *
         * @param {String} url url
         * @returns {UrlObject}
         */
        toUrlObject: function (url) {
            var fullUrl = this.getAbsoluteUrl(url),
                baseUrl = this.getBaseUrl(fullUrl),
                fragment = this.getUrlFragment(url);

            return {
                base: baseUrl,
                full: fullUrl,
                original: url,
                fragment: fragment
            };
        },
        /**
         * 判断浏览器是否支持 sessionStorage，支持返回 true，否则返回 false
         * @returns {Boolean}
         */
        supportStorage: function () {
            var mod = 'foxui.router.storage.ability';
            try {
                sessionStorage.setItem(mod, mod);
                sessionStorage.removeItem(mod);
                return true;
            } catch (e) {
                return false;
            }
        }
    };

    var routerConfig = {
        sectionGroupClass: 'fui-page-group',
        // 表示是当前 page 的 class
        curPageClass: 'fui-page-current',
        // 用来辅助切换时表示 page 是 visible 的,
        // 之所以不用 curPageClass，是因为 page-current 已被赋予了「当前 page」这一含义而不仅仅是 display: block
        // 并且，别的地方已经使用了，所以不方便做变更，故新增一个
        visiblePageClass: 'fui-page-visible',
        // 表示是 page 的 class，注意，仅是标志 class，而不是所有的 class
        pageClass: 'fui-page'
    };

    var DIRECTION = {
        leftToRight: 'from-left-to-right',
        rightToLeft: 'from-right-to-left'
    };

    var theHistory = window.history;

    var Router = function () {
        this.sessionNames = {
            currentState: 'foxui.router.currentState',
            maxStateId: 'foxui.router.maxStateId'
        };

        this._init();
        this.xhr = null;
        window.addEventListener('popstate', this._onPopState.bind(this));
    };

    /**
     * 初始化
     *
     * - 把当前文档内容缓存起来
     * - 查找默认展示的块内容，查找顺序如下
     *      1. id 是 url 中的 fragment 的元素
     *      2. 有当前块 class 标识的第一个元素
     *      3. 第一个块
     * - 初始页面 state 处理
     *
     * @private
     */
    Router.prototype._init = function () {

        this.$view = $('body');

        // 用来保存 document 的 map
        this.cache = {};
        var $doc = $(document);
        var currentUrl = location.href;
        this._saveDocumentIntoCache($doc, currentUrl);

        var curPageId;

        var currentUrlObj = Util.toUrlObject(currentUrl);
        var $allSection = $doc.find('.' + routerConfig.pageClass);
        var $visibleSection = $doc.find('.' + routerConfig.curPageClass);
        var $curVisibleSection = $visibleSection.eq(0);
        var $hashSection;

        if (currentUrlObj.fragment) {
            $hashSection = $doc.find('#' + currentUrlObj.fragment);
        }
        if ($hashSection && $hashSection.length) {
            $visibleSection = $hashSection.eq(0);
        } else if (!$visibleSection.length) {
            $visibleSection = $allSection.eq(0);
        }
        if (!$visibleSection.attr('id')) {
            $visibleSection.attr('id', this._generateRandomId());
        }

        if ($curVisibleSection.length &&
            ($curVisibleSection.attr('id') !== $visibleSection.attr('id'))) {
            // 在 router 到 inner page 的情况下，刷新（或者直接访问该链接）
            // 直接切换 class 会有「闪」的现象,或许可以采用 animateSection 来减缓一下
            $curVisibleSection.removeClass(routerConfig.curPageClass);
            $visibleSection.addClass(routerConfig.curPageClass);
        } else {
            $visibleSection.addClass(routerConfig.curPageClass);
        }
        curPageId = $visibleSection.attr('id');


        // 新进入一个使用 history.state 相关技术的页面时，如果第一个 state 不 push/replace,
        // 那么在后退回该页面时，将不触发 popState 事件
        if (theHistory.state === null) {
            var curState = {
                id: this._getNextStateId(),
                url: Util.toUrlObject(currentUrl),
                pageId: curPageId
            };

            theHistory.replaceState(curState, '', currentUrl);
            this._saveAsCurrentState(curState);
            this._incMaxStateId();
        }
    };

    /**
     * 切换到 url 指定的块或文档
     *
     * 如果 url 指向的是当前页面，那么认为是切换块；
     * 否则是切换文档
     *
     * @param {String} url url
     * @param {Boolean=} ignoreCache 是否强制请求不使用缓存，对 document 生效，默认是 false
     */
    Router.prototype.load = function (url, ignoreCache) {
        if (ignoreCache === undefined) {
            ignoreCache = false;
        }

        if (this._isTheSameDocument(location.href, url)) {
            this._switchToSection(Util.getUrlFragment(url));
        } else {
            this._saveDocumentIntoCache($(document), location.href);
            this._switchToDocument(url, ignoreCache);
        }
    };

    /**
     * 调用 history.forward()
     */
    Router.prototype.forward = function () {
        theHistory.forward();
    };

    /**
     * 调用 history.back()
     */
    Router.prototype.back = function () {
        theHistory.back();
    };

    /**
     * 切换显示当前文档另一个块
     *
     * 把新块从右边切入展示，同时会把新的块的记录用 history.pushState 来保存起来
     *
     * 如果已经是当前显示的块，那么不做任何处理；
     * 如果没对应的块，那么忽略。
     *
     * @param {String} sectionId 待切换显示的块的 id
     * @private
     */
    Router.prototype._switchToSection = function (sectionId) {
        if (!sectionId) {
            return;
        }

        var $curPage = this._getCurrentSection(),
            $newPage = $('#' + sectionId);

        // 如果已经是当前页，不做任何处理
        if ($curPage === $newPage) {
            return;
        }

        this._animateSection($curPage, $newPage, DIRECTION.rightToLeft);
        this._pushNewState('#' + sectionId, sectionId);
    };

    /**
     * 载入显示一个新的文档
     *
     * - 如果有缓存，那么直接利用缓存来切换
     * - 否则，先把页面加载过来缓存，然后再切换
     *      - 如果解析失败，那么用 location.href 的方式来跳转
     *
     * 注意：不能在这里以及其之后用 location.href 来 **读取** 切换前的页面的 url，
     *     因为如果是 popState 时的调用，那么此时 location 已经是 pop 出来的 state 的了
     *
     * @param {String} url 新的文档的 url
     * @param {Boolean=} ignoreCache 是否不使用缓存强制加载页面
     * @param {Boolean=} isPushState 是否需要 pushState
     * @param {String=} direction 新文档切入的方向
     * @private
     */
    Router.prototype._switchToDocument = function (url, ignoreCache, isPushState, direction) {
        var baseUrl = Util.toUrlObject(url).base;

        if (ignoreCache) {
            delete this.cache[baseUrl];
        }

        var cacheDocument = this.cache[baseUrl];
        var context = this;

        if (cacheDocument) {

            this._doSwitchDocument(url, isPushState, direction);


        } else {
            this._loadDocument(url, {
                success: function ($doc) {
                    try {
                        context._parseDocument(url, $doc);
                        context._doSwitchDocument(url, isPushState, direction);
                    } catch (e) {
                        location.href = url;
                    }
                },
                error: function () {
                    location.href = url;
                }
            });
        }
    };

    /**
     * 利用缓存来做具体的切换文档操作
     *
     * - 确定待切入的文档的默认展示 section
     * - 把新文档 append 到 view 中
     * - 动画切换文档
     * - 如果需要 pushState，那么把最新的状态 push 进去并把当前状态更新为该状态
     *
     * @param {String} url 待切换的文档的 url
     * @param {Boolean} isPushState 加载页面后是否需要 pushState，默认是 true
     * @param {String} direction 动画切换方向，默认是 DIRECTION.rightToLeft
     * @private
     */
    Router.prototype._doSwitchDocument = function (url, isPushState, direction) {
        if (typeof isPushState === 'undefined') {
            isPushState = true;
        }

        var urlObj = Util.toUrlObject(url);
        var $currentDoc = this.$view.find('.' + routerConfig.sectionGroupClass);
        var $newDoc = $($('<div></div>').append(this.cache[urlObj.base].$content).html());



        // 确定一个 document 展示 section 的顺序
        // 1. 与 hash 关联的 element
        // 2. 默认的标识为 current 的 element
        // 3. 第一个 section
        var $allSection = $newDoc.find('.' + routerConfig.pageClass);
        var $visibleSection = $newDoc.find('.' + routerConfig.curPageClass);
        var $hashSection;

        if (urlObj.fragment) {
            $hashSection = $newDoc.find('#' + urlObj.fragment);
        }
        if ($hashSection && $hashSection.length) {
            $visibleSection = $hashSection.eq(0);
        } else if (!$visibleSection.length) {
            $visibleSection = $allSection.eq(0);
        }
        if (!$visibleSection.attr('id')) {
            $visibleSection.attr('id', this._generateRandomId());
        }

        var $currentSection = this._getCurrentSection();
        $currentSection.trigger(EVENTS.beforePageSwitch, [$currentSection.attr('id'), $currentSection]);

        $allSection.removeClass(routerConfig.curPageClass);
        $visibleSection.addClass(routerConfig.curPageClass);

        // prepend 而不 append 的目的是避免 append 进去新的 document 在后面，
        // 其里面的默认展示的(.page-current) 的页面直接就覆盖了原显示的页面（因为都是 absolute）
        this.$view.prepend($newDoc);



        this._animateDocument($currentDoc, $newDoc, $visibleSection, direction);

        if (isPushState) {
            this._pushNewState(url, $visibleSection.attr('id'));
        }
    };

    /**
     * 判断两个 url 指向的页面是否是同一个
     *
     * 判断方式: 如果两个 url 的 base 形式（不带 hash 的绝对形式）相同，那么认为是同一个页面
     *
     * @param {String} url
     * @param {String} anotherUrl
     * @returns {Boolean}
     * @private
     */
    Router.prototype._isTheSameDocument = function (url, anotherUrl) {
        return Util.toUrlObject(url).base === Util.toUrlObject(anotherUrl).base;
    };

    /**
     * ajax 加载 url 指定的页面内容
     *
     * 加载过程中会发出以下事件
     *  pageLoadCancel: 如果前一个还没加载完,那么取消并发送该事件
     *  pageLoadStart: 开始加载
     *  pageLodComplete: ajax complete 完成
     *  pageLoadError: ajax 发生 error
     *
     *
     * @param {String} url url
     * @param {Object=} callback 回调函数配置，可选，可以配置 success\error 和 complete
     *      所有回调函数的 this 都是 null，各自实参如下：
     *      success: $doc, status, xhr
     *      error: xhr, status, err
     *      complete: xhr, status
     *
     * @private
     */
    Router.prototype._loadDocument = function (url, callback) {
        if (this.xhr && this.xhr.readyState < 4) {
            this.xhr.onreadystatechange = function () {
            };
            this.xhr.abort();
            this.dispatch(EVENTS.pageLoadCancel);
        }

        this.dispatch(EVENTS.pageLoadStart);

        callback = callback || {};
        var self = this;

        this.xhr = $.ajax({
            url: url,
            success: $.proxy(function (data, status, xhr) {
                // 给包一层 <html/>，从而可以拿到完整的结构
                var $doc = $('<html></html>');
                $doc.append(data);
                callback.success && callback.success.call(null, $doc, status, xhr);
            }, this),
            error: function (xhr, status, err) {
                callback.error && callback.error.call(null, xhr, status, err);
                self.dispatch(EVENTS.pageLoadError);
            },
            complete: function (xhr, status) {
                callback.complete && callback.complete.call(null, xhr, status);
                self.dispatch(EVENTS.pageLoadComplete);
            }
        });
    };

    /**
     * 对于 ajax 加载进来的页面，把其缓存起来
     *
     * @param {String} url url
     * @param $doc ajax 载入的页面的 jq 对象，可以看做是该页面的 $(document)
     * @private
     */
    Router.prototype._parseDocument = function (url, $doc) {
        var $innerView = $doc.find('.' + routerConfig.sectionGroupClass);

        if (!$innerView.length) {
            throw new Error('missing router view mark: ' + routerConfig.sectionGroupClass);
        }

        this._saveDocumentIntoCache($doc, url);
    };

    /**
     * 把一个页面的相关信息保存到 this.cache 中
     *
     * 以页面的 baseUrl 为 key,而 value 则是一个 DocumentCache
     *
     * @param {*} doc doc
     * @param {String} url url
     * @private
     */
    Router.prototype._saveDocumentIntoCache = function (doc, url) {
        var urlAsKey = Util.toUrlObject(url).base;
        var $doc = $(doc);

        this.cache[urlAsKey] = {
            $doc: $doc,
            $title: $('title').text(),
            $content: $doc.find('.' + routerConfig.sectionGroupClass)
        };
    };

    /**
     * 从 sessionStorage 中获取保存下来的「当前状态」
     *
     * 如果解析失败，那么认为当前状态是 null
     *
     * @returns {State|null}
     * @private
     */
    Router.prototype._getLastState = function () {
        var currentState = sessionStorage.getItem(this.sessionNames.currentState);
        try {
            currentState = JSON.parse(currentState);
        } catch (e) {
            currentState = null;
        }

        return currentState;
    };

    /**
     * 把一个状态设为当前状态，保存仅 sessionStorage 中
     *
     * @param {State} state
     * @private
     */
    Router.prototype._saveAsCurrentState = function (state) {
        sessionStorage.setItem(this.sessionNames.currentState, JSON.stringify(state));
    };

    /**
     * 获取下一个 state 的 id
     *
     * 读取 sessionStorage 里的最后的状态的 id，然后 + 1；如果原没设置，那么返回 1
     *
     * @returns {number}
     * @private
     */
    Router.prototype._getNextStateId = function () {
        var maxStateId = sessionStorage.getItem(this.sessionNames.maxStateId);
        return maxStateId ? parseInt(maxStateId, 10) + 1 : 1;
    };

    /**
     * 把 sessionStorage 里的最后状态的 id 自加 1
     *
     * @private
     */
    Router.prototype._incMaxStateId = function () {
        sessionStorage.setItem(this.sessionNames.maxStateId, this._getNextStateId());
    };

    /**
     * 从一个文档切换为显示另一个文档
     *
     * @param $from 目前显示的文档
     * @param $to 待切换显示的新文档
     * @param $visibleSection 新文档中展示的 section 元素
     * @param direction 新文档切入方向
     * @private
     */
    Router.prototype._animateDocument = function ($from, $to, $visibleSection, direction) {
        var sectionId = $visibleSection.attr('id');


        var $visibleSectionInFrom = $from.find('.' + routerConfig.curPageClass);
        $visibleSectionInFrom.addClass(routerConfig.visiblePageClass).removeClass(routerConfig.curPageClass);

        $visibleSection.trigger(EVENTS.pageAnimationStart, [sectionId, $visibleSection]);

        this._animateElement($from, $to, direction);
        $from.animationEnd(function () {
            $visibleSectionInFrom.removeClass(routerConfig.visiblePageClass);
            // 移除 document 前后，发送 beforePageRemove 和 pageRemoved 事件
            $(window).trigger(EVENTS.beforePageRemove, [$from]);
            $from.remove();
            $(window).trigger(EVENTS.pageRemoved);
        });
        $to.animationEnd(function () {
            $visibleSection.trigger(EVENTS.pageAnimationEnd, [sectionId, $visibleSection]);
            // 外层（init.js）中会绑定 pageInitInternal 事件，然后对页面进行初始化
            $visibleSection.trigger(EVENTS.pageInit, [sectionId, $visibleSection]);
        });
    };

    /**
     * 把当前文档的展示 section 从一个 section 切换到另一个 section
     *
     * @param $from
     * @param $to
     * @param direction
     * @private
     */
    Router.prototype._animateSection = function ($from, $to, direction) {
        var toId = $to.attr('id');
        $from.trigger(EVENTS.beforePageSwitch, [$from.attr('id'), $from]);

        $from.removeClass(routerConfig.curPageClass);
        $to.addClass(routerConfig.curPageClass);
        $to.trigger(EVENTS.pageAnimationStart, [toId, $to]);
        this._animateElement($from, $to, direction);
        $to.animationEnd(function () {
            $to.trigger(EVENTS.pageAnimationEnd, [toId, $to]);
            // 外层（init.js）中会绑定 pageInitInternal 事件，然后对页面进行初始化
            $to.trigger(EVENTS.pageInit, [toId, $to]);
        });
    };

    /**
     * 切换显示两个元素
     *
     * 切换是通过更新 class 来实现的，而具体的切换动画则是 class 关联的 css 来实现
     *
     * @param $from 当前显示的元素
     * @param $to 待显示的元素
     * @param direction 切换的方向
     * @private
     */
    Router.prototype._animateElement = function ($from, $to, direction) {
        // todo: 可考虑如果入参不指定，那么尝试读取 $to 的属性，再没有再使用默认的
        // 考虑读取点击的链接上指定的方向
        if (typeof direction === 'undefined') {
            direction = DIRECTION.rightToLeft;
        }

        var animPageClasses = [
            'fui-page-from-center-to-left',
            'fui-page-from-center-to-right',
            'fui-page-from-right-to-center',
            'fui-page-from-left-to-center'].join(' ');

        var classForFrom, classForTo;
        switch (direction) {
            case DIRECTION.rightToLeft:
                classForFrom = 'fui-page-from-center-to-left';
                classForTo = 'fui-page-from-right-to-center';
                break;
            case DIRECTION.leftToRight:
                classForFrom = 'fui-page-from-center-to-right';
                classForTo = 'fui-page-from-left-to-center';
                break;
            default:
                classForFrom = 'fui-page-from-center-to-left';
                classForTo = 'fui-page-from-right-to-center';
                break;
        }

        $from.removeClass(animPageClasses).addClass(classForFrom);
        $to.removeClass(animPageClasses).addClass(classForTo);

        $from.animationEnd(function () {
            $from.removeClass(animPageClasses);
        });
        $to.animationEnd(function () {
            $to.removeClass(animPageClasses);
        });
    };

    /**
     * 获取当前显示的第一个 section
     *
     * @returns {*}
     * @private
     */
    Router.prototype._getCurrentSection = function () {
        return this.$view.find('.' + routerConfig.curPageClass).eq(0);
    };

    /**
     * popState 事件关联着的后退处理
     *
     * 判断两个 state 判断是否是属于同一个文档，然后做对应的 section 或文档切换；
     * 同时在切换后把新 state 设为当前 state
     *
     * @param {State} state 新 state
     * @param {State} fromState 旧 state
     * @private
     */
    Router.prototype._back = function (state, fromState) {
        if (this._isTheSameDocument(state.url.full, fromState.url.full)) {
            var $newPage = $('#' + state.pageId);
            if ($newPage.length) {
                var $currentPage = this._getCurrentSection();
//                       if ($.device.isWeixin) {
//                    var $body = $('body');
//                    document.title = title;
//                    var $iframe = $('<iframe src="/favicon.ico"></iframe>');
//                    $iframe.off('load').on('load', function () {
//                        setTimeout(function () {
//
//                            $iframe.off('load').remove();
//                        }, 0);
//                    }).appendTo($body);
//                } else {
//                     document.title =title;
//                }


                this._animateSection($currentPage, $newPage, DIRECTION.leftToRight);
                this._saveAsCurrentState(state);
            } else {
                location.href = state.url.full;
            }
        } else {

            this._saveDocumentIntoCache($(document), fromState.url.full);
            this._switchToDocument(state.url.full, false, false, DIRECTION.leftToRight);
            this._saveAsCurrentState(state);
        }
    };

    /**
     * popState 事件关联着的前进处理,类似于 _back，不同的是切换方向
     *
     * @param {State} state 新 state
     * @param {State} fromState 旧 state
     * @private
     */
    Router.prototype._forward = function (state, fromState) {
        if (this._isTheSameDocument(state.url.full, fromState.url.full)) {
            var $newPage = $('#' + state.pageId);
            if ($newPage.length) {
                var $currentPage = this._getCurrentSection();
                this._animateSection($currentPage, $newPage, DIRECTION.rightToLeft);
                this._saveAsCurrentState(state);
            } else {
                location.href = state.url.full;
            }
        } else {
            this._saveDocumentIntoCache($(document), fromState.url.full);
            this._switchToDocument(state.url.full, false, false, DIRECTION.rightToLeft);
            this._saveAsCurrentState(state);
        }
    };

    /**
     * popState 事件处理
     *
     * 根据 pop 出来的 state 和当前 state 来判断是前进还是后退
     *
     * @param event
     * @private
     */
    Router.prototype._onPopState = function (event) {
        var state = event.state;
        // if not a valid state, do nothing
        if (!state || !state.pageId) {
            return;
        }

        var lastState = this._getLastState();

        if (!lastState) {
            console.error && console.error('Missing last state when backward or forward');
            return;
        }

        if (state.id === lastState.id) {
            return;
        }

        if (state.id < lastState.id) {
            this._back(state, lastState);
        } else {
            this._forward(state, lastState);
        }
    };

    /**
     * 页面进入到一个新状态
     *
     * 把新状态 push 进去，设置为当前的状态，然后把 maxState 的 id +1。
     *
     * @param {String} url 新状态的 url
     * @param {String} sectionId 新状态中显示的 section 元素的 id
     * @private
     */
    Router.prototype._pushNewState = function (url, sectionId) {
        var state = {
            id: this._getNextStateId(),
            pageId: sectionId,
            url: Util.toUrlObject(url)
        };

        theHistory.pushState(state, '', url);
        this._saveAsCurrentState(state);
        this._incMaxStateId();
    };

    /**
     * 生成一个随机的 id
     *
     * @returns {string}
     * @private
     */
    Router.prototype._generateRandomId = function () {
        return "fui-page-" + (+new Date());
    };

    Router.prototype.dispatch = function (event) {
        var e = new CustomEvent(event, {
            bubbles: true,
            cancelable: true
        });

        //noinspection JSUnresolvedFunction
        window.dispatchEvent(e);
    };

    /**
     * 判断一个链接是否使用 router 来处理
     *
     * @param $link
     * @returns {boolean}
     */
    function isInRouterBlackList($link) {

        var classBlackList = [
            'external',
            'tab-link',
            'open-popup',
            'close-popup',
            'open-panel',
            'close-panel'
        ];

        for (var i = classBlackList.length - 1; i >= 0; i--) {
            if ($link.hasClass(classBlackList[i])) {
                return true;
            }
        }

        var linkEle = $link.get(0);
        var linkHref = linkEle.getAttribute('href');

        var protoWhiteList = [
            'http',
            'https'
        ];

        //如果非noscheme形式的链接，且协议不是http(s)，那么路由不会处理这类链接
        if (/^(\w+):/.test(linkHref) && protoWhiteList.indexOf(RegExp.$1) < 0) {
            return true;
        }

        //noinspection RedundantIfStatementJS
        if (linkEle.hasAttribute('external')) {
            return true;
        }

        return false;
    }

    /**
     * 自定义是否执行路由功能的过滤器
     *
     * 可以在外部定义 $.config.routerFilter 函数，实参是点击链接的 Zepto 对象。
     *
     * @param $link 当前点击的链接的 Zepto 对象
     * @returns {boolean} 返回 true 表示执行路由功能，否则不做路由处理
     */
    function customClickFilter($link) {
        var customRouterFilter = FoxUI.defaults.routerFilter;
        if ($.isFunction(customRouterFilter)) {
            var filterResult = customRouterFilter($link);
            if (typeof filterResult === 'boolean') {
                return filterResult;
            }
        }

        return true;
    }


    $(function () {
        // 用户可选关闭router功能
        if (!FoxUI.defaults.router) {
            return;
        }

        if (!Util.supportStorage()) {
            return;
        }

        var $pages = $('.' + routerConfig.pageClass);
        if (!$pages.length) {
            var warnMsg = 'Disable Router  Because Of no .fui-page Elements';
            if (window.console && window.console.warn) {
                console.warn(warnMsg);
            }
            return;
        }


/*
        var userAgent = navigator.userAgent;
        if (userAgent.indexOf('CK 2.0') <= -1){
            $(document).on('click', 'a', function (e) {
                var $target = $(e.currentTarget);
                if ($target.hasClass('back')) {
                    theHistory.back();
                }
            })
        }*/
            FoxUI.router = $.router = new Router();
            $(document).on('click', 'a', function (e) {
                var $target = $(e.currentTarget);

                var filterResult = customClickFilter($target);
                if (!filterResult) {
                    return;
                }

                if (isInRouterBlackList($target)) {
                    return;
                }

                e.preventDefault();

                if ($target.hasClass('back')) {
                    FoxUI.router.back();
                } else {

                    var url = $target.attr('href');
                    if (!url || url === '#') {
                        return;
                    }

                    var ignoreCache = $target.attr('data-nocache') === 'true';

                    FoxUI.router.load(url, ignoreCache);
                }
            });


    });

    var getPage = function () {
        var $page = $(".fui-page-current");

        if (!$page[0]) {
            $page = $(".fui-page").addClass('fui-page-current');

        }

        return $page;
    };

    //初始化页面中的JS组件
    FoxUI.initPage = function (page) {
        var $page = getPage();
        if (!$page[0])
            $page = $(document.body);
        var $content = $page.hasClass('fui-content') ?
            $page :
            $page.find('.fui-content');

        //according
        FoxUI.according.init();

        //swipe
        $('.fui-swipe').swipe();

        //搜索
        FoxUI.searchbar.init();

        //懒加载
        
        
        
        $('.fui-content').lazyload();
        
        /*
        
        if( typeof(h5app)==="undefined"){
            h5app.initHN();
        }*/

    };

    if (FoxUI.defaults.routerShowLoading) {

        //这里的 以 push 开头的是私有事件，不要用
        $(window).on('pageLoadStart', function () {
            FoxUI.loader.show('mini');
        });
        $(window).on('pageAnimationStart', function () {
            FoxUI.loader.hide();
        });
        $(window).on('pageLoadCancel', function () {
            FoxUI.loader.hide();
        });
        $(window).on('pageLoadComplete', function () {

            FoxUI.loader.hide();
        });
        $(window).on('pageLoadError', function () {
            FoxUI.loader.hide();
        });
    }

    window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
            location.reload();
        }
    });

    //滚动条位置
    $.lastScrollPosition = function (options) {
        if (!sessionStorage) {
            return;
        }
        // 需要记忆模块的className
        var needMemoryClass = options.needMemoryClass || [];
        $(window).on('beforePageSwitch', function (event, id, arg) {
            updateMemory(id, arg);
        });
        $(window).on('pageAnimationStart', function (event, id, arg) {
            getMemory(id, arg);
        });
        //让后退页面回到之前的高度
        function getMemory(id, arg) {
            needMemoryClass.forEach(function (item, index) {
                if ($(item).length === 0) {
                    return;
                }
                var positionName = id;
                // 遍历对应节点设置存储的高度
                var memoryHeight = sessionStorage.getItem(positionName);
                arg.find(item).scrollTop(parseInt(memoryHeight));

            });
        }
        //记住即将离开的页面的高度
        function updateMemory(id, arg) {
            var positionName = id;
            // 存储需要记忆模块的高度
            needMemoryClass.forEach(function (item, index) {
                if ($(item).length === 0) {
                    return;
                }
                sessionStorage.setItem(
                    positionName,
                    arg.find(item).scrollTop()
                );

            });
        }
    };

    $(window).on('pageAnimationStart', function (event, id, page) {
        //关闭modal
        $.closeModal();
    });

    $(document).on($.touchEvents.move, ".fui-header", function (e) {
        e.preventDefault();
    });


    $(window).on('pageInit', function () {
        FoxUI.loader.hide();
        $.lastScrollPosition({
            needMemoryClass: [
                '.fui-content'
            ]
        });
    });

    FoxUI.init = function () {
        var $page = getPage();
        var id = $page[0].id;
        $page.trigger('pageInit', [id, $page]);
        FoxUI.initPage();
    };
    $(function () {
        //直接绑定
        FastClick.attach(document.body);

        FoxUI.init();

        $(document).on('pageInitInternal', function (e, id, page) {
            FoxUI.init();
        });
    });

    if (typeof define === 'function' && define.amd) {
        define(['jquery'], function () {
            return FoxUI;
        });

    } else if (typeof module !== "undefined" && module !== null) {
        module.exports = FoxUI;

    } else {
        window.FoxUI = FoxUI;
    }
})();