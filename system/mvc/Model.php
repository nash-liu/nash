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
 * 这个类的实例作为数据库模型基类
 *
 * @package     system
 * @subpackage  mvc
 * @category    core
 * @author      Nash
 */
class Model
{
    const TABLE = 'TABLE';
    const COLLATE = 'COLLATE';
    const ENGINE = 'ENGINE';
    const COMMENT = 'COMMENT';
    const FIELDS = 'FIELDS';

    const TYPE = 'TYPE';
    const TYPE_INT = 'INT';
    const TYPE_VARCHAR = 'VARCHAR';
    const TYPE_CHAR = 'CHAR';
    const TYPE_TEXT = 'TEXT';
    const TYPE_DATE = 'DATE';
    const TYPE_DATETIME = 'DATETIME';
    const TYPE_TIME = 'TIME';
    const TYPE_BIT = 'BIT';
    const TYPE_TIMESTAMP = 'TIMESTAMP';
    const TYPE_SET = 'SET';
    const TYPE_ENUM = 'ENUM';

    const UNSIGNED = 'UNSIGNED';
    const AUTO_INCREMENT = 'AUTO_INCREMENT';

    const NOT_NULL = 'NOT NULL';

    const DEFAULT_VALUE = 'DEFAULT';
    const DEFAULT_NULL = ' NULL';
    const DEFAULT_NOW = ' CURRENT_TIMESTAMP';

    const ON_UPDATE_NOW = ' ON UPDATE CURRENT_TIMESTAMP';

    const KEYS = 'KEYS';
    const PRIMARY = 'PRIMARY KEY ';
    const INDEX = 'INDEX ';
    const FOREIGN = 'FOREIGN KEY ';
    const UNIQUE = 'UNIQUE INDEX ';
    const FULLTEXT = 'FULLTEXT INDEX ';
    const SPATIAL = 'SPATIAL INDEX ';

    private $_class_name, $_table;

    public function __construct($class_name)
    {
        $this->_class_name = $class_name;
        $this->_table = $class_name::$table;
    }

    public function where($value='')
    {
        # code...
    }

    public function order($value='')
    {
        # code...
    }

    public function offset($value='')
    {
        # code...
    }

    public function get($value='')
    {
        # code...
    }

    public static function depot()
    {
        return new system\mvc\Model(get_called_class());
    }

    public static function migrate()
    {
        $class_name = get_called_class();
        global $di;
        return $di->get('db')->exec(Model::_make_table($class_name::$table)) !== false;
    }

    protected static function _make_table($arr)
    {
        $re = 'CREATE TABLE IF NOT EXISTS `' . $arr[Model::TABLE] . '` (' . Model::_make_fields($arr[Model::FIELDS]);
        if (isset($arr[Model::KEYS])) {
            $re .= (', ' . Model::_make_keys($arr[Model::KEYS]));
        }
        $re .= ')';
        if (isset($arr[Model::COMMENT])) {
            $re .= (' COMMENT=\'' . $arr[Model::COMMENT] . '\'');
        }
        if (isset($arr[Model::COLLATE])) {
            $re .= (' COLLATE=\'' . $arr[Model::COLLATE] . '\'');
        }
        if (isset($arr[Model::ENGINE])) {
            $re .= (' ENGINE=' . $arr[Model::ENGINE]);
        }
        return $re;
    }

    protected static function _make_fields($fields)
    {
        $arr = array();
        foreach ($fields as $key => $value) {
            if (!is_array($value)) {
                throw new Exception("Error Processing Request", 1);
            }
            $re_temp = '`' . $key . '`';
            $type = $value[Model::TYPE];
            if (is_array($type) && isset($type[1]) && is_array($type[1])) {
                $re_temp .= (' ' . $type[0] . '(\'' . implode('\', \'', $type[1]) . '\')');
            } elseif (is_array($type) && isset($type[1])) {
                $re_temp .= (' ' . $type[0] . '(' . $type[1] . ')');
            } elseif (is_array($type)) {
                $re_temp .= (' ' . $type[0]);
            } else {
                $re_temp .= (' ' . $type);
            }
            if (in_array(Model::UNSIGNED, $value)) {
                $re_temp .= ' UNSIGNED';
            }
            if (in_array(Model::NOT_NULL, $value)) {
                $re_temp .= ' NOT';
            }
            $re_temp .= ' NULL';
            if (in_array(Model::AUTO_INCREMENT, $value)) {
                $re_temp .= ' AUTO_INCREMENT';
            }
            if (isset($value[Model::DEFAULT_VALUE])) {
                $re_temp .= ' DEFAULT';
                if ($value[Model::DEFAULT_VALUE] === Model::DEFAULT_NULL || $value[Model::DEFAULT_VALUE] === Model::DEFAULT_NOW) {
                    $re_temp .= $value[Model::DEFAULT_VALUE];
                } else {
                    $re_temp .= (' \'' . $value[Model::DEFAULT_VALUE] . '\'');
                }
                if (in_array(Model::ON_UPDATE_NOW, $value)) {
                    $re_temp .= Model::ON_UPDATE_NOW;
                }
            }
            if (isset($value[Model::COMMENT])) {
                $re_temp .= (' COMMENT \'' . $value[Model::COMMENT] . '\'');
            }
            $arr[] = $re_temp;
        }
        return implode(', ', $arr);
    }

    protected static function _make_keys($keys)
    {
        $arr = array();
        foreach ($keys as $k => $v) {
            if (is_array($v)) {
                if ($k === Model::PRIMARY) {
                    $arr[] = $k . '(`' . implode('`, `', $v) . '`)';
                } elseif ($k === Model::FOREIGN) {
                    foreach ($v as $_k => $_v) {
                        $arr[] = 'FOREIGN KEY (`' . (is_array($_v[0]) ? implode('`, `', $_v[0]) : $_v[0]) . '`) REFERENCES `' . $_k . '` (`' . (is_array($_v[1]) ? implode('`, `', $_v[1]) : $_v[1]) . '`)';
                    }
                } else {
                    foreach ($v as $_k => $_v) {
                        $arr[] = $k . '(`' . (is_array($_v) ? implode('`, `', $_v) : $_v) . '`)';
                    }
                }
            } else {
                $arr[] = $k . '(`' . $v . '`)';
            }
        }
        return implode(', ', $arr);
    }
}
