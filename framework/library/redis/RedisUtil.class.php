<?php

/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 16-4-11
 * Time: 下午8:44
 */
class RedisUtil
{

    private $redis;

    /**
     * @param string $host
     * @param int    $post
     */
    public function __construct($host = '127.0.0.1', $port = 6379)
    {
        $this->redis = new Redis();
        $this->redis->connect($host, $port);
        return $this->redis;
    }

    /**
     * 设置值  构建一个字符串
     *
     * @param string $key     KEY名称
     * @param string $value   设置值
     * @param int    $timeOut 时间  0表示无过期时间
     */
    public function set($key, $value, $timeOut = 0)
    {
        $retRes = $this->redis->set($key, $value);
        if ($timeOut > 0){
            $this->redis->expire('$key', $timeOut);
        }

        return $retRes;
    }

    /**
     * Set the string value in argument as value of the key, with a time to live.
     *
     * @param   string $key
     * @param   int    $ttl
     * @param   string $value
     *
     * @return  bool:   TRUE if the command is successful.
     * @link    http://redis.io/commands/setex
     * @example $redis->setex('key', 3600, 'value'); // sets key → value, with 1h TTL.
     */
    public function setex($key, $ttl, $value)
    {
        return $this->redis->setex($key, $ttl, $value);
    }

    /*
     * 构建一个集合(无序集合)
     * @param string $key 集合Y名称
     * @param string|array $value  值
     */
    public function sadd($key, $value)
    {
        return $this->redis->sadd($key, $value);
    }

    /*
     * 构建一个集合(有序集合)
     * @param string $key 集合名称
     * @param string|array $value  值
     */
    public function zadd($key, $value)
    {
        return $this->redis->zadd($key, $value);
    }

    /**
     * 取集合对应元素
     *
     * @param string $setName 集合名字
     */
    public function smembers($setName)
    {
        return $this->redis->smembers($setName);
    }

    /**
     * 构建一个列表(先进后出，类似栈)
     *
     * @param sting  $key   KEY名称
     * @param string $value 值
     */
    public function lPush($key, $value)
    {
//        echo "$key - $value \n";
        return $this->redis->lPush($key, $value);
    }



    /**
     * 获取所有列表数据（从头到尾取）
     *
     * @param sting $key  KEY名称
     * @param int   $head 开始
     * @param int   $tail 结束
     */
    public function lranges($key, $head, $tail)
    {
        return $this->redis->lrange($key, $head, $tail);
    }

    /**
     * HASH类型
     *
     * @param string $tableName 表名字key
     * @param string $key       字段名字
     * @param sting  $value     值
     */
    public function hset($tableName, $field, $value)
    {
        return $this->redis->hset($tableName, $field, $value);
    }

    public function hget($tableName, $field)
    {
        return $this->redis->hget($tableName, $field);
    }

    /**
     * Returns the length of a hash, in number of items
     *
     * @param   string $key
     *
     * @return  int     the number of items in a hash, FALSE if the key doesn't exist or isn't a hash.
     * @link    http://redis.io/commands/hlen
     * @example
     * <pre>
     * $redis->delete('h')
     * $redis->hSet('h', 'key1', 'hello');
     * $redis->hSet('h', 'key2', 'plop');
     * $redis->hLen('h'); // returns 2
     * </pre>
     */
    public function hlen($tableName)
    {
        return $this->redis->hLen($tableName);
    }

