<?php
/** 
 * 文件名(system/mvc/Model.php)
 * 
 * 提供Model类的定义
 * 
 * @package     system
 * @subpackage  mvc
 * @author      Nash<18695616095@163.com> 
 * @since       Version 1.0.0
 */

namespace system\mvc;

/**
 * system\mvc\Model类
 *
 * 这个类的实例作为模型基类
 *
 * @package     system
 * @subpackage  mvc
 * @category    core
 * @author      Nash
 */
class Model
{
    const COLLATE = 'COLLATE';
    const ENGINE = 'ENGINE';
    const TABLE = 'TABLE';
    const FIELDS = 'FIELDS';

    const INDEX = 'INDEX';
    const UNIQUE = 'UNIQUE INDEX';
    const SPATIAL = 'SPATIAL INDEX';
    const FULLTEXT = 'FULLTEXT INDEX';
    const PRIMARY = 'PRIMARY KEY';
    const FOREIGN = 'FOREIGN KEY';

    const TYPE = 'TYPE';
    const TYPE_INT = 'INT';
    const TYPE_VARCHAR = 'VARCHAR';
    const TYPE_CHAR = 'CHAR';
    const TYPE_DATETIME = 'DATETIME';
    const TYPE_TEXT = 'TEXT';
    const TYPE_BIT = 'BIT';
    const TYPE_TIMESTAMP = 'TIMESTAMP';

    const NOT_NULL = 'NOT NULL';
    const AUTO_INCREMENT = 'AUTO_INCREMENT';

    const DEFAULT_VALUE = 'DEFAULT';
    const DEFAULT_NULL = 'NULL';
    const DEFAULT_CURRENT_TIMESTAMP = 'CURRENT_TIMESTAMP';
    const DEFAULT_CURRENT_TIMESTAMP_WITH_UPDATE = 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP';

    const COMMENT = 'COMMENT';

    protected $_di = null;

    /**
     * Model类构造方法
     *
     * 构建一个Model类的实例
     *
     * @return  void
     */
    public function __construct()
    {
        global $di;
        $this->_di = &$di;
    }
    
    public function get()
    {
        # code...
    }
/*
[
    'id' => 12
]
*/
    public static function find($where)
    {
        $where = $this->_where('AND', $where);
    }

    protected function _where($uni, $arr)
    {
        # code...
    }

    /**
     * migrate方法
     *
     * 执行数据库表的创建过程，请作为事务过程处理
     *
     * @return mixed
     */
    public static function migrate()
    {
        $class = get_called_class();
        $sql = 'CREATE TABLE `' . $class::$table[\system\mvc\Model::TABLE] . '` (';
        $filed_arr = array();
        foreach ($class::$table[\system\mvc\Model::FIELDS] as $key => $value) {
            if (is_array($value)) {
                $filed_arr[] = SELF::_make_filed($key, $value);
            } else {
                // 该模型为简化模型，不进行迁移处理，抛出异常
            }
        }
        $arr = array(\system\mvc\Model::PRIMARY, \system\mvc\Model::FOREIGN, \system\mvc\Model::INDEX, \system\mvc\Model::UNIQUE, \system\mvc\Model::FULLTEXT, \system\mvc\Model::SPATIAL);
        foreach ($arr as $v) {
            if (array_key_exists($v, $class::$table)) {
                if ($v === \system\mvc\Model::PRIMARY) {
                    $filed_arr[] = SELF::_make_key($v, $class::$table[$v]);
                } elseif ($v === \system\mvc\Model::FOREIGN) {
                    foreach ($class::$table[$v] as $key => $value) {
                        $re_temp = explode('#', $key);
                        $filed_arr[] = SELF::_make_key('FOREIGN KEY', $value[0]) . SELF::_make_key(' REFERENCES `' . $re_temp[0] . '`', $value[1]);
                    }
                } else {
                    foreach ($class::$table[$v] as $value) {
                        $filed_arr[] = SELF::_make_key($v, $value);
                    }
                }
            }
        }
        $sql .= (implode(', ', $filed_arr) . ')');
        $arr = array(\system\mvc\Model::COLLATE, \system\mvc\Model::ENGINE, \system\mvc\Model::COMMENT);
        foreach ($arr as $v) {
            if (array_key_exists($v, $class::$table)) {
                if ($v !== \system\mvc\Model::ENGINE) {
                    $sql .= (' ' . $v . '=\'' . $class::$table[$v] . '\'');
                } else {
                    $sql .= (' ' . $v . '=' . $class::$table[$v]);
                }
            }
        }
        global $di;
        return $di->get('db')->exec($sql);
    }

    /**
     * _make_key方法
     *
     * 生成制定key或者index参数部分sql语句
     *
     * @param  $keyname  key和index的类型
     * @param  $keys     需要设置的值
     * @return string
     */
    protected static function _make_key($keyname, $keys)
    {
        if (is_array($keys)) {
            return $keyname . ' (`' . implode('`, `', $keys) . '`)';
        } else {
            return $keyname . ' (`' . $keys . '`)';
        }
    }

    /**
     * _make_filed方法
     *
     * 生成字段部分sql
     *
     * @param  $filed_name  字段名称
     * @param  $filed_arr   字段属性的参数
     * @return string
     */
    protected static function _make_filed($filed_name, $filed_arr)
    {
        $re = '`' . $filed_name . '`';
        if (array_key_exists(\system\mvc\Model::TYPE, $filed_arr)) {
            $re .= SELF::_make_filed_type($filed_arr[\system\mvc\Model::TYPE]);
        }
        if (in_array(\system\mvc\Model::NOT_NULL, $filed_arr)) {
            $re .= ' NOT';
        }
        $re .= ' NULL';
        if (in_array(\system\mvc\Model::AUTO_INCREMENT, $filed_arr)) {
            $re .= ' AUTO_INCREMENT';
        } elseif (array_key_exists(\system\mvc\Model::DEFAULT_VALUE, $filed_arr)) {
            $re .= (' DEFAULT ' . (in_array($filed_arr[\system\mvc\Model::DEFAULT_VALUE], array(\system\mvc\Model::DEFAULT_NULL, \system\mvc\Model::DEFAULT_CURRENT_TIMESTAMP, \system\mvc\Model::DEFAULT_CURRENT_TIMESTAMP_WITH_UPDATE)) ? $filed_arr[\system\mvc\Model::DEFAULT_VALUE] : ('\'' . $filed_arr[\system\mvc\Model::DEFAULT_VALUE] . '\'')));
        }
        if (array_key_exists(\system\mvc\Model::COMMENT, $filed_arr)) {
            $re .= ' COMMENT \'' . $filed_arr[\system\mvc\Model::COMMENT] . '\'';
        }
        return $re;
    }

    /**
     * _make_filed_type方法
     *
     * 生成字段值的类型
     *
     * @param  $type_arr  需要生成的字段的类型数据
     * @return string
     */
    protected static function _make_filed_type($type_arr)
    {
        if (is_array($type_arr) && isset($type_arr[1])) {
            return ' ' . $type_arr[0] . '(' . $type_arr[1] . ')';
        } elseif (is_array($type_arr)) {
            return ' ' . $type_arr[0];
        } else {
            return ' ' . $type_arr;
        }
    }
}
