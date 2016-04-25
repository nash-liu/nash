<?php
/** 
 * 文件名(system/http/Request.php)
 * 
 * 提供Request类的定义
 * 
 * @package     system
 * @subpackage  http
 * @author      Nash<18695616095@163.com> 
 * @since       Version 1.0.0
 */

namespace system\http;

/**
 * system\http\Request类
 *
 * 这个类的实例代表框架运行的HTTP请求
 *
 * @package     system
 * @subpackage  http
 * @category    core
 * @author      Nash
 */
class Request
{
    private $_di = null;

    /**
     * Request类构造方法
     *
     * 构建一个Request类的实例
     *
     * @return  void
     */
    public function __construct()
    {
        global $di;
        $this->_di = &$di;
    }

    /**
     * method方法
     *
     * 获取当前请求的请求方式
     *
     * @return  string
     */
    public function method()
    {
        return strtoupper($this->server('REQUEST_METHOD'));
    }

    /**
     * ip方法
     *
     * 获取当前请求的客户端地址
     *
     * @return  string
     */
    public function ip()
    {
        return $this->server('REMOTE_ADDR');
    }

    /**
     * get方法
     *
     * 获取当前请求的get参数
     *
     * @param   mixed   $index   get参数中需要取出的参数的索引
     * @return  mixed
     */
    public function get($index = null)
    {
        return $this->_fetch_from_array($_GET, $index);
    }

    /**
     * post方法
     *
     * 获取当前请求的post参数
     *
     * @param   mixed   $index   post参数中需要取出的参数的索引
     * @return  mixed
     */
    public function post($index = null)
    {
        return $this->_fetch_from_array($_POST, $index);
    }

    /**
     * cookie方法
     *
     * 获取当前客户端的cookie参数
     *
     * @param   mixed   $index   cookie参数中需要取出的参数的索引
     * @return  mixed
     */
    public function cookie($index = null)
    {
        return $this->_fetch_from_array($_COOKIE, $index);
    }

    /**
     * server方法
     *
     * 获取当前客户端的server参数
     *
     * @param   mixed   $index   server参数中需要取出的参数的索引
     * @return  mixed
     */
    public function server($index = null)
    {
        return $this->_fetch_from_array($_SERVER, $index);
    }

    /**
     * set_cookie方法
     *
     * 设置当前客户端的cookie参数
     *
     * @param   mixed   $index     cookie参数中需要设置的参数的索引
     * @param   mixed   $value     cookie参数中需要设置的参数的值
     * @param   mixed   $time_out  cookie参数中需要设置的参数得超时时间
     * @return  mixed
     */
    public function set_cookie($index, $value = null, $time_out = null)
    {
        if (is_array($index)) {
            foreach ($index as $k => $v) {
                $this->set_cookie($k, $v, $value);
            }
        } elseif (is_string($index)) {
            $time_out = is_null($time_out) ? 3600 : $time_out;
            $time_out = is_null($value) ? time() - $time_out : time() + $time_out;
            setcookie($index, $value, $time_out);
        }
    }

    /**
     * unset_cookie方法
     *
     * 清除当前客户端的cookie参数
     *
     * @param   mixed   $index     cookie参数中需要清除的参数的索引
     * @return  mixed
     */
    public function unset_cookie($index = null)
    {
        $this->set_cookie(is_null($index) ? array_keys($_COOKIE) : $index, null);
    }

    /**
     * _fetch_from_array方法
     *
     * 从超全局变量中获取需要取得的参数
     *
     * @param   array   &$arr    $_GET, $_POST, $_COOKIE, $_SERVER, 等.
     * @param   mixed   $index   数组中需要取出的参数的索引
     * @return  mixed
     */
    private function _fetch_from_array(&$arr, $index = null)
    {
        if (is_null($index)) {
            return $this->_fetch_from_array($arr, array_keys($arr));
        } elseif (is_array($index)) {
            foreach ($index as $key => $value) {
                $index[$value] = $this->_fetch_from_array($arr, $value);
                unset($index[$key]);
            }
            return $index;
        } elseif (isset($arr[$index])) {
            return $arr[$index];
        }
        return null;
    }
}