    /**
     * Removes a values from the hash stored at key.
     * If the hash table doesn't exist, or the key doesn't exist, FALSE is returned.
     *
     * @param   string  $key
     * @param   string  $hashKey1
     * @param   string  $hashKey2
     * @param   string  $hashKeyN
     * @return  int     Number of deleted fields
     * @link    http://redis.io/commands/hdel
     * @example
     * <pre>
     * $redis->hMSet('h',
     *               array(
     *                    'f1' => 'v1',
     *                    'f2' => 'v2',
     *                    'f3' => 'v3',
     *                    'f4' => 'v4',
     *               ));
     *
     * var_dump( $redis->hDel('h', 'f1') );        // int(1)
     * var_dump( $redis->hDel('h', 'f2', 'f3') );  // int(2)
     * s
     * var_dump( $redis->hGetAll('h') );
     * //// Output:
     * //  array(1) {
     * //    ["f4"]=> string(2) "v4"
     * //  }
     * </pre>
     */
    public function hDel($tableName, $hashKey1, $hashKey2 = null, $hashKeyN = null)
    {
        return $this->redis->hDel($tableName, $hashKey1, $hashKey2, $hashKeyN);
    }


    /**
     * 设置多个值
     *
     * @param array        $keyArray KEY名称
     * @param string|array $value    获取得到的数据
     * @param int          $timeOut  时间
     */
    public function sets($keyArray, $timeout)
    {
        if (is_array($keyArray)) {
            $retRes = $this->redis->mset($keyArray);
            if ($timeout > 0) {
                foreach ($keyArray as $key => $value) {
                    $this->redis->expire($key, $timeout);
                }
            }
            return $retRes;
        } else {
            return "Call  " . __FUNCTION__ . " method  parameter  Error !";
        }
    }

    /**
     * 通过key获取数据
     *
     * @param string $key KEY名称
     */
    public function get($key)
    {
        $result = $this->redis->get($key);
        return $result;
    }

    /**
     * 同时获取多个值
     *
     * @param ayyay $keyArray 获key数值
     */
    public function gets($keyArray)
    {
        if (is_array($keyArray)) {
            return $this->redis->mget($keyArray);
        } else {
            return "Call  " . __FUNCTION__ . " method  parameter  Error !";
        }
    }

    /**
     * 获取所有key名，不是值
     */
    public function keyAll()
    {
        return $this->redis->keys('*');
    }

    /**
     * 删除一条数据key
     *
     * @param string $key 删除KEY的名称
     */
    public function del($key)
    {
        return $this->redis->delete($key);
    }

    /**
     * 同时删除多个key数据
     *
     * @param array $keyArray KEY集合
     */
    public function dels($keyArray)
    {
        if (is_array($keyArray)) {
            return $this->redis->del($keyArray);
        } else {
            return "Call  " . __FUNCTION__ . " method  parameter  Error !";
        }
    }

    /**
     * 数据自增
     *
     * @param string $key KEY名称
     */
    public function increment($key)
    {
        return $this->redis->incr($key);
    }

    /**
     * 数据自减
     *
     * @param string $key KEY名称
     */
    public function decrement($key)
    {
        return $this->redis->decr($key);
    }


    /**
     * 判断key是否存在
     *
     * @param string $key KEY名称
     */
    public function isExists($key)
    {
        return $this->redis->exists($key);
    }

    /**
     * @param $key
     * @param $hashKey
     *
     * @return bool
     */
    public function ishExists($key, $hashKey)
    {
        return $this->redis->hExists($key, $hashKey);
    }


    /**
     * 重命名- 当且仅当newkey不存在时，将key改为newkey ，当newkey存在时候会报错哦RENAME
     *  和 rename不一样，它是直接更新（存在的值也会直接更新）
     *
     * @param string $Key    KEY名称
     * @param string $newKey 新key名称
     */
    public function updateName($key, $newKey)
    {
        return $this->redis->RENAMENX($key, $newKey);
    }

    /**
     * 获取KEY存储的值类型
     * none(key不存在) int(0)  string(字符串) int(1)   list(列表) int(3)  set(集合) int(2)   zset(有序集) int(4)    hash(哈希表) int(5)
     *
     * @param string $key KEY名称
     */
    public function dataType($key)
    {
        return $this->redis->type($key);
    }


    /**
     * 清空数据
     */
    public function flushAll()
    {
        return $this->redis->flushAll();
    }


