<?php
namespace system\inc\database;

interface DbType
{
    public function def_val($val);
    public function upd_val($val);
    public function val();
    public function destory();
}