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
    private $status = array(
        '404' => '404 Not Found',
        '200' => '200 OK'
    );
    private $_di = null;

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
    }

    /**
     * json方法
     *
     * 向浏览器返回json格式的应答信息
     *
     * @param   mixed   $data   需要格式化为json的数据
     * @param   mixed   $code   应答的状态码，默认值为200
     * @return  void
     */
    public function json($data, $code = 200)
    {
        $this->status($code)->header("Content-Type: application/json; charset=utf-8");
        echo json_encode($data);
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
        return $this->header("Status: " . $this->status["{$code}"]);
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
}
