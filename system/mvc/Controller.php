<?php
/** 
 * 文件名(system/mvc/Controller.php)
 * 
 * 提供Controller类的定义
 * 
 * @package     system
 * @subpackage  mvc
 * @author      Nash<18695616095@163.com> 
 * @since       Version 1.0.0
 */

namespace system\mvc;

/**
 * system\mvc\Controller类
 *
 * 这个类的实例作为控制器基类
 *
 * @package     system
 * @subpackage  mvc
 * @category    core
 * @author      Nash
 */
class Controller
{
    protected $_di = null;

    /**
     * Controller类构造方法
     *
     * 构建一个Controller类的实例
     *
     * @return  void
     */
    public function __construct()
    {
        global $di;
        $this->_di = &$di;
    }

    /**
     * call方法
     *
     * 执行框架请求
     *
     * @param  $method_name  呼叫的控制器方法
     * @param  $param        呼叫方法的参数
     * @return mixed
     */
    public function call($method_name, $param)
    {
        if (method_exists($this, $method_name)) {
            return call_user_func_array(array($this, $method_name), $param);
        }
        throw new \Exception("Error Processing Request", 1);
    }
}
