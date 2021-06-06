<?php
// +---------------------------------
// | Copyright (c) 2016-2019 52cp.cn
// +---------------------------------
// | DateTime: 2019/7/9 10:29
// +---------------------------------
// | Author: samir <84570829@qq.com>
// +---------------------------------


namespace chat\library\samir\mysql;


use Swoole\Coroutine;

class Db
{
    private static $instance;
    private static $sql = [];
    private static $mysql = [];
    private static $channel = [];

    static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function init($channel = 'client')
    {
        self::$channel[Coroutine::getCid()] = $channel;
        return $this;
    }

    /**
     * 设置表名
     * @param $table | 表名
     * @return $this
     */
    public function table($table)
    {
        $this->addParam('table', trim($table));
        return $this;
    }

    /**
     * 设置字段
     * @param $field | 字段
     * @return $this
     */
    public function field($field)
    {
        $this->addParam('field', trim($field));
        return $this;
    }

    /**
     * 设置join条件
     * @param $table | 表名
     * @param $condition | 条件
     * @param string $lr | 作用域 LEFT,RIGHT,''
     * @return $this
     */
    public function join($table, $condition, $lr = '')
    {
        switch (trim($lr)) {
            case 'left':
                $direction = 'LEFT JOIN';
                break;
            case 'right':
                $direction = 'RIGHT JOIN';
                break;
            default:
                $direction = 'JOIN';
        }
        $this->addParam('join', $direction . ' ' . trim($table) . ' ON ' . trim($condition), 2);
        return $this;
    }

    /**
     * 设置group条件
     * @param $field | 字段
     * @return $this
     */
    public function group($field)
    {
        $this->addParam('group', trim($field), 2);
        return $this;
    }

    /**
     * 设置having条件
     * @param $field | 字段
     * @param $operator | 条件
     * @param $value | 值
     * @return $this
     */
    public function having($field, $operator, $value)
    {
        $this->addParam('having', trim($field) . ' ' . trim($operator) . ' ' . trim($value), 2);
        return $this;
    }

    /**
     * 设置order条件
     * @param $field | 字段
     * @param $sort | 排序 ASC DESC
     * @return $this
     */
    public function order($field, $sort)
    {
        $this->addParam('order', trim($field) . ' ' . strtoupper(trim($sort)), 2);
        return $this;
    }


    public function limit(...$argv)
    {
        $this->addParam('limit', implode(',', $argv));
        return $this;
    }

    /**
     * 设置where AND
     * @param mixed ...$argv | 变长参，2位则表示=
     * @return $this
     */
    public function where(...$argv)
    {
        switch (count($argv)) {
            case 2:
                $this->addParam('where', [$this->buildWhere($argv[0], '=', $argv[1]), 'AND'], 2);
                break;
            case 3:
                $this->addParam('where', [$this->buildWhere($argv[0], $argv[1], $argv[2]), 'AND'], 2);
                break;
            default:
                throw new \RuntimeException('where param error');
        }
        return $this;
    }

    /**
     * 设置where OR
     * @param mixed ...$argv | 变长参，2位则表示=
     * @return $this
     */
    public function whereOr(...$argv)
    {
        switch (count($argv)) {
            case 2:
                $this->addParam('where', [$this->buildWhere($argv[0], '=', $argv[1]), 'OR'], 2);
                break;
            case 3:
                $this->addParam('where', [$this->buildWhere($argv[0], $argv[1], $argv[2]), 'OR'], 2);
                break;
            default:
                throw new \RuntimeException('whereOr param error');
        }
        return $this;
    }

    /**
     * 执行sql语句[预处理]
     * @param $sql | sql语句
     * @param array $p | 预处理参数
     * @return mixed
     */
    public function query($sql, $p = [])
    {
        return $this->prepare(trim($sql), $p, 1);
    }

    /**
     * 单条查询 | 成功返回1维数组或空数组，失败返回null
     * @param bool $buildSql 是否返回sql
     * @return mixed|string
     */
    public function find($buildSql = false)
    {
        $res = $this->buildSql('find', $buildSql);
        if ($buildSql === true) {
            return $res;
        } else {
            return !$res ? $res : $res[0];
        }
    }

    /**
     * 多条查询 | 成功返回2维数组或空数组，失败返回null
     * @param bool $buildSql 是否返回sql
     * @return mixed|string
     */
    public function select($buildSql = false)
    {
        return $this->buildSql('select', $buildSql);
    }

