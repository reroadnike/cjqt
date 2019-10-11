{
    "from": 0,
    "size": "10",
    "query": {
        "bool": {
            "filter": [
                {
                    "bool": {
                        "must": [
                            {
                                "match_phrase": {
                                    "uniacid": {
                                        "query": "16"
                                    }
                                }
                            }
                        ]
                    }
                },
                {
                    "bool": {
                        "must": [
                            {
                                "match_phrase": {
                                    "deleted": {
                                        "query": "0"
                                    }
                                }
                            }
                        ]
                    }
                },
                {
                    "bool": {
                        "must": [
                            {
                                "match_phrase": {
                                    "status": {
                                        "query": "1"
                                    }
                                }
                            }
                        ]
                    }
                },
                {
                    "bool": {
                        "must": [
                            {
                                "match_phrase": {
                                    "checked": {
                                        "query": "0"
                                    }
                                }
                            }
                        ]
                    }
                },
                {
                    "terms": {
                        "merchid": [
                            "8",
                            "11",
                            "13",
                            "14",
                            "15",
                            "16",
                            "18",
                            "19",
                            "20"
                        ]
                    }
                },
                {
                    "range": {
                        "minprice": {
                            "gt": "0.00",
                            "time_zone": "+08:00"
                        }
                    }
                },
                {
                    "bool": {
                        "must": [
                            {
                                "match_phrase": {
                                    "`title`": "AUCS"
                                }
                            },
                            {
                                "match_phrase": {
                                    "`title`": "白板伴侣组合套装（白板清洁剂×1、白板笔×2、磁性白板擦×1、清洁布×1）"
                                }
                            }
                        ]
                    }
                }
            ]
        }
    },
    "sort": [
        {
            "displayorder": {
                "order": "DESC"
            }
        },
        {
            "createtime": {
                "order": "DESC"
            }
        }
    ]
}