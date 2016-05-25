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

use \system\inc\database\mysql\DbTypeDatetime;
use \system\inc\database\mysql\DbTypeInt;

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
abstract class Model
{
    const NOT_NULL = 'NOT NULL';
    protected $_key_data = array('_id'=>null,'_create_time'=>null,'_update_time'=>null,'_delete_time'=>null);
    protected $_data = array();
    protected $_ignore = array('_id', '_create_time', '_update_time', '_delete_time');
    protected $_table_name;
    abstract protected static function _fileds();
    public function __construct($id = null) // 查找单条数据（或创建一条新的数据）
    {
        $class_name = get_class($this);
        $arr = $class_name::_fileds();
        foreach ($arr as $key => $value) {
            $this->_data[$key] = $value;
            $this->_ignore[] = $key;
        }
        $tb_name = explode('\\', strtolower($class_name));
        $this->_table_name = 'nash_' . strtolower(array_pop($tb_name));
        $this->_key_data['_id'] = (new DbTypeInt);
        $this->_key_data['_create_time'] = (new DbTypeDatetime)->def_val(DbTypeDatetime::NOW);
        $this->_key_data['_update_time'] = (new DbTypeDatetime)->def_val(null)->upd_val(DbTypeDatetime::NOW);
        $this->_key_data['_delete_time'] = (new DbTypeDatetime)->def_val(null);
        if (!is_null($id)) {
            $this->_key_data['_id']->val($id);
            if (!$this->_sync()) {
                $this->_key_data['_id']->destory();
            }
        }
    }
    public function delete()     // 删除数据
    {
        global $di;
        $di->get('db')->exec("UPDATE `{$this->_table_name}` SET `_delete_time` = NOW() WHERE `_id` = :_id", array('_id'=>$this->_key_data['_id']->val()));
        $this->_sync();
    }
    public function destory()    // 销毁数据
    {
        global $di;
        $di->get('db')->exec("DELETE FROM `{$this->_table_name}` WHERE `_id` = :_id", array('_id'=>$this->_key_data['_id']->val()));
        $this->_sync();
    }
    public function save()       // 保存数据（新增或修改）
    {
        global $di;
        if (is_null($this->_key_data['_id']->val())) {
            // 新增
            $f_arr = array();
            foreach ($this->_data as $key => $value) {
                $di->get('db')->setParam($key, $value->val());
                $f_arr["`{$key}`"] = ":{$key}";
            }
            if ($di->get('db')->exec('INSERT INTO `' . $this->_table_name . '` (' . implode(', ', array_keys($f_arr)) . ') VALUES (' . implode(', ', $f_arr) . ')')) {
                $this->_key_data['_id']->val($di->get('db')->insert_id());
            } else {
                echo 'error';
            }
        } else {
            // 修改
            $f_arr = array();
            foreach ($this->_data as $key => $value) {
                $di->get('db')->setParam($key, $value->val());
                $f_arr[] = "`{$key}` = :{$key}";
            }
            $di->get('db')->exec('UPDATE `' . $this->_table_name . '` SET ' . implode(', ', $f_arr) . ' WHERE `_id` = :_id', array('_id'=>$this->_key_data['_id']->val()));
        }
        $this->_sync();
    }
    public function is_deleted() // 数据是否已被删除
    {
        // return $this->_key_data['_id']->val() !== null && $this->_key_data['_delete_time']->val() !== null;
    }
    public function __get($key)  // 获取数据
    {
        if ($key === '_id' || $key === '_create_time' || $key === '_update_time' || $key === '_delete_time') {
            return $this->_key_data[$key]->val();
        }
        if (isset($this->_data[$key])) {
            return $this->_data[$key]->val();
        }
    }
    public function __call($func, $args) // 获取数据对象
    {
        if (isset($this->_data[$func]) && isset($args[0]) && get_class($args[0]) === get_class($this->_data[$func])) {
            $this->_data[$func] = $args[0];
        } elseif (isset($this->_data[$func]) && isset($args[0])) {
            $this->_data[$func]->val($args[0]);
        } elseif (isset($this->_data[$func])) {
            return $this->_data[$func];
        }
    }
    public function __set($key, $value) // 设置数据
    {
        if (isset($this->_data[$key])) {
            $this->_data[$key]->val($value);
        }
    }
    private function _sync()
    {
        global $di;
        $re = $di->get('db')->exec('SELECT `' . implode('`, `', $this->_ignore) . '` FROM `' . $this->_table_name . '` WHERE `_id` = :_id AND `_delete_time` IS NULL', array('_id'=>$this->_key_data['_id']->val()));
        if ($re) {
            $arr = $re->one();
            if ($arr) {
                foreach ($arr as $key => $value) {
                    if (array_key_exists($key, $this->_key_data)) {
                        $this->_key_data[$key]->val($value);
                    } elseif (array_key_exists($key, $this->_data)) {
                        $this->_data[$key]->val($value);
                    }
                }
            } else {
                return false;
            }
        }
        return $re !== false;
    }
}