    /**
     * 列查询 | 成功返回1维2维或空数组，失败返回null
     * @param $field : 查询字段
     * @param string $index 查询索引
     * @param bool $buildSql 是否返回sql
     * @return array|mixed|string
     */
    public function column($field, $index = '', $buildSql = false)
    {
        $_field = $index != '' ? trim($field) . ',' . trim($index) : trim($field);
        $this->addParam('field', $_field);
        $res = $this->buildSql('column', $buildSql);
        if ($buildSql === true) {
            return $res;
        } else {
            if ($res) {
                switch (count(explode(',', $_field))) {
                    case 1:
                        $res = array_column($res, $field);
                        break;
                    case 2:
                        $res = $index != '' ? array_column($res, $field, $index) : $res;
                        break;
                    default:
                        $res = $index != '' ? array_column($res, null, $index) : $res;
                }
            }
            return $res;
        }
    }

    /**
     * 获取某的字段的值 | 成功返回字段值，失败返回false
     * @param $field | 查询字段
     * @param bool $buildSql 是否返回sql
     * @return bool|mixed|string
     */
    public function value($field, $buildSql = false)
    {
        $this->addParam('field', $field);
        $res = $this->buildSql('value', $buildSql);
        if ($buildSql === true) {
            return $res;
        } else {
            return isset($res[0][$field]) ? $res[0][$field] : null;
        }
    }

    /**
     * 聚合查询count | 成功返回总数，失败返回null
     * @param string $field | 查询字段
     * @param bool $buildSql 是否返回sql
     * @return mixed|string
     */
    public function count($field = '*', $buildSql = false)
    {
        $this->addParam('field', "COUNT({$field}) `count`");
        $res = $this->buildSql('count', $buildSql);
        if ($buildSql === true) {
            return $res;
        } else {
            return !$res ? $res : $res[0]['count'];
        }
    }

    /**
     * 聚合查询sum | 成功返回总数，失败返回null
     * @param $field | 查询字段
     * @param bool $buildSql 是否返回sql
     * @return mixed|string
     */
    public function sum($field, $buildSql = false)
    {
        $this->addParam('field', "SUM({$field}) `sum`");
        $res = $this->buildSql('sum', $buildSql);
        if ($buildSql === true) {
            return $res;
        } else {
            return !$res ? $res : $res[0]['sum'];
        }
    }

    /**
     * 聚合查询
     * @param $field
     * @param bool $buildSql
     * @return mixed|string
     */
    public function max($field, $buildSql = false)
    {
        $this->addParam('field', "MAX({$field}) `max`");
        $res = $this->buildSql('max', $buildSql);
        if ($buildSql === true) {
            return $res;
        } else {
            return !$res ? $res : $res[0]['max'];
        }
    }

    /**
     * 聚合查询
     * @param $field
     * @param bool $buildSql
     * @return mixed|string
     */
    public function min($field, $buildSql = false)
    {
        $this->addParam('field', "MIN({$field}) `min`");
        $res = $this->buildSql('min', $buildSql);
        if ($buildSql === true) {
            return $res;
        } else {
            return !$res ? $res : $res[0]['min'];
        }
    }

    /**
     * 聚合查询
     * @param $field
     * @param bool $buildSql
     * @return mixed|string
     */
    public function avg($field, $buildSql = false)
    {
        $this->addParam('field', "AVG({$field}) `avg`");
        $res = $this->buildSql('avg', $buildSql);
        if ($buildSql === true) {
            return $res;
        } else {
            return !$res ? $res : $res[0]['avg'];
        }
    }

    /**
     * 分页查询 | 成功返回2维数组或空数组，失败返回null
     * @param int $page | 页码
     * @param int $limit | 单页条数
     * @param bool $buildSql 是否返回sql
     * @return mixed|string
     */
    public function paginate($page = 1, $limit = 10, $buildSql = false)
    {
        $this->addParam('limit', ($page - 1) * $limit . "," . $limit);
        return $this->buildSql('paginate', $buildSql);
    }

    /**
     * 单条写入 | 成功返回主键ID，失败返回false
     * @param array $data | 一维数组
     * @param bool $buildSql 是否返回sql
     * @return mixed|string
     */
    public function insert(array $data, $buildSql = false)
    {
        $this->addParam('field', '(' . implode(',', array_map(function ($v) {
                return "`{$v}`";
            }, array_keys($data))) . ')');
        $this->addParam('values', '(' . $this->buildAsk(count($data)) . ')', 2);
        $this->addStmt($data);
        return $this->buildSql('insert', $buildSql);
    }

