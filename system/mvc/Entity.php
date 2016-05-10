<?php
/** 
 * 文件名(system/mvc/Entity.php)
 * 
 * 提供Entity类的定义
 * 
 * @package     system
 * @subpackage  mvc
 * @author      Nash<18695616095@163.com> 
 * @since       Version 1.0.0
 */

namespace system\mvc;

/**
 * system\mvc\Entity类
 *
 * 这个类的实例作为数据实体基类
 *
 * @package     system
 * @subpackage  mvc
 * @category    core
 * @author      Nash
 */
class Entity
{
    protected $_di = null;

    /**
     * Entity类构造方法
     *
     * 构建一个Entity类的实例
     *
     * @return  void
     */
    public function __construct()
    {
        global $di;
        $this->_di = &$di;
    }
    
    public function FunctionName($value='')
    {
        # code...
    }
}
