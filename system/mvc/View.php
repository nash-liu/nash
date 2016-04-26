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
 * 这个类的实例作为控制器基类
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
        }
        $this->_vars[$key] = $value;
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
        if (is_array($view)) {
            foreach ($view as $value) {
                require WEB_DIR . DS . 'app' . DS . 'view' . DS . $value . '.phtml';
            }
        } else {
            require WEB_DIR . DS . 'app' . DS . 'view' . DS . $view . '.phtml';
        }
    }
}