    /**
     * 批量写入 | 成功返回插入条数，失败返回false
     * @param array $data | 二维数组
     * @param bool $buildSql 是否返回sql
     * @return mixed|string
     */
    public function insertAll(array $data, $buildSql = false)
    {
        $_data = end($data);
        $ask = $this->buildAsk(count($_data));
        $this->addParam('field', '(' . implode(',', array_map(function ($v) {
                return "`{$v}`";
            }, array_keys($_data))) . ')');
        foreach ($data as $k => $v) {
            $this->addStmt($v);
            $this->addParam('values', "({$ask})", 2);
        }
        return $this->buildSql('insertAll', $buildSql);
    }

    /**
     * 单条更新 | 成功返回更新的条数，失败返回false
     * @param array $data | 一维数组
     * @param bool $buildSql 是否返回sql
     * @return mixed|string
     */
    public function update(array $data, $buildSql = false)
    {
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $this->addParam('set', "`{$k}`=`{$v[0]}`{$v[1]}{$v[2]}", 2);
            } else {
                $this->addParam('set', "`{$k}`" . '=' . $this->buildType($v), 2);
            }
        }
        return $this->buildSql('update', $buildSql);
    }

    /**
     * 批量更新 | 成功返回更新的条数，失败返回false
     * @param array $data | 二维数组
     * @param bool $buildSql 是否返回sql
     * @return float|int|mixed|string
     */
    public function updateAll(array $data, $buildSql = false)
    {
        $_data = end($data);
        $ask = $this->buildAsk(count($_data));
        $this->addParam('field', '(' . implode(',', array_map(function ($v) {
                return "`{$v}`";
            }, array_keys($_data))) . ')');
        foreach ($_data as $k => $v) {
            if (is_array($v)) {
                $this->addParam('duplicate', "`{$k}`=VALUES(`{$v[0]}`){$v[1]}`{$k}`", 2);
            } else {
                $this->addParam('duplicate', "`{$k}`=VALUES(`{$k}`)", 2);
            }
        }
        foreach ($data as $k => $v) {
            $this->addParam('values', '(' . $ask . ')', 2);
            $this->addStmt($v);
        }
        return $buildSql === true ? $this->buildSql('updateAll', $buildSql) : $this->buildSql('updateAll', $buildSql) / 2;
    }

    /**
     * 字段递增 | 成功返回true，失败返回false
     * @param $field
     * @param int $incr
     * @param bool $buildSql 是否返回sql
     * @return mixed|string
     */
    public function setInc($field, $incr = 1, $buildSql = false)
    {
        $this->addParam('set', "`{$field}`=`{$field}`+{$incr}", 2);
        return $this->buildSql('setInc', $buildSql);
    }

    /**
     * 字段递减 | 成功返回true，失败返回false
     * @param $field
     * @param int $decr
     * @param bool $buildSql 是否返回sql
     * @return mixed|string
     */
    public function setDec($field, $decr = 1, $buildSql = false)
    {
        $this->addParam('set', "`{$field}`=`{$field}`-{$decr}", 2);
        return $this->buildSql('setDec', $buildSql);
    }

    /**
     * 删除数据 | 成功返回true，失败返回false
     * @param bool $buildSql 是否返回sql
     * @return mixed|string
     */
    public function delete($buildSql = false)
    {
        return $this->buildSql('delete', $buildSql);
    }

    /**
     * 开启事务 | 断线重连，开启失败抛出异常，开启成功会阻塞自动回收
     */
    public function begin()
    {
        $cid = Coroutine::getCid();
        $retry = 0;
        back:
        $retry++;
        $mysql = ($retry === 1) ? $this->getMysql() : $this->getMysql(true);
        if (!$mysql->begin()) {
            if ($retry < 2) {
                goto back;
            } else {
                throw new \RuntimeException('mysql begin error');
            }
        }
    }

    /**
     * 提交事务 | 成功返回true并回收，失败返回false
     * @return mixed
     */
    public function commit()
    {
        $mysql = $this->getMysql();
        return $mysql->commit();
    }

    /**
     * 事务回滚 | 成功返回true并回收，失败返回false
     * @return mixed
     */
    public function rollback()
    {
        $mysql = $this->getMysql();
        return $mysql->rollback();
    }

    /**
     * 建立sql语句并查询
     * @param $method
     * @param bool $backSql
     * @return mixed|string
     */
    private function buildSql($method, $backSql = false)
    {
        $cid = Coroutine::getCid();
        $table = self::$sql[$cid]['table'];
        $field = empty(self::$sql[$cid]['field']) ? '*' : self::$sql[$cid]['field'];
        $join = empty(self::$sql[$cid]['join']) ? '' : implode(' ', self::$sql[$cid]['join']);
        if (!empty(self::$sql[$cid]['where'])) {
            $where = 'WHERE ';
            $whereTotal = count(self::$sql[$cid]['where']);
            foreach (self::$sql[$cid]['where'] as $k => $v) {
                if ($k == $whereTotal - 1) {
                    $where .= $v[0];
                } else {
                    $where .= "{$v[0]} {$v[1]} ";
                }
            }
        } else {
            $where = '';
        }
        $group = empty(self::$sql[$cid]['group']) ? '' : 'GROUP BY ' . implode(',', self::$sql[$cid]['group']);
        $having = empty(self::$sql[$cid]['having']) ? '' : 'HAVING ' . implode('AND', self::$sql[$cid]['having']);
        $order = empty(self::$sql[$cid]['order']) ? '' : 'ORDER BY ' . implode(',', self::$sql[$cid]['order']);
        $limit = empty(self::$sql[$cid]['limit']) ? '' : 'LIMIT ' . self::$sql[$cid]['limit'];
        $values = empty(self::$sql[$cid]['values']) ? '' : implode(',', self::$sql[$cid]['values']);
        $set = empty(self::$sql[$cid]['set']) ? '' : implode(',', self::$sql[$cid]['set']);
        $duplicate = empty(self::$sql[$cid]['duplicate']) ? '' : implode(',', self::$sql[$cid]['duplicate']);
        switch ($method) {
            case 'find':
            case 'select':
            case 'column':
            case 'value':
            case 'count':
            case 'sum':
            case 'avg':
            case 'max':
            case 'min':
            case 'paginate':
                $sql = "SELECT {$field} FROM {$table} {$join} {$where} {$group} {$having} {$order} {$limit}";
                $back = 1;
                break;
            case 'insert':
                $sql = "INSERT INTO {$table} {$field} VALUE {$values}";
                $back = 2;
                break;
            case 'insertAll':
                $sql = "INSERT INTO {$table} {$field} VALUES {$values}";
                $back = 3;
                break;
            case 'setInc':
            case 'setDec':
            case 'update':
                $sql = "UPDATE {$table} SET {$set} {$where}";
                $back = 3;
                break;
            case 'updateAll':
                $sql = "INSERT INTO {$table} {$field} VALUES {$values} ON DUPLICATE KEY UPDATE {$duplicate}";
                $back = 3;
                break;
            case 'delete':
                $sql = "DELETE FROM {$table} {$where}";
                $back = 3;
                break;
            default:
                throw new \RuntimeException('mysql method error');
        }
        $sql = str_replace(['  ', '   '], ' ', trim($sql));
        $stmt = !isset(self::$sql[$cid]['stmt']) ? [] : self::$sql[$cid]['stmt'];
        if (!$backSql) {
            return $this->prepare($sql, $stmt, $back);
        }
        return $sql;
    }

    /**
     * 预处理
     * @param $sql
     * @param array $stmtArr
     * @param int $backInfo
     * @return bool
     */
    private function prepare($sql, array $stmtArr, int $backInfo)
    {
        $cid = Coroutine::getCid();
        //初始化sql
        $this->initSql($cid);
        //如果是读写分离
        if (MysqlManager::$mode == 'proxy') {
            if (strpos($sql, 'SELECT') !== false || strpos($sql, 'select') !== false) {
                self::$sql[$cid]['queryMethod'] = 'read';
            } else {
                self::$sql[$cid]['queryMethod'] = 'write';
            }
        }
        $retry = 0;
        back:
        $retry++;
        $mysql = ($retry === 1) ? $this->getMysql() : $this->getMysql(true);
        $stmt = $mysql->prepare($sql);
        if ($stmt === false) {
            if ($retry < 2) {
                goto back;
            } else {
                $this->recycle($cid);
                throw new \RuntimeException("mysql query failed");
            }
        } else {
            $this->recycle($cid);
            $result = $stmt->execute($stmtArr);
        }
        if ($result) {
            switch ($backInfo) {
                case 1:
                    $output = $result;
                    break;
                case 2:
                    $output = $mysql->insert_id;
                    break;
                default:
                    $output = $mysql->affected_rows;
            }
        } else {
            $output = false;
        }
        return $output;
    }

    /**
     * 获取连接
     * @param bool $replace 强制重连
     * @return Coroutine\MySQL
     */
    private function getMysql($replace = false): Coroutine\MySQL
    {
        $cid = Coroutine::getCid();
        if (MysqlManager::$mode == 'cluster') {
            $keyArr = array_keys(MysqlManager::$dbConf);
            $keyCount = (count($keyArr));
            $key = $keyCount > 1 ? $keyArr[mt_rand(0, ($keyCount - 1))] : $keyArr[0];
        } else {
            $key = self::$sql[$cid]['queryMethod'];
        }
        if ($replace === true) {
            self::$mysql[$cid][$key] = MysqlClient::getInstance()->get($key);
        } elseif (!isset(self::$mysql[$cid][$key])) {
            self::$mysql[$cid][$key] = (self::$channel[$cid] == 'client') ? MysqlClient::getInstance()->get($key) : MysqlPool::getInstance()->get($key);
        }
        return self::$mysql[$cid][$key];
    }

    /**
     * 资源回收
     * @param $cid
     */
    private function recycle($cid)
    {
        defer(function () use ($cid) {
            if (isset(self::$mysql[$cid])) {
                $keyArr = array_keys(self::$mysql[$cid]);
                if (self::$channel[$cid] == 'pool') {
                    foreach ($keyArr as $key) {
                        MysqlPool::getInstance()->put($key, self::$mysql[$cid][$key]);
                    }
                } else {
                    foreach ($keyArr as $key) {
                        self::$mysql[$cid][$key]->close();
                    }
                }
                unset(self::$mysql[$cid]);
            }
            if (isset(self::$sql[$cid])) {
                unset(self::$sql[$cid]);
            }
            if (isset(self::$channel[$cid])) {
                unset(self::$channel[$cid]);
            }
        });
    }

    /**
     * 初始化查询条件
     * @param $cid :协程ID
     */
    private function initSql($cid)
    {
        self::$sql[$cid] = [
            'queryMethod' => '',
            'table' => '',
            'field' => '',
            'limit' => '',
            'order' => [],
            'join' => [],
            'group' => [],
            'having' => [],
            'where' => [],
            'values' => [],
            'set' => [],
            'duplicate' => [],
            'stmt' => [],
        ];
    }

    /**
     * 构造查询条件
     * @param $key
     * @param $val
     * @param int $mode
     */
    private function addParam($key, $val, $mode = 1)
    {
        if ($mode == 2) {
            self::$sql[Coroutine::getCid()][$key][] = $val;
        } else {
            self::$sql[Coroutine::getCid()][$key] = $val;
        }
    }

    /**
     * 构造预加载值
     * @param $val
     */
    private function addStmt($val)
    {
        $cid = Coroutine::getCid();
        if (is_array($val)) {
            foreach ($val as $v) {
                if (is_array($v)) {
                    self::$sql[$cid]['stmt'][] = $v[2];
                } else {
                    self::$sql[$cid]['stmt'][] = $v;
                }
            }
        } else {
            self::$sql[$cid]['stmt'][] = $val;
        }
    }

    /**
     * 构造where条件
     * @param $field | 字段
     * @param $operator | 条件
     * @param $value | 值
     * @return string
     */
    private function buildWhere($field, $operator, $value)
    {
        $val = '';
        $operator = strtoupper($operator);
        switch ($operator) {
            case "NOT IN":
            case 'IN':
                if (!is_array($value)) {
                    $value = explode(',', $value);
                }
                $this->addStmt($value);
                $val .= '(';
                $val .= $this->buildAsk(count($value));
                $val .= ')';
                break;
            case 'NOT BETWEEN':
            case 'BETWEEN':
                if (!is_array($value)) {
                    $value = explode(',', $value);
                }
                $this->addStmt($value);
                $val .= "? AND ?";
                break;
            case 'IS NOT NULL':
            case 'IS NULL':
                break;
            default:
                $val .= '?';
                $this->addStmt($value);
        }
        return "{$field} {$operator} {$val}";
    }

    /**
     * 构造预处理问号
     * @param int $total
     * @return string
     */
    private function buildAsk($total)
    {
        $str = '';
        for ($i = 0; $i < $total; $i++) {
            $str .= ($i == 0) ? '?' : ',?';
        }
        return $str;
    }

    /**
     * 判断值类型以确定是否加引号
     * @param $val
     * @return string
     */
    private function buildType($val)
    {
        return is_string($val) ? "'{$val}'" : $val;
    }

}