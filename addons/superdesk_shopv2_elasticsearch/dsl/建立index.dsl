PUT http://127.0.0.1:9200/db_super_desk_v20180408
{
    "mappings": {
        "ims_superdesk_shop_goods": {
            "properties": {
                "@timestamp": {
                    "type": "date"
                },
                "@version": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "allcates": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "artid": {
                    "type": "long"
                },
                "autoreceive": {
                    "type": "long"
                },
                "bargain": {
                    "type": "long"
                },
                "buyagain": {
                    "type": "long"
                },
                "buyagain_commission": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "buyagain_condition": {
                    "type": "integer"
                },
                "buyagain_islong": {
                    "type": "integer"
                },
                "buyagain_sale": {
                    "type": "integer"
                },
                "buycontent": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "buygroups": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "buylevels": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "buyshow": {
                    "type": "integer"
                },
                "cannotrefund": {
                    "type": "long"
                },
                "cash": {
                    "type": "long"
                },
                "catch_id": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "catch_source": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "catch_url": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "cates": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "catesinit3": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "ccate": {
                    "type": "long"
                },
                "ccates": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "checked": {
                    "type": "long"
                },
                "city": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "commission": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "commission1_pay": {
                    "type": "long"
                },
                "commission1_rate": {
                    "type": "long"
                },
                "commission2_pay": {
                    "type": "long"
                },
                "commission2_rate": {
                    "type": "long"
                },
                "commission3_pay": {
                    "type": "long"
                },
                "commission3_rate": {
                    "type": "long"
                },
                "commission_thumb": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "content": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "costprice": {
                    "type": "long"
                },
                "createtime": {
                    "type": "long"
                },
                "credit": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "deduct": {
                    "type": "long"
                },
                "deduct2": {
                    "type": "float"
                },
                "deleted": {
                    "type": "long"
                },
                "description": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "detail_btntext1": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "detail_btntext2": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "detail_btnurl1": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "detail_btnurl2": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "detail_logo": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "detail_shopname": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "detail_totaltitle": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "discounts": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "dispatch": {
                    "type": "long"
                },
                "dispatchid": {
                    "type": "long"
                },
                "dispatchprice": {
                    "type": "long"
                },
                "dispatchtype": {
                    "type": "integer"
                },
                "displayorder": {
                    "type": "long"
                },
                "diyfields": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "diyformid": {
                    "type": "long"
                },
                "diyformtype": {
                    "type": "integer"
                },
                "diymode": {
                    "type": "integer"
                },
                "diypage": {
                    "type": "long"
                },
                "diysave": {
                    "type": "integer"
                },
                "diysaveid": {
                    "type": "long"
                },
                "edareas": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "edmoney": {
                    "type": "long"
                },
                "ednum": {
                    "type": "long"
                },
                "followtip": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "followurl": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "goodssn": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "groupstype": {
                    "type": "integer"
                },
                "hascommission": {
                    "type": "long"
                },
                "hasoption": {
                    "type": "long"
                },
                "hidecommission": {
                    "type": "long"
                },
                "id": {
                    "type": "long"
                },
                "invoice": {
                    "type": "long"
                },
                "iscomment": {
                    "type": "integer"
                },
                "isdiscount": {
                    "type": "integer"
                },
                "isdiscount_discounts": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "isdiscount_time": {
                    "type": "long"
                },
                "isdiscount_title": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "ishot": {
                    "type": "integer"
                },
                "isnew": {
                    "type": "integer"
                },
                "isnodiscount": {
                    "type": "integer"
                },
                "isrecommand": {
                    "type": "integer"
                },
                "issendfree": {
                    "type": "integer"
                },
                "istime": {
                    "type": "integer"
                },
                "isverify": {
                    "type": "integer"
                },
                "jd_vop_page_num": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "jd_vop_sku": {
                    "type": "long"
                },
                "keywords": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "labelname": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "manydeduct": {
                    "type": "integer"
                },
                "marketprice": {
                    "type": "float"
                },
                "maxbuy": {
                    "type": "long"
                },
                "maxprice": {
                    "type": "float"
                },
                "merchid": {
                    "type": "long"
                },
                "merchsale": {
                    "type": "integer"
                },
                "minbuy": {
                    "type": "long"
                },
                "minprice": {
                    "type": "float"
                },
                "minpriceupdated": {
                    "type": "integer"
                },
                "money": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "needfollow": {
                    "type": "long"
                },
                "nocommission": {
                    "type": "long"
                },
                "noticeopenid": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "noticetype": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "old_shop_goods_id": {
                    "type": "long"
                },
                "originalprice": {
                    "type": "long"
                },
                "pcate": {
                    "type": "long"
                },
                "pcates": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "productprice": {
                    "type": "float"
                },
                "productsn": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "province": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "quality": {
                    "type": "long"
                },
                "repair": {
                    "type": "long"
                },
                "sales": {
                    "type": "long"
                },
                "salesreal": {
                    "type": "long"
                },
                "score": {
                    "type": "long"
                },
                "seven": {
                    "type": "long"
                },
                "share_icon": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "share_title": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "sharebtn": {
                    "type": "integer"
                },
                "shopid": {
                    "type": "long"
                },
                "shorttitle": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "showgroups": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "showlevels": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "showtotal": {
                    "type": "integer"
                },
                "showtotaladd": {
                    "type": "integer"
                },
                "spec": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "status": {
                    "type": "integer"
                },
                "storeids": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "subtitle": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "synctime_jd_vop_price": {
                    "type": "long"
                },
                "taobaoid": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "taobaourl": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "taotaoid": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "tcate": {
                    "type": "long"
                },
                "tcates": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "thumb": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "thumb_first": {
                    "type": "long"
                },
                "thumb_url": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "timeend": {
                    "type": "long"
                },
                "timestart": {
                    "type": "long"
                },
                "title": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "text",
                            "index":"true",
                            "boost":"5",
                            "analyzer":"ik_max_word",
                            "search_analyzer":"ik_max_word"
                        }
                    }
                },
                "total": {
                    "type": "long"
                },
                "totalcnf": {
                    "type": "long"
                },
                "type": {
                    "type": "long"
                },
                "uniacid": {
                    "type": "long"
                },
                "unit": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "updatetime": {
                    "type": "long"
                },
                "usermaxbuy": {
                    "type": "long"
                },
                "verifytype": {
                    "type": "integer"
                },
                "viewcount": {
                    "type": "long"
                },
                "virtual": {
                    "type": "long"
                },
                "virtualsend": {
                    "type": "integer"
                },
                "virtualsendcontent": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword",
                            "ignore_above": 256
                        }
                    }
                },
                "weight": {
                    "type": "long"
                }
            }
        }
    }
}

{
    "acknowledged": true,
    "shards_acknowledged": true,
    "index": "db_super_desk"
}