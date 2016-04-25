<?php
/** 
 * 文件名(system/Di.php)
 * 
 * 提供Di类的定义
 * 
 * @package     system
 * @author      Nash<18695616095@163.com> 
 * @since       Version 1.0.0
 */

namespace system;

/**
 * system\Di类
 *
 * 这个类的实例代表框架运行的上下文环境
 *
 * @package     system
 * @category    core
 * @author      Nash
 */
class Di
{

    private $_php_version = null;
    private $_services = [];

    /**
     * Di类构造方法
     *
     * 构建一个Di类的实例
     *
     * @return  void
     */
    public function __construct()
    {
        # code...
    }

    /**
     * php_version方法
     *
     * 获取PHP版本
     *
     * @return  string
     */
    public function php_version()
    {
        return PHP_VERSION;
    }

    /**
     * run方法
     *
     * 执行框架请求
     *
     * @return  string
     */
    public function run()
    {
        return $this->get('router')->route();
    }

    /**
     * run_time方法
     *
     * 判断(获取)PHP运行环境
     *
     * @param   string    $run_time    需要判断的运行环境
     * @return  mixed
     */
    public function run_time($run_time = null)
    {
        if (is_null($run_time))
            return php_sapi_name();
        return strcasecmp(php_sapi_name(), $run_time) === 0;
    }

    /**
     * set方法
     *
     * 注册依赖
     *
     * @param   string    $key           需要注入的依赖名称
     * @param   Closure   $key_function  需要注入的依赖闭包
     * @return  system\Di
     */
    public function set($key, \Closure $key_function)
    {
        $this->_services[$key] = array(false, $key_function);
        return $this;
    }

    /**
     * share方法
     *
     * 注册共享依赖
     *
     * @param   string    $key           需要注入的依赖名称
     * @param   Closure   $key_function  需要注入的依赖闭包
     * @return  system\Di
     */
    public function share($key, \Closure $key_function)
    {
        $this->_services[$key] = array(true, $key_function);
        return $this;
    }

    /**
     * get方法
     *
     * 获取依赖实例
     *
     * @param   string   $key           需要注入的依赖名称
     * @return  mixed
     */
    public function get($key)
    {
        if (is_object($this->_services[$key][1]) && !is_callable($this->_services[$key][1])) {
            return $this->_services[$key][1];
        }
        return $this->_create_service($key, $this->_services[$key][0], $this->_services[$key][1]);
    }

    /**
     * _create_service方法
     *
     * 实例化依赖对象
     *
     * @param   string   $key           需要注入的依赖名称
     * @param   Closure  $key_function  需要设置的依赖闭包
     * @return  mixed
     */
    private function _create_service($key, $isshare, \Closure $callback)
    {
        if ($isshare) {
            $this->_services[$key][1] = $callback();
            return $this->_services[$key][1];
        }
        return $callback();
    }
}