    /**
     * 返回redis对象
     * redis有非常多的操作方法，我们只封装了一部分
     * 拿着这个对象就可以直接调用redis自身方法
     * eg:$redis->redisOtherMethods()->keys('*a*')   keys方法没封
     */
    public function redisOtherMethods()
    {
        return $this->redis;
    }

    /**
     * Sort
     *
     * @param   string $key
     * @param   array  $option array(key => value, ...) - optional, with the following keys and values:
     *                         - 'by' => 'some_pattern_*',
     *                         - 'limit' => array(0, 1),
     *                         - 'get' => 'some_other_pattern_*' or an array of patterns,
     *                         - 'sort' => 'asc' or 'desc',
     *                         - 'alpha' => TRUE,
     *                         - 'store' => 'external-key'
     *
     * @return  array
     * An array of values, or a number corresponding to the number of elements stored if that was used.
     * @link    http://redis.io/commands/sort
     * @example
     * <pre>
     * $redis->delete('s');
     * $redis->sadd('s', 5);
     * $redis->sadd('s', 4);
     * $redis->sadd('s', 2);
     * $redis->sadd('s', 1);
     * $redis->sadd('s', 3);
     *
     * var_dump($redis->sort('s')); // 1,2,3,4,5
     * var_dump($redis->sort('s', array('sort' => 'desc'))); // 5,4,3,2,1
     * var_dump($redis->sort('s', array('sort' => 'desc', 'store' => 'out'))); // (int)5
     * </pre>
     */
    public function sort($key, $option = null)
    {

        return $this->redis->sort($key, $option);
    }

    /**
     * Returns the size of a list identified by Key. If the list didn't exist or is empty,
     * the command returns 0. If the data type identified by Key is not a list, the command return FALSE.
     *
     * @param   string  $key
     * @return  int     The size of the list identified by Key exists.
     * bool FALSE if the data type identified by Key is not list
     * @link    http://redis.io/commands/llen
     * @example
     * <pre>
     * $redis->rPush('key1', 'A');
     * $redis->rPush('key1', 'B');
     * $redis->rPush('key1', 'C');  // key1 => [ 'A', 'B', 'C' ]
     * $redis->lLen('key1');       // 3
     * $redis->rPop('key1');
     * $redis->lLen('key1');       // 2
     * </pre>
     */
    public function lLen($key){
        return $this->redis->lLen($key);
    }

    /**
     * Returns and removes the first element of the list.
     *
     * @param   string $key
     * @return  string if command executed successfully BOOL FALSE in case of failure (empty list)
     * @link    http://redis.io/commands/lpop
     * @example
     * <pre>
     * $redis->rPush('key1', 'A');
     * $redis->rPush('key1', 'B');
     * $redis->rPush('key1', 'C');  // key1 => [ 'A', 'B', 'C' ]
     * $redis->lPop('key1');        // key1 => [ 'B', 'C' ]
     * </pre>
     */
    public function lPop( $key ) {
        return $this->redis->lPop($key);
    }

    /**
     * Returns and removes the last element of the list.
     *
     * @param   string $key
     * @return  string if command executed successfully BOOL FALSE in case of failure (empty list)
     * @link    http://redis.io/commands/rpop
     * @example
     * <pre>
     * $redis->rPush('key1', 'A');
     * $redis->rPush('key1', 'B');
     * $redis->rPush('key1', 'C');  // key1 => [ 'A', 'B', 'C' ]
     * $redis->rPop('key1');        // key1 => [ 'A', 'B' ]
     * </pre>
     */
    public function rPop($key){
        return $this->redis->rPop($key);
    }

    /**
     * 构建一个列表(先进先出，类似队列)
     *
     * @param sting  $key   KEY名称
     * @param string $value 值
     */
    public function rPush($key, $value)
    {
        return $this->redis->rPush($key, $value);
    }

}