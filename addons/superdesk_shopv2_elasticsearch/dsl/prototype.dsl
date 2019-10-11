"db_super_desk" : {
    "mappings" : {
      "ims_superdesk_shop_goods" : {
        "properties" : {
          "@timestamp" : {
            "type" : "date"
          },
          "@version" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "allcates" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "artid" : {
            "type" : "long"
          },
          "autoreceive" : {
            "type" : "long"
          },
          "bargain" : {
            "type" : "long"
          },
          "buyagain" : {
            "type" : "long"
          },
          "buyagain_commission" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "buyagain_condition" : {
            "type" : "boolean"
          },
          "buyagain_islong" : {
            "type" : "boolean"
          },
          "buyagain_sale" : {
            "type" : "boolean"
          },
          "buycontent" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "buygroups" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "buylevels" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "buyshow" : {
            "type" : "boolean"
          },
          "cannotrefund" : {
            "type" : "long"
          },
          "cash" : {
            "type" : "long"
          },
          "catch_id" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "catch_source" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "catch_url" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "cates" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "catesinit3" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "ccate" : {
            "type" : "long"
          },
          "ccates" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "checked" : {
            "type" : "long"
          },
          "city" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "commission" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "commission1_pay" : {
            "type" : "long"
          },
          "commission1_rate" : {
            "type" : "long"
          },
          "commission2_pay" : {
            "type" : "long"
          },
          "commission2_rate" : {
            "type" : "long"
          },
          "commission3_pay" : {
            "type" : "long"
          },
          "commission3_rate" : {
            "type" : "long"
          },
          "commission_thumb" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "content" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "costprice" : {
            "type" : "long"
          },
          "createtime" : {
            "type" : "long"
          },
          "credit" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "deduct" : {
            "type" : "long"
          },
          "deduct2" : {
            "type" : "float"
          },
          "deleted" : {
            "type" : "long"
          },
          "description" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "detail_btntext1" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "detail_btntext2" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "detail_btnurl1" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "detail_btnurl2" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "detail_logo" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "detail_shopname" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "detail_totaltitle" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "discounts" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "dispatch" : {
            "type" : "long"
          },
          "dispatchid" : {
            "type" : "long"
          },
          "dispatchprice" : {
            "type" : "long"
          },
          "dispatchtype" : {
            "type" : "boolean"
          },
          "displayorder" : {
            "type" : "long"
          },
          "diyfields" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "diyformid" : {
            "type" : "long"
          },
          "diyformtype" : {
            "type" : "boolean"
          },
          "diymode" : {
            "type" : "boolean"
          },
          "diypage" : {
            "type" : "long"
          },
          "diysave" : {
            "type" : "boolean"
          },
          "diysaveid" : {
            "type" : "long"
          },
          "edareas" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "edmoney" : {
            "type" : "long"
          },
          "ednum" : {
            "type" : "long"
          },
          "followtip" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "followurl" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "goodssn" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "groupstype" : {
            "type" : "boolean"
          },
          "hascommission" : {
            "type" : "long"
          },
          "hasoption" : {
            "type" : "long"
          },
          "hidecommission" : {
            "type" : "long"
          },
          "id" : {
            "type" : "long"
          },
          "invoice" : {
            "type" : "long"
          },
          "iscomment" : {
            "type" : "boolean"
          },
          "isdiscount" : {
            "type" : "boolean"
          },
          "isdiscount_discounts" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "isdiscount_time" : {
            "type" : "long"
          },
          "isdiscount_title" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "ishot" : {
            "type" : "boolean"
          },
          "isnew" : {
            "type" : "boolean"
          },
          "isnodiscount" : {
            "type" : "long"
          },
          "isrecommand" : {
            "type" : "boolean"
          },
          "issendfree" : {
            "type" : "boolean"
          },
          "istime" : {
            "type" : "boolean"
          },
          "isverify" : {
            "type" : "long"
          },
          "jd_vop_page_num" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "jd_vop_sku" : {
            "type" : "long"
          },
          "keywords" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "labelname" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "manydeduct" : {
            "type" : "boolean"
          },
          "marketprice" : {
            "type" : "float"
          },
          "maxbuy" : {
            "type" : "long"
          },
          "maxprice" : {
            "type" : "float"
          },
          "merchid" : {
            "type" : "long"
          },
          "merchsale" : {
            "type" : "boolean"
          },
          "minbuy" : {
            "type" : "long"
          },
          "minprice" : {
            "type" : "float"
          },
          "minpriceupdated" : {
            "type" : "boolean"
          },
          "money" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "needfollow" : {
            "type" : "long"
          },
          "nocommission" : {
            "type" : "long"
          },
          "noticeopenid" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "noticetype" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "old_shop_goods_id" : {
            "type" : "long"
          },
          "originalprice" : {
            "type" : "long"
          },
          "pcate" : {
            "type" : "long"
          },
          "pcates" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "productprice" : {
            "type" : "float"
          },
          "productsn" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "province" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "quality" : {
            "type" : "long"
          },
          "repair" : {
            "type" : "long"
          },
          "sales" : {
            "type" : "long"
          },
          "salesreal" : {
            "type" : "long"
          },
          "score" : {
            "type" : "long"
          },
          "seven" : {
            "type" : "long"
          },
          "share_icon" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "share_title" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "sharebtn" : {
            "type" : "boolean"
          },
          "shopid" : {
            "type" : "long"
          },
          "shorttitle" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "showgroups" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "showlevels" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "showtotal" : {
            "type" : "boolean"
          },
          "showtotaladd" : {
            "type" : "boolean"
          },
          "spec" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "status" : {
            "type" : "boolean"
          },
          "storeids" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "subtitle" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "synctime_jd_vop_price" : {
            "type" : "long"
          },
          "taobaoid" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "taobaourl" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "taotaoid" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "tcate" : {
            "type" : "long"
          },
          "tcates" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "thumb" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "thumb_first" : {
            "type" : "long"
          },
          "thumb_url" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "timeend" : {
            "type" : "long"
          },
          "timestart" : {
            "type" : "long"
          },
          "title" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "total" : {
            "type" : "long"
          },
          "totalcnf" : {
            "type" : "long"
          },
          "type" : {
            "type" : "long"
          },
          "uniacid" : {
            "type" : "long"
          },
          "unit" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "updatetime" : {
            "type" : "long"
          },
          "usermaxbuy" : {
            "type" : "long"
          },
          "verifytype" : {
            "type" : "boolean"
          },
          "viewcount" : {
            "type" : "long"
          },
          "virtual" : {
            "type" : "long"
          },
          "virtualsend" : {
            "type" : "boolean"
          },
          "virtualsendcontent" : {
            "type" : "text",
            "fields" : {
              "keyword" : {
                "type" : "keyword",
                "ignore_above" : 256
              }
            }
          },
          "weight" : {
            "type" : "long"
          }
        }
      }
    }
  },