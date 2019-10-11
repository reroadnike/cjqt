


curl -XPOST "https://127.0.0.1:9200/db_super_desk/ims_superdesk_shop_goods/_mapping?pretty" -d '{
     "ims_superdesk_shop_goods": {
                "properties": {
                 "status":{
                    "type":"long"
               }
            }
        }
}'