<?php
/** 
 * 文件名(system/lib/Database.php)
 * 
 * 提供Database类的定义
 * 
 * @package     system
 * @subpackage  lib
 * @author      Nash<18695616095@163.com> 
 * @since       Version 1.0.0
 */
namespace system\lib;

/**
 * system\lib\Database类
 *
 * 这个类的实例作为数据库操作类
 *
 * @package     system
 * @subpackage  lib
 * @category    core
 * @author      Nash
 */
class Database
{
    protected $_di = null, $_pdo = null, $_stmt = null, $_param = null;

    /**
     * Database类构造方法
     *
     * 构建一个Database类的实例
     *
     * @return  void
     */
    public function __construct()
    {
        global $di;
        $this->_di = &$di;
        $this->_param = array();
    }

    /**
     * get_conn方法
     *
     * 获取数据库连接
     *
     * @return  PDO
     */
    public function get_conn()
    {
        if (is_null($this->_pdo)) {
            $arr = $this->_di->get('config')->module_data('db');
            $port = isset($arr['port']) ? $arr['port'] : '3306';
            $this->_pdo = new \PDO("mysql:host={$arr['host']};port={$port};dbname={$arr['dbname']}", $arr['username'], $arr['password']);
        }
        return $this->_pdo;
    }

    /**
     * exec方法
     *
     * 执行sql语句
     *
     * @param  $prepare  预处理sql语句
     * @param  $data     预处理绑定的数据
     * @return  mixed
     */
    public function exec($prepare, $data = array())
    {
        foreach ($data as $key => $value) {
            $this->setParam($key, $value);
        }
        $this->_stmt = $this->get_conn()->prepare($prepare);
        $re = $this->_stmt->execute($this->_param) ? $this : false;
        $this->_param = array();
        return $re;
    }

    /**
     * setParam方法
     *
     * 设置需要预设的值
     *
     * @param  $key       预设值的key
     * @param  $value     预设值的val
     * @return  void
     */
    public function setParam($key, $value)
    {
        $this->_param[':' . $key] = $value;
    }

    /**
     * all方法
     *
     * 从结果集中获取全部数据
     *
     * @return  mixed
     */
    public function all()
    {
        $re = $this->_stmt->fetchAll(\PDO::FETCH_ASSOC);
        $this->_stmt->closeCursor();
        return $re;
    }

    /**
     * one方法
     *
     * 从结果集中获取一行数据
     *
     * @return  mixed
     */
    public function one()
    {
        $re = $this->_stmt->fetch(\PDO::FETCH_ASSOC);
        $this->_stmt->closeCursor();
        return $re;
    }

    /**
     * trans方法
     *
     * 执行一个事务
     *
     * @param  $actions     需要执行的事务的处理函数
     * @return  bool
     */
    public function trans($actions)
    {
        if ($actions instanceof \Closure) {
            $this->get_conn()->setAttribute(\PDO::ATTR_AUTOCOMMIT, 0);
            $re = false;
            try {
                $this->get_conn()->beginTransaction();
                $actions($this);
                $this->get_conn()->commit();
                $re = true;
            } catch (\PDOException $e) {
                $this->get_conn()->rollback();
            } finally {
                $this->get_conn()->setAttribute(\PDO::ATTR_AUTOCOMMIT, 1);
                return $re;
            }
        }
        throw new \system\lib\Error("system\lib\Database::trans()方法的参数必须为闭包");
    }

    /**
     * insert_id方法
     *
     * 返回最后插入行的ID或序列值
     *
     * @return  string
     */
    public function insert_id()
    {
        return $this->get_conn()->lastInsertId();
    }

    /**
     * affected_rows方法
     *
     * 返回最近一次sql执行影响的行数
     *
     * @return  string
     */
    public function affected_rows()
    {
        return is_null($this->_stmt) ? 0 : $this->_stmt->rowCount();
    }
}
