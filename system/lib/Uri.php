<?php
/** 
 * 文件名(system/lib/Uri.php)
 * 
 * 提供Uri类的定义
 * 
 * @package     system
 * @subpackage  lib
 * @author      Nash<18695616095@163.com> 
 * @since       Version 1.0.0
 */

namespace system\lib;

/**
 * system\lib\Uri类
 *
 * 这个类的实例代表框架运行的路由库
 *
 * @package     system
 * @subpackage  lib
 * @category    core
 * @author      Nash
 */
class Uri
{
    private $_di = null;

    /**
     * Uri类构造方法
     *
     * 构建一个Uri类的实例
     *
     * @return  void
     */
    public function __construct()
    {
        global $di;
        $this->_di = &$di;
    }

    public function prase_path()
    {
        $conf = $this->_di->get('config')->base_data('route');
        $uri = strtr($this->_di->get('req')->server('REQUEST_URI'), array('.htm'=>'', isset($conf['subdir'])?$conf['subdir']:''=>''));
        $uri_arr = explode('/', $uri);
        $module_name = $controller_name = $action_name = '';
        $params = array();
        if ($uri === '/') {
            $module_name = $conf['module'];
        } else {
            $module_name = empty($uri_arr[1]) ? $conf['module'] : $uri_arr[1];
            $controller_name = isset($uri_arr[2]) ? $uri_arr[2] : '';
            $action_name = isset($uri_arr[3]) ? $uri_arr[3] : '';
            $params = count($uri_arr) > 4 ? array_slice($uri_arr, 4) : array();
            if (!is_dir(WEB_DIR . DS . $module_name)) {
                $action_name = $controller_name;
                $controller_name = $module_name;
                $module_name = $conf['module'];
                $params = count($uri_arr) > 3 ? array_slice($uri_arr, 3) : array();
            }
        }
        $m_conf = $this->_di->get('config')->set_module($module_name)->module_data('route');
        if (empty($controller_name) && isset($m_conf['controller']) && !empty($m_conf['controller'])) {
            $controller_name = $m_conf['controller'];
        } elseif (empty($controller_name)) {
            $controller_name = $conf['controller'];
        }
        if (empty($action_name) && isset($m_conf['action']) && !empty($m_conf['action'])) {
            $action_name = $m_conf['action'];
        } elseif (empty($action_name)) {
            $action_name = $conf['action'];
        }
        return array(
            'module' => $module_name
            , 'controller' => ucfirst($controller_name)
            , 'action' => $action_name
            , 'params' => $params
        );
    }
}
