<?php
/** 
 * 文件名(system/rest/Resource.php)
 * 
 * 提供Resource类的定义
 * 
 * @package     system
 * @subpackage  rest
 * @author      Nash<18695616095@163.com> 
 * @since       Version 1.0.0
 */

namespace system\rest;

/**
 * system\rest\Resource类
 *
 * 这个类的实例作为Restful资源控制器基类
 *
 * @package     system
 * @subpackage  rest
 * @category    core
 * @author      Nash
 */
class Resource extends \system\mvc\Controller
{
    private $_di = null;

    /**
     * Resource类构造方法
     *
     * 构建一个Resource类的实例
     *
     * @return  void
     */
    public function __construct()
    {
        global $di;
        $this->_di = &$di;
    }
}
