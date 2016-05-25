<?php
namespace system\inc\database\mysql;

class DbTypeInt implements \system\inc\database\DbType
{
    private $_def_val, $_upd_val, $_val;
    public function def_val($val)
    {
        $this->_def_val = $val;
        return $this;
    }
    public function upd_val($val)
    {
        $this->_upd_val = $val;
        return $this;
    }
    public function val()
    {
        $args = func_get_args();
        if (isset($args[0])) {
            $this->_val = intval($args[0]);
            return $this;
        }
        return $this->_val;
    }
    public function destory()
    {
        $this->_val = null;
    }
}