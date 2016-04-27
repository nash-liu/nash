<?php
/** 
 * 文件名(system/lib/Error.php)
 * 
 * 提供Error类的定义
 * 
 * @package     system
 * @subpackage  lib
 * @author      Nash<18695616095@163.com> 
 * @since       Version 1.0.0
 */

namespace system\lib;

/**
 * system\lib\Error类
 *
 * 这个类的实例代表框架异常
 *
 * @package     system
 * @subpackage  lib
 * @category    core
 * @author      Nash
 */
class Error extends \Exception
{
    private $_di = null;

    /**
     * Error类构造方法
     *
     * 构建一个Error类的实例
     *
     * @return  void
     */
    public function __construct($message, $code = 500, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        global $di;
        $this->_di = &$di;
    }

    /**
     * error_res方法
     *
     * 输出错误信息的视图展示
     *
     * @return  void
     */
    public function error_res()
    {
        $this->_di->get('res')->status($this->getCode())->setVars(array(
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
            'file' => $this->getFile(),
            'line' => $this->getLine()
        ))->views('error/' . $this->getCode())->pull();
    }
}
