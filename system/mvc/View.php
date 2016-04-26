<?php
/** 
 * 文件名(system/mvc/View.php)
 * 
 * 提供View类的定义
 * 
 * @package     system
 * @subpackage  mvc
 * @author      Nash<18695616095@163.com> 
 * @since       Version 1.0.0
 */

namespace system\mvc;

/**
 * system\mvc\View类
 *
 * 这个类的实例作为视图类基类
 *
 * @package     system
 * @subpackage  mvc
 * @category    core
 * @author      Nash
 */
class View
{
    private $_di = null;
    private $_vars = array();

    /**
     * View类构造方法
     *
     * 构建一个View类的实例
     *
     * @return  void
     */
    public function __construct()
    {
        global $di;
        $this->_di = &$di;
    }

    /**
     * assign方法
     *
     * 设置视图变量
     *
     * @param   string  $key    设置到视图中的变量名称
     * @param   mixed   $value  设置到视图中的变量值
     * @return  system\mvc\View
     */
    public function assign($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->assign($k, $v);
            }
        } elseif (is_string($key) && !is_numeric($key)) {
            $this->_vars[$key] = $value;
        } else {
            throw new \system\lib\Error("向视图中注册变量使用了无效的参数名称");
        }
        return $this;
    }

    /**
     * display方法
     *
     * 输出视图
     *
     * @param   mixed  $view  需要输出的视图名或视图名数组
     * @return  void
     */
    public function display($view)
    {
        foreach ($this->_vars as $key => $value) {
            $$key = $value;
        }
        $uri = $this->_di->get('uri')->prase_path();
        if (is_array($view)) {
            foreach ($view as $value) {
                require $this->_check_path(WEB_DIR . DS . $uri['module'] . DS . 'view' . DS . strtr($value, array('/' => DS, '\\' => DS)) ,'.phtm');
            }
        } else {
            require $this->_check_path(WEB_DIR . DS . $uri['module'] . DS . 'view' . DS . strtr($view, array('/' => DS, '\\' => DS)) ,'.phtm');
        }
    }

    /**
     * _check_path方法
     *
     * 检查文件是否存在
     *
     * @param   string  $path  需要检查的文件名
     * @param   string  $fixed 需要动态检查的文件名后缀
     * @return  string
     */
    private function _check_path($path, $fixed)
    {
        if (is_file($path)) {
            return $path;
        } elseif (is_file($path . $fixed)) {
            return $path . $fixed;
        }
        throw new \system\lib\Error("找不到定义的视图");
    }
}