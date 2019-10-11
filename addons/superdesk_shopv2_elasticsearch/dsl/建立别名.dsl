


-XPOST localhost:9200/_aliases -d '
{
    "actions": [
        { "remove": {
            "alias": "my_index",
            "index": "my_index_v1"
        }},
        { "add": {
            "alias": "my_index",
            "index": "my_index_v2"
        }}
    ]
}







-XPOST http://127.0.0.1:9200/_aliases
{
    "actions": [

        { "add": {
            "alias": "db_super_desk",
            "index": "db_super_desk_v20180408"
        }}
    ]
}
