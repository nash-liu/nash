<?php
/** 
 * 文件名(system/http/Response.php)
 * 
 * 提供Response类的定义
 * 
 * @package     system
 * @subpackage  http
 * @author      Nash<18695616095@163.com> 
 * @since       Version 1.0.0
 */

namespace system\http;

/**
 * system\http\Response类
 *
 * 这个类的实例代表框架运行的HTTP应答
 *
 * @package     system
 * @subpackage  http
 * @category    core
 * @author      Nash
 */
class Response
{
    private $_status = array(
        '404' => '404 Not Found',
        '500' => '500 Internal Server Error',
        '200' => '200 OK'
    );
    private $_di = null, $_data = null, $_views = null, $_code = null;

    /**
     * Response类构造方法
     *
     * 构建一个Response类的实例
     *
     * @return  void
     */
    public function __construct()
    {
        global $di;
        $this->_di = &$di;
        $this->_data = array();
        $this->_views = array();
        $this->_code = '200';
    }

    /**
     * json方法
     *
     * 向浏览器返回json格式的应答信息
     *
     * @return  void
     */
    public function json()
    {
        $this->header("Status: " . $this->_status[$this->_code])->header("Content-Type: application/json; charset=utf-8");
        echo json_encode($this->_data);
    }

    /**
     * views方法
     *
     * 设置当前请求的视图
     *
     * @param   string   $views   视图文件相对视图目录的路径
     * @return  system\http\Response
     */
    public function views($views)
    {
        if (is_array($views)) {
            $this->_views = array_merge($this->_views ,$views);
        } else {
            $this->_views[] = $views;
        }
        return $this;
    }

    /**
     * pull方法
     *
     * 输出视图到客户端
     *
     * @return  void
     */
    public function pull()
    {
        if (count($this->_views) > 0) {
            $this->header("Status: " . $this->_status[$this->_code])->header("Content-Type: text/html; charset=utf-8");
            $this->_di->get('view')->assign($this->_data)->display($this->_views);
        }
    }

    /**
     * status方法
     *
     * 设置当前应答的状态
     *
     * @param   int   $code   应答的状态码
     * @return  system\http\Response
     */
    public function status($code)
    {
        $this->_code = "{$code}";
        return $this;
    }

    /**
     * header方法
     *
     * 设置当前应答的头部信息
     *
     * @param   string   $header   需要设置的应答的数据
     * @return  system\http\Response
     */
    public function header($header)
    {
        header($header);
        return $this;
    }

    /**
     * setVars方法
     *
     * 设置该对象的应答信息的属性、值
     *
     * @param   string   $att   设置的属性名称
     * @param   mixed    $val   设置的属性值
     * @return  system\http\Response
     */
    public function setVars($att, $val = null)
    {
        if (is_array($att)) {
            foreach ($att as $k => $v) {
                $this->setVars($k, $v);
            }
        } else {
            $this->_data[$att] = $val;
        }
        return $this;
    }

    /**
     * __set方法
     *
     * 魔术方法，定义该对象属性设置的行为
     *
     * @param   string   $att   设置的属性名称
     * @param   mixed    $val   设置的属性值
     * @return  void
     */
    public function __set($att, $val)
    {
        $this->_data[$att] = $val;
    }
}
