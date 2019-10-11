/**
 * jquery.jd_vop_area_cascade 1.0
 */
;(function (factory) {
    if (typeof define === "function" && (define.amd || define.cmd) && !jQuery) {
        // AMD或CMD
        define(["jquery"], factory);
    } else if (typeof module === 'object' && module.exports) {
        // Node/CommonJS
        module.exports = function (root, jQuery) {
            if (jQuery === undefined) {
                if (typeof window !== 'undefined') {
                    jQuery = require('jquery');
                } else {
                    jQuery = require('jquery')(root);
                }
            }
            factory(jQuery);
            return jQuery;
        };
    } else {
        //Browser globals
        factory(jQuery);
    }
}(function ($) {
    $.support.cors = true;

    $.fn.citys = function (parameter, getApi) {

        if (typeof parameter == 'function') { //重载
            getApi = parameter;
            parameter = {};
        } else {
            parameter = parameter || {};
            getApi = getApi || function () {
                };
        }

        var defaults = {
            dataUrl : '',     //数据库地址
            dataType: 'json',          //数据库类型:'json'或'jsonp'

            provinceField: 'province',      //省份字段名
            cityField    : 'city',          //城市字段名
            areaField    : 'area',          //地区字段名
            townField    : 'town',
            valueType    : 'code',          //下拉框值的类型,code行政区划代码,name地名
            code         : 0,               //地区编码

            province: 0,               //省份,可以为地区编码或者名称
            city    : 0,               //城市,可以为地区编码或者名称
            area    : 0,               //地区,可以为地区编码或者名称
            town    : 0,

            required: true,            //是否必须选一个
            nodata  : 'hidden',        //当无数据时的表现形式:'hidden'隐藏,'disabled'禁用,为空不做任何处理

            onChange: function () {
            }   //地区切换时触发,回调函数传入地区数据
        };

        var options = $.extend({}, defaults, parameter);

        console.log(options.dataUrl);

        return this.each(function () {

            // console.log('只执行一次');
            //对象定义
            var _api = {};

            var $this = $(this);

            var $province = $this.find('select[name="' + options.provinceField + '"]'),
                $city = $this.find('select[name="' + options.cityField + '"]'),
                $area = $this.find('select[name="' + options.areaField + '"]'),
                $town = $this.find('select[name="' + options.townField + '"]');

            // console.log(data);

            var province = {}, city = {}, area = {}, hasCity = false;       //判断是非有地级城市

            // if (options.code) {   //如果设置地区编码，则忽略单独设置的信息
            //
            //     console.log(options.code);
            //
            //     var c = options.code - options.code % 1e4;
            //     if (data[c]) {
            //         options.province = c;
            //     }
            //     c = options.code - (options.code % 1e4 ? options.code % 1e2 : options.code);
            //     if (data[c]) {
            //         options.city = c;
            //     }
            //     c = options.code % 1e2 ? options.code : 0;
            //     if (data[c]) {
            //         options.area = c;
            //     }
            // }

            var loadData = function (parent_code, callback, andThen) {
                $.ajax({
                    url          : options.dataUrl + '&parent_code=' + parent_code,
                    type         : 'GET',
                    crossDomain  : true,
                    dataType     : options.dataType,
                    jsonpCallback: 'jsonp_location',
                    success      : function (data) {
                        // callback is function 判定
                        if (typeof callback == 'function') {
                            callback(data);
                        }
                    }
                }).then(function () {
                    // andThen is function 判定
                    if (typeof andThen == 'function') {
                        andThen();
                    }

                });
            };

            // var updateData = function () {
            //
            //
            //
            //
            //     $.each(data, function (index, element) {
            //
            //         var that = this;// {code: "4", text: "重庆"}
            //
            //         console.log(that);
            //
            //         province[that['code']] = that['text'];
            //
            //     });
            //
            //
            // };

            var format = {

                province: function () {

                    // console.log('action format.province(0)');

                    $province.hide().empty();
                    province = {};

                    if (!options.required) {
                        $province.append('<option value=""> - 请选择 - </option>');
                    }

                    loadData(0, function (data) {
                        $.each(data, function (index, element) {
                            var that = this;// {code: "4", text: "重庆"}
                            // console.log(that);
                            province[that['code']] = that['text'];
                        });

                        for (var i in province) {
                            $province.append('<option value="' + (options.valueType == 'code' ? i : province[i]) + '" data-code="' + i + '">' + province[i] + '</option>');
                        }

                        if (options.province == 0) {
                            options.province = $province.find('option:first').data('code') || 0; //选中节点的区划代码
                        }

                    }, function () {
                        if (options.province) {
                            var value = options.valueType == 'code' ? options.province : province[options.province];
                            $province.val(value);
                        }

                        if (_api.sizeof(province) > 0) {
                            $province.show();
                            format.city();

                        }
                        options.onChange(_api.getInfo());


                    });


                },

                city: function () {

                    // console.log('action format.city(province=' + options.province + ')');

                    $city.hide().empty();
                    city = {};

                    // if (!hasCity) {
                    //     $city.css('display', 'none');
                    // } else {
                    //     $city.css('display', '');
                    // }

                    if (!options.required) {
                        $city.append('<option value=""> - 请选择 - </option>');
                    }

                    // if (options.nodata == 'disabled') {
                    //     $city.prop('disabled', $.isEmptyObject(city));
                    // } else if (options.nodata == 'hidden') {
                    //     $city.css('display', $.isEmptyObject(city) ? 'none' : '');
                    // }



                    loadData(options.province, function (data) {


                        $.each(data, function (index, element) {
                            var that = this;// {code: "4", text: "重庆"}
                            // console.log(that);
                            city[that['code']] = that['text'];
                        });

                        // console.log(JSON.stringify(city));
                        for (var i in city) {
                            $city.append('<option value="' + (options.valueType == 'code' ? i : city[i]) + '" data-code="' + i + '">' + city[i] + '</option>');
                        }

                        if (options.city == 0) {
                            options.city = $city.find('option:first').data('code') || 0; //选中节点的区划代码
                        }


                    }, function () {


                        if (options.city) {
                            var value = options.valueType == 'code' ? options.city : city[options.city];
                            $city.val(value);
                        }
                        // else if (options.area) {
                        //     var value = options.valueType == 'code' ? options.area : city[options.area];
                        //     $city.val(value);
                        // }

                        if (_api.sizeof(city) > 0) {
                            $city.show();
                            format.area();
                        }

                        options.onChange(_api.getInfo());


                    });
                },

                area: function () {

                    // console.log('action format.area(city=' + options.city + ')');

                    $area.hide().empty();
                    area = {};

                    if (!options.required) {
                        $area.append('<option value=""> - 请选择 - </option>');
                    }

                    // if (options.nodata == 'disabled') {
                    //     $area.prop('disabled', $.isEmptyObject(area));
                    // } else if (options.nodata == 'hidden') {
                    //     $area.css('display', $.isEmptyObject(area) ? 'none' : '');
                    // }

                    loadData(options.city, function (data) {
                        $.each(data, function (index, element) {
                            var that = this;// {code: "4", text: "重庆"}
                            // console.log(that);
                            area[that['code']] = that['text'];
                        });

                        // console.log(JSON.stringify(area));
                        for (var i in area) {
                            $area.append('<option value="' + (options.valueType == 'code' ? i : area[i]) + '" data-code="' + i + '">' + area[i] + '</option>');
                        }

                        if (options.area == 0) {
                            options.area = $area.find('option:first').data('code') || 0; //选中节点的区划代码
                        }

                    }, function () {

                        // console.log(JSON.stringify(_api.getInfo()));

                        if (options.area) {
                            var value = options.valueType == 'code' ? options.area : area[options.area];
                            $area.val(value);
                        }

                        if (_api.sizeof(area) > 0) {
                            $area.show();
                            format.town();
                        }

                        options.onChange(_api.getInfo());

                    });

                },

                town: function () {

                    // console.log('action format.town(area=' + options.area + ')');

                    $town.hide().empty();
                    town = {};

                    loadData(options.area, function (data) {
                        $.each(data, function (index, element) {
                            var that = this;
                            town[that['code']] = that['text'];
                        });

                        // console.log(JSON.stringify(town));
                        for (var i in town) {
                            $town.append('<option value="' + (options.valueType == 'code' ? i : town[i]) + '" data-code="' + i + '">' + town[i] + '</option>');
                        }

                        if (options.town == 0) {
                            options.town = $town.find('option:first').data('code') || 0; //选中节点的区划代码
                        }

                    }, function () {

                        if (options.town) {
                            var value = options.valueType == 'code' ? options.town : town[options.town];
                            $town.val(value);
                        }

                        if (_api.sizeof(town) > 0) {
                            $town.show();
                        }

                        options.onChange(_api.getInfo());

                    });
                }
            };

            _api.sizeof = function (obj) {
                var count = 0;
                for (var i in obj) {
                    if (obj.hasOwnProperty(i)) {
                        count++;
                    }
                }
                // console.log('call by _api.sizeof' + JSON.stringify(_api.getInfo()));
                return count;
            };

            //获取当前地理信息
            _api.getInfo = function () {
                var status = {
                    province: options.province || 0,
                    city    : options.city || 0,
                    area    : options.area || 0,
                    town    : options.town || 0
                    // code    : options.area || options.city || options.province
                };
                return status;
            };

            //事件绑定
            $province.on('change', function () {
                options.province = $(this).find('option:selected').data('code') || 0; //选中节点的区划代码
                options.city = 0;
                options.area = 0;
                options.town = 0;

                format.city();
                options.onChange(_api.getInfo());


            });
            $city.on('change', function () {
                options.city = $(this).find('option:selected').data('code') || 0; //选中节点的区划代码
                options.area = 0;
                options.town = 0;

                format.area();
                options.onChange(_api.getInfo());


            });
            $area.on('change', function () {
                options.area = $(this).find('option:selected').data('code') || 0; //选中节点的区划代码
                options.town = 0;

                format.town();
                options.onChange(_api.getInfo());


            });
            $town.on('change', function () {
                options.town = $(this).find('option:selected').data('code') || 0; //选中节点的区划代码

                options.onChange(_api.getInfo());

            });

            //初始化
            // updateData();

            format.province();

            // if (options.code) {
            //     options.onChange(_api.getInfo());
            // }

            // 外部 function call
            getApi(_api);


        });
    };
}));