[
    {
        "expr_type": "colref", 
        "base_expr": "uniacid", 
        "no_quotes": "uniacid", 
        "sub_tree": false
    }, 
    {
        "expr_type": "operator", 
        "base_expr": "=", 
        "sub_tree": false
    }, 
    {
        "expr_type": "const", 
        "base_expr": "16", 
        "sub_tree": false
    }, 
    {
        "expr_type": "operator", 
        "base_expr": "AND", 
        "sub_tree": false
    }, 
    {
        "expr_type": "colref", 
        "base_expr": "deleted", 
        "no_quotes": "deleted", 
        "sub_tree": false
    }, 
    {
        "expr_type": "operator", 
        "base_expr": "=", 
        "sub_tree": false
    }, 
    {
        "expr_type": "const", 
        "base_expr": "0", 
        "sub_tree": false
    }, 
    {
        "expr_type": "operator", 
        "base_expr": "AND", 
        "sub_tree": false
    }, 
    {
        "expr_type": "colref", 
        "base_expr": "status", 
        "no_quotes": "status", 
        "sub_tree": false
    }, 
    {
        "expr_type": "operator", 
        "base_expr": "=", 
        "sub_tree": false
    }, 
    {
        "expr_type": "const", 
        "base_expr": "1", 
        "sub_tree": false
    }, 
    {
        "expr_type": "operator", 
        "base_expr": "and", 
        "sub_tree": false
    }, 
    {
        "expr_type": "colref", 
        "base_expr": "checked", 
        "no_quotes": "checked", 
        "sub_tree": false
    }, 
    {
        "expr_type": "operator", 
        "base_expr": "=", 
        "sub_tree": false
    }, 
    {
        "expr_type": "const", 
        "base_expr": "0", 
        "sub_tree": false
    }, 
    {
        "expr_type": "operator", 
        "base_expr": "and", 
        "sub_tree": false
    }, 
    {
        "expr_type": "colref", 
        "base_expr": "merchid", 
        "no_quotes": "merchid", 
        "sub_tree": false
    }, 
    {
        "expr_type": "operator", 
        "base_expr": "in", 
        "sub_tree": false
    }, 
    {
        "expr_type": "in-list", 
        "base_expr": "( 8,11,13,14,15,16,18,19,20)", 
        "sub_tree": [
            {
                "expr_type": "const", 
                "base_expr": "8", 
                "sub_tree": false
            }, 
            {
                "expr_type": "const", 
                "base_expr": "11", 
                "sub_tree": false
            }, 
            {
                "expr_type": "const", 
                "base_expr": "13", 
                "sub_tree": false
            }, 
            {
                "expr_type": "const", 
                "base_expr": "14", 
                "sub_tree": false
            }, 
            {
                "expr_type": "const", 
                "base_expr": "15", 
                "sub_tree": false
            }, 
            {
                "expr_type": "const", 
                "base_expr": "16", 
                "sub_tree": false
            }, 
            {
                "expr_type": "const", 
                "base_expr": "18", 
                "sub_tree": false
            }, 
            {
                "expr_type": "const", 
                "base_expr": "19", 
                "sub_tree": false
            }, 
            {
                "expr_type": "const", 
                "base_expr": "20", 
                "sub_tree": false
            }
        ]
    }, 
    {
        "expr_type": "operator", 
        "base_expr": "and", 
        "sub_tree": false
    }, 
    {
        "expr_type": "colref", 
        "base_expr": "minprice", 
        "no_quotes": "minprice", 
        "sub_tree": false
    }, 
    {
        "expr_type": "operator", 
        "base_expr": ">", 
        "sub_tree": false
    }, 
    {
        "expr_type": "const", 
        "base_expr": "0.00", 
        "sub_tree": false
    }, 
    {
        "expr_type": "operator", 
        "base_expr": "AND", 
        "sub_tree": false
    }, 
    {
        "expr_type": "colref", 
        "base_expr": "title.keyword", 
        "no_quotes": "title.keyword", 
        "sub_tree": false
    }, 
    {
        "expr_type": "operator", 
        "base_expr": "LIKE", 
        "sub_tree": false
    }, 
    {
        "expr_type": "colref", 
        "base_expr": "%打印机%", 
        "no_quotes": "%打印机%", 
        "sub_tree": false
    }
]