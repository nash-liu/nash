<?php
/** 
 * 文件名(system/lib/Router.php)
 * 
 * 提供Router类的定义
 * 
 * @package     system
 * @subpackage  lib
 * @author      Nash<18695616095@163.com> 
 * @since       Version 1.0.0
 */

namespace system\lib;

/**
 * system\lib\Router类
 *
 * 这个类的实例代表框架运行的路由库
 *
 * @package     system
 * @subpackage  lib
 * @category    core
 * @author      Nash
 */
class Router
{
    private $_di = null;

    /**
     * Router类构造方法
     *
     * 构建一个Router类的实例
     *
     * @return  void
     */
    public function __construct()
    {
        global $di;
        $this->_di = &$di;
    }

    public function route()
    {
        $uri = $this->_di->get('uri')->prase_path();
        $controller_class_name = "{$uri['module']}\\controller\\{$uri['controller']}";
        try{
            $contro = new $controller_class_name;
            $contro->call($uri['action'], $uri['params']);
        }catch(\Exception $e){
            echo $e->xdebug_message;
        }
    }
}
