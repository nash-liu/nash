<?php
/** 
 * 文件名(system/lib/Config.php)
 * 
 * 提供Config类的定义
 * 
 * @package     system
 * @subpackage  lib
 * @author      Nash<18695616095@163.com> 
 * @since       Version 1.0.0
 */

namespace system\lib;

/**
 * system\lib\Config类
 *
 * 这个类的实例代表框架的配置类库
 *
 * @package     system
 * @subpackage  lib
 * @category    core
 * @author      Nash
 */
class Config
{
    private $_di = null;
    private $_config = null;

    /**
     * Config类构造方法
     *
     * 构建一个Config类的实例
     *
     * @param   string   $default_config_file_path   框架配置文件所在的位置（绝对位置）
     * @return  void
     */
    public function __construct($default_config_file_path)
    {
        global $di;
        $this->_di = &$di;
        if (is_file($default_config_file_path)) {
            $this->_config = array('base'=>parse_ini_file($default_config_file_path, true));
        } else {
            throw new \system\lib\Error("框架配置文件无法读取");
        }
    }

    /**
     * base_data方法
     *
     * 获取框架默认配置的指定方案
     *
     * @param   string   $key   需要获取的配置方案的名称
     * @return  array
     */
    public function base_data($key)
    {
        return $this->_get_arr('base', $key);
    }

    /**
     * module_data方法
     *
     * 获取框架当前请求所在模块配置的指定方案
     *
     * @param   string   $key   需要获取的配置方案的名称
     * @return  array
     */
    public function module_data($key)
    {
        return $this->_get_arr('module', $key);
    }

    /**
     * _get_arr方法
     *
     * 从配置方案的数组中选取指定的配置方案
     *
     * @param   string   $type  需要获取的配置方案所在的区域，"base"指系统默认配置方案，"module"指当前请求的模块的配置方案
     * @param   string   $key   需要获取的配置方案的名称
     * @return  array
     */
    private function _get_arr($type, $key)
    {
        if (isset($this->_config[$type][$key])) {
            return $this->_config[$type][$key];
        }
        throw new \system\lib\Error("正在尝试获取不存在的配置数据");
    }

    /**
     * set_module方法
     *
     * 设置当前请求所请求的模块名称
     *
     * @param   string   $module   当前请求所请求的模块名称
     * @return  system\lib\Config
     */
    public function set_module($module)
    {
        $file_path = WEB_DIR . DS . $module . DS . 'conf.inc';
        if (is_file($file_path)) {
            $this->_config['module'] = require $file_path;
            return $this;
        } else {
            throw new \system\lib\Error("请求配置文件无法读取");
        }
    }
}
