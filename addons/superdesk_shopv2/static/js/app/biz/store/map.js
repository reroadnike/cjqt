define(['core', 'tpl'],
    function(core, tpl) {
        var modal = {
            store: false
        };
        modal.init = function(params) {
            modal.store = params.store;
            FoxUI.loader.show('mini');
            $('#js-map').height($(document.body).height() - $('.fui-header').height() - $('.fui-footer .fui-list:first-child').height() - 20 + 'px');
            var map = new BMap.Map("js-map");
            map.centerAndZoom(new BMap.Point(modal.store.lng, modal.store.lat), 19);
            var marker = new BMap.Marker(new BMap.Point(modal.store.lng, modal.store.lat));
            map.addOverlay(marker);
            var popHTML = '<div class="info-window"><div class="address">' + modal.store.address + '</div><div class="navi"><a class="tag">到这里去</a><div class="js-navi-to navi-to"></div></div></div>';
            var infoWindow = new BMap.InfoWindow(popHTML, {
                title: modal.store.storename,
                width: 220,
                height: 80,
                offset: {
                    width: 0,
                    height: 15
                }
            });
            infoWindow.addEventListener("open",
                function(e) {
                    $('.js-navi-to').click(function() {
                        window.location.href = 'http://map.baidu.com/mobile/webapp/search/search/qt=s&wd=' + modal.store.address + '/?third_party=uri_api'
                    })
                });
            marker.openInfoWindow(infoWindow);
            marker.addEventListener("click",
                function(e) {
                    marker.openInfoWindow(infoWindow)
                });
            $('.fui-footer').css('visibility', 'visible');
            FoxUI.loader.hide()
        };
        return modal
    